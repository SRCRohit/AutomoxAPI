<?php

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

function verifyError($code, $user){
    switch($code){
        case 400:
            Log::channel('custom')->info('Invalid request from user '.$user->email, ['errorCode' => $code]);
            break;
        case 401:
            Log::channel('custom')->info('Access token is missing or invalid from user '.$user->email, ['errorCode' => $code]);
            break;
        case 403:
            Log::channel('custom')->info('You do not have permission to perform this action from user '.$user->email, ['errorCode' => $code]);
            break;
        case 404:
            Log::channel('custom')->info('Entity not found from user '.$user->email, ['errorCode' => $code]);
            break;
        case 429:
            Log::channel('custom')->info('Too many requests from user '.$user->email, ['errorCode' => $code]);
            break;
        case 503:
            Log::channel('custom')->info('Service Unavailable from user '.$user->email, ['errorCode' => $code]);
            break;
        default:
            Log::channel('custom')->info('Unknown error from user '.$user->email);
            break;
    }
}

function getErrorMessage($code)
{
    switch($code){
        case 400:
            return 'Invalid request';
            break;
        case 401:
            return 'Access token is missing or invalid';
            break;
        case 403:
            return 'You do not have permission to perform this action';
            break;
        case 404:
            return 'Entity not found';
            break;
        case 429:
            return 'Too many requests';
            break;
        case 503:
            return 'Service Unavailable';
            break;
        default:
            return 'Unknown error';
            break;
    }
}

function formatBytes($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    // Uncomment one of the following alternatives
     $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}


function company_update($company)
{
    if($company->organisation_id && $company->user_id){
        $client = new Client();
        $url = "https://console.automox.com/api/users/".$company->user_id."/api_keys?o=".$company->organisation_id;
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer '.$company->api,
            ]
        ]);
        $response = json_decode($response->getBody());
        if(count($response->results)){
            $data = \App\Models\Company::find($company->id);
            $data->api_expires_at = explode('T',$response->results[0]->expires_at)[0];
            $data->api_created_at = explode('T',$response->results[0]->created_at)[0];
            $data->save();
        }
    }
}

function organisation_insert()
{
    $companies = \App\Models\Company::all();
    foreach ($companies as $company)
    {
        try{
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company->api,
                ]
            ]);
            $organisations = json_decode($response->getBody());
            if($organisations){
                foreach ($organisations as $organisation){
                    $org = \App\Models\Organisation::where("org_id",$organisation->id)->where('company_id',$company->id)->first();
                    if(!$org) {
                        $data = new \App\Models\Organisation;
                        $data->org_id = $organisation->id;
                        $data->company_id = $company->id;
                        $data->name = $organisation->name;
                        $data->save();
                    }
                }
            }
        }catch (\Exception $e){
            Log::channel('custom')->info('Invalid request from company '.$company->name, ['errorMessage' =>  $e->getMessage()]);
        }
    }
}

function get_devices($company_id = '')
{
    $client = new Client();
    $organisations = \App\Models\Organisation::all();
    foreach ($organisations as $organisation){
        if($company_id && ($company_id != $organisation->company_id)){
            continue;
        }
        $url = "https://console.automox.com/api/servers?o=".$organisation->org_id;
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer '.$organisation->company->api,
            ]
        ]);
        $response = json_decode($response->getBody(), true);
        foreach ($response as $data){

            $data['device_id'] = $data['id'];
            unset($data['id']);
            $insert = [];
            $insert['company_id'] = $organisation->company_id;
            $insert['organisation_id'] = $organisation->org_id;
            foreach ($data as $column => $value){
                if(is_array($value)){
                    if (Schema::hasColumn('devices', $column)) {
                        $insert[$column] = json_encode($value);
                    }else{
                        Schema::table('devices', function (Blueprint $table) use($column) {
                            $table->tinyText($column)->nullable()->after('device_id');
                        });
                        $insert[$column] = json_encode($value);
                    }
                }else{
                    if (Schema::hasColumn('devices', $column)) {
                        $insert[$column] = $value;
                    }else{
                        Schema::table('devices', function (Blueprint $table) use($column) {
                            $table->tinyText($column)->nullable()->after('device_id');
                        });
                        $insert[$column] = $value;
                    }
                }
            }
            $db_row = \App\Models\Device::where('device_id', $data['device_id'])->where('organisation_id',
                $organisation->org_id)->where('company_id', $organisation->company_id)
                ->first();
            if($db_row){
                try{
                    \App\Models\Device::where('id',$db_row->id)->update($insert);
                }catch (\Exception $exception){
                    echo $exception->getMessage();
                    Log::channel('custom')->info('Error while updating row on device id '.$data['device_id'],['errorMessage' =>
                        $exception->getMessage()]);
                }
            }else{
                try{
                    \App\Models\Device::insert($insert);
                }catch (\Exception $exception){
                    echo $exception->getMessage();
                    Log::channel('custom')->info('Error while inserting row',['errorMessage' => $exception->getMessage()]);
                }
            }

        }
    }
}

function checkPermission(){
    if(auth()->user()->type == 'user') {
        $current_route = Request::path();
        if (Route::currentRouteName() != 'dashboard') {
            $permission = json_decode(auth()->user()->permission, TRUE);
            $current_route = str_replace('-','_', $current_route);
            if(array_key_exists($current_route, $permission)){
                return TRUE;
            }
            return FALSE;
        }
        return TRUE;
    }
    return TRUE;
}

function log_error($message, $arr){
    Log::channel('custom')->info($message, $arr);
}