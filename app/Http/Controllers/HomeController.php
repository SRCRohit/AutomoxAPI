<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Admin;
use GuzzleHttp\Client;
use App\Models\UserCount;
use App\Models\daily_activity_report;
use PDF;
use App\Helpers;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $companies = \App\Models\Company::all();
        if(count($companies)){
            return view('dashboard');
        }else{
            return redirect()->route('managecompany');
        }
    }

    public function error_view()
    {
        return view('error-view');
    }
    
    
        // ======================================================================
    //                         Activity Logs
    // ======================================================================
       public function activity_logs(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }else{
        $activityLogs = Admin::all();
        return view('activitylogs', ['activityLogs' => $activityLogs]);
        }
    //  return view('activitylogs');
    }


    public function company_access(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $users      = [];
        $orgs       = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){

            $org = $request->get('organisation');

            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
        if($org) {

            $client = new Client();
            $url = "https://console.automox.com/api/users?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $users = json_decode($response->getBody());
        }
        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
            $data[] = ['ID','Company Name','MSP','Name','Email Id','Role','License Issue Date','License Expiry'];
            foreach($users as $user){
                $data[] = [
                    $user->id,
                    $orgs->name,
                    $company_detail->name,
                    $user->firstname.' '.$user->lastname,
                    $user->email,
                    $user->rbac_roles[array_search($org, array_column($user->rbac_roles, 'organization_id'))]->name,
                    explode('T',$orgs->create_time)[0],
                    ($orgs->trial_end_time?explode('T',$orgs->trial_end_time)[0]:'N/A')
                ];
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }
        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.company-access', compact('users', 'org', 'orgs', 'company_detail'))->setPaper('a4', 'landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }
        return view('company-access',compact('users', 'org', 'orgs', 'company_detail'));
    }
    
      public function company_view(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $orgs       = [];
        $users      = [];
        $company_detail = '';
         $UserCount = '';
    
        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }
    
        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
    
        if($request->has('daterange')){
            $daterange = $request->get('daterange');
        }
        
    //------------------------------------This data added to count devices--------------------------//    
         if($org) {
                $UserCount = \App\Models\UserCount::where('company_id', $org)->get();
                if ($UserCount) {
                    // echo $UserCount;
                } else {
                   echo "<script>alert('no data found')</script>";
                }
            }
    //------------------------------------This data added to count devices--------------------------//  
        if ($request->has('excel') || $request->has('csv')) {
        $filename = $orgs->name . '.xlsx';
        if ($request->has('csv')) {
            $filename = $orgs->name . '.csv';
        }
        $data[] = ['ID', 'Date', 'Company Name', 'Account Status','License Issue Date', 'No. of Licensed Issued', 'MSP', 'Need Attention', 'Need Reboot', 'Need Compatible','30+ Days Disconnected Device',];
    
        $startDate = date('Y-m-d', strtotime(explode(' - ', $daterange)[0]));
        $endDate = date('Y-m-d', strtotime(explode(' - ', $daterange)[1]));
        $startTime = strtotime($startDate);
        $endTime = strtotime($endDate);
    
        foreach ($UserCount as $UserCounts) {
                $thisDate = date('Y-m-d');
    
                $data[] = [
                    $org,
                    $UserCounts->createdAt->format('Y-m-d') ,
                    $orgs->name,
                    'Active',
                    $UserCounts->license_issue_date,
                    $UserCounts->user_number,
                    $UserCounts->msp,
                    $UserCounts->need_attention,
                    $UserCounts->need_reboot,
                    $UserCounts->not_compatible,
                    $UserCounts->disconnected_devices,
                ];
            
        }
    
        $export = new ExcelExport($data);
        return \Excel::download($export, $filename);
    }

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.company-view', compact('daterange','UserCount','org', 'orgs', 'company_detail'))->setPaper('a4','landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }
    
        return view('company-view',compact('users','UserCount','orgs', 'org', 'daterange', 'company_detail'));
    }
    
    
 public function tagsremoval(Request $request)
    {
        if (!checkPermission()) {
            return redirect()->route('errorview');
        }
    
        $org = '';
        $daterange = '';
        $users = [];
        $orgs = [];
        $company_detail = '';
         $groups = []; // Initialize $groups
    
        if ($request->has('company')) {
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id', $company)->first();
    
            if (!$company_detail) {
                return redirect()->route('errorview')->with('error', 'Invalid Company details');
            }
        }
    
        if ($request->has('organisation')) {
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $company_detail->api,
                ],
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
    
        if ($request->has('daterange')) {
            if ($request->get('daterange')) {
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }
    
        if ($request->has('id')) {
            $userId = $request->get('id');
            $client = new Client();
    
            if ($daterange) {
                $url = "https://console.automox.com/api/servers?o=" . $org . "&userId=" . $userId . "&startDate=" . $start . "&endDate=" . $end;
            } else {
                $url = "https://console.automox.com/api/servers?o=" . $org . "&userId=" . $userId."&page=1";
            }
    
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $company_detail->api,
                ],
            ]);
            $user_activity = json_decode($response->getBody());
    
            $table_data = '';
            if ($user_activity) {
                foreach ($user_activity as $activity) {
                    list($date, $time) = explode('T', $activity->create_time)[0];
                    $data_flg = $activity->data ? 1 : 0;
                    $table_data .= '<tr><td>' . $activity->id . '</td><td>' . ucwords(str_replace('.', ' ', $activity->name)) . '</td><td>' . ($data_flg ? $activity->data->orgname : 'N/A') . '</td><td>' . ($data_flg ? $activity->data->firstname . ' ' . $activity->data->lastname : 'N/A') . '</td><td>' . ($data_flg ? $activity->data->email : 'N/A') . '</td><td>' . date('Y-m-d H:i:s', strtotime($activity->create_time)) . '</td></tr>';
                }
            }
    
            return response()->json(['table_data' => $table_data]);
        }
    
        // Add the following lines in your controller before returning the view
   // ...

if ($org) {
    $client = new Client();
    $page = 0; // Start with the first page
    $users = collect(); // Initialize a Laravel Collection

    while (true) {
        $url = "https://console.automox.com/api/servers?o=" . $org . "&page=" . $page;
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $company_detail->api,
            ],
        ]);

        $usersPage = json_decode($response->getBody());

        // Check if there are users on the current page
        if ($usersPage) {
            // Add users from the current page to the collection
            $users = $users->merge($usersPage);
            $page++; // Increment the page number for the next iteration
        } else {
            break; // No more users, exit the loop
        }
    }

    // Rest of your code...

    $url = "https://console.automox.com/api/servergroups/?o=" . $org;
    $response = $client->get($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $company_detail->api,
        ],
    ]);
    $groups = json_decode($response->getBody(), TRUE);

    foreach ($users as $key => $user) {
        $group_name = $groups[array_search($user->server_group_id, array_column($groups, 'id'))]['name'];
        $users[$key]->server_group_id = $group_name ? $group_name : 'Default';
    }
}



 if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
           $data = [['ID', 'Name', 'Tags', 'Device Status']];

        foreach ($users as $device) {
            if($device->status->device_status == "ready"){
                $devicename = $device->name;
                foreach ($device->tags as $tags) {
                    $tag = $tags;
                }
                $devicestatus = $device->status->device_status;
                }
            
                $data[] = [
                    $device->id,
                    $devicename,
                    $tag,
                    $devicestatus,
            ];
        }
        
        if ($request->has('excel') || $request->has('csv')) {
            $filename = $orgs->name . '.xlsx';
            if ($request->has('csv')) {
                $filename = $orgs->name . '.csv';
            }
        
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }
     
 }

        // if($request->has('pdf')){
        //     $pdf = Pdf::loadView('pdf.tagsremoval', compact('users', 'orgs', 'org', 'daterange', 'company_detail', 'groups'))->setPaper('a4','landscape');
        //     return $pdf->stream($orgs->name.'.pdf');
        // }

    
    return view('tagsremoval', compact('users', 'orgs', 'org', 'daterange', 'company_detail', 'groups'));
    
    }
    
    
      public function dailyreport(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $orgs       = [];
        $users      = [];
        $company_detail = '';
         $UserCount = '';
        $dailydevicereport = '';
    
        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }
    
        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
    
        if($request->has('daterange')){
            $daterange = $request->get('daterange');
        }
        
    //------------------------------------This data added to count devices--------------------------//    
         if($org) {
                $dailydevicereport = \App\Models\daily_activity_report::where('company_id', $org)->get();
                if ($dailydevicereport) {
                    // echo $UserCount;
                } else {
                   echo "<script>alert('no data found')</script>";
                }
            }
    //------------------------------------This data added to count devices--------------------------//  
        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
            $data[] = ['ID','Date','Company Name','Account Status','MSP','License Issue Date','License Expiry Date','No. of Licensed Issued'];
            $startDate = date('Y-m-d', strtotime(explode(' - ', $daterange)[0]));
            $endDate = date('Y-m-d', strtotime(explode(' - ', $daterange)[1]));
            $startTime = strtotime($startDate);
            $endTime = strtotime($endDate);
            
            for ( $i = $startTime; $i <= $endTime; $i = $i + 86400 ) {
                $thisDate = date( 'Y-m-d', $i );
    
                $data[] = [
                    $org,
                    $thisDate,
                    $orgs->name,
                    'Active',
                    $company_detail->name,
                    explode('T',$orgs->create_time)[0],
                    ($orgs->trial_end_time?explode('T',$orgs->trial_end_time)[0]:'N/A'),
                ];
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }
        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.dailyreport', compact('daterange','org', 'orgs', 'company_detail'))->setPaper('a4','landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }
    
        return view('dailyreport',compact('users','dailydevicereport','orgs', 'org', 'daterange', 'company_detail'));
    }

     public function connections(Request $request)
    {
        if (!checkPermission()) {
            return redirect()->route('errorview');
        }
    
        $org = '';
        $daterange = '';
        $users = [];
        $orgs = [];
        $company_detail = '';
         $groups = []; // Initialize $groups
    
        if ($request->has('company')) {
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id', $company)->first();
    
            if (!$company_detail) {
                return redirect()->route('errorview')->with('error', 'Invalid Company details');
            }
        }
    
        if ($request->has('organisation')) {
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $company_detail->api,
                ],
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
    
        if ($request->has('daterange')) {
            if ($request->get('daterange')) {
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }
    
        if ($request->has('id')) {
            $userId = $request->get('id');
            $client = new Client();
    
            if ($daterange) {
                $url = "https://console.automox.com/api/servers?o=" . $org . "&userId=" . $userId . "&startDate=" . $start . "&endDate=" . $end;
            } else {
                $url = "https://console.automox.com/api/servers?o=" . $org . "&userId=" . $userId."&page=1";
            }
    
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $company_detail->api,
                ],
            ]);
            $user_activity = json_decode($response->getBody());
    
            $table_data = '';
            if ($user_activity) {
                foreach ($user_activity as $activity) {
                    list($date, $time) = explode('T', $activity->create_time)[0];
                    $data_flg = $activity->data ? 1 : 0;
                    $table_data .= '<tr><td>' . $activity->id . '</td><td>' . ucwords(str_replace('.', ' ', $activity->name)) . '</td><td>' . ($data_flg ? $activity->data->orgname : 'N/A') . '</td><td>' . ($data_flg ? $activity->data->firstname . ' ' . $activity->data->lastname : 'N/A') . '</td><td>' . ($data_flg ? $activity->data->email : 'N/A') . '</td><td>' . date('Y-m-d H:i:s', strtotime($activity->create_time)) . '</td></tr>';
                }
            }
    
            return response()->json(['table_data' => $table_data]);
        }
    
        // Add the following lines in your controller before returning the view
   // ...

if ($org) {
    $client = new Client();
    $page = 0; // Start with the first page
    $users = collect(); // Initialize a Laravel Collection

    while (true) {
        $url = "https://console.automox.com/api/servers?o=" . $org . "&page=" . $page;
        $response = $client->get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $company_detail->api,
            ],
        ]);

        $usersPage = json_decode($response->getBody());

        // Check if there are users on the current page
        if ($usersPage) {
            // Add users from the current page to the collection
            $users = $users->merge($usersPage);
            $page++; // Increment the page number for the next iteration
        } else {
            break; // No more users, exit the loop
        }
    }

    // Rest of your code...

    $url = "https://console.automox.com/api/servergroups/?o=" . $org;
    $response = $client->get($url, [
        'headers' => [
            'Authorization' => 'Bearer ' . $company_detail->api,
        ],
    ]);
    $groups = json_decode($response->getBody(), TRUE);

    foreach ($users as $key => $user) {
        $group_name = $groups[array_search($user->server_group_id, array_column($groups, 'id'))]['name'];
        $users[$key]->server_group_id = $group_name ? $group_name : 'Default';
    }
}



    
    return view('connections', compact('users', 'orgs', 'org', 'daterange', 'company_detail', 'groups'));
    
    }
    

    public function user_activity_report(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $users      = [];
        $orgs       = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
        
        if($request->has('daterange')){
            if($request->get('daterange')){
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }

        if($request->has('user_id')){
            $userId = $request->get('user_id');
            $client = new Client();
            if($daterange){
                $url = "https://console.automox.com/api/events?o=".$org."&userId=".$userId."&startDate=".$start."&endDate=".$end;
            }else{
                $url = "https://console.automox.com/api/events?o=".$org."&userId=".$userId;
            }
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $user_activity = json_decode($response->getBody());

            $table_data = '';
            if($user_activity){
                foreach($user_activity as $activity){
                    list($date, $time) = explode('T',$activity->create_time)[0];
                    $data_flg = 0;
                    if($activity->data){
                        $data_flg = 1;
                    }
                    $table_data .= '<tr><td>'.$activity->id.'</td><td>'.ucwords(str_replace('.', ' ', $activity->name)).'</td><td>'.($data_flg ? $activity->data->orgname : 'N/A').'</td><td>'.($data_flg?$activity->data->firstname.' '.$activity->data->lastname:'N/A').'</td><td>'.($data_flg?$activity->data->email:'N/A').'</td><td>'.date('Y-m-d H:i:s',strtotime($activity->create_time)).'</td></tr>';
                }
            }
            echo $table_data;
            exit;
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/users?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $users = json_decode($response->getBody());
        }
        return view('user-activity-report',compact('users', 'orgs', 'org', 'daterange', 'company_detail'));
    }

    public function device_report(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $users      = [];
        $orgs       = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($request->has('daterange')){
            if($request->get('daterange')){
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }

        if($request->has('user_id')){
            $userId = $request->get('user_id');
            $client = new Client();
            if($daterange){
                $url = "https://console.automox.com/api/events?o=".$org."&serverId=".$userId."&startDate=".$start."&endDate=".$end;
            }else{
                $url = "https://console.automox.com/api/events?o=".$org."&serverId=".$userId;
            }
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $user_activity = json_decode($response->getBody());
            $table_data = '';
            if($user_activity){
                foreach($user_activity as $activity){
                    list($date, $time) = explode('T',$activity->create_time)[0];
                    $table_data .= '<tr><td>'.$activity->id.'</td><td>'.ucwords(str_replace('.', ' ',
                            $activity->name)).'</td><td>'.$activity->policy_id.'</td><td>'.$activity->policy_name.'</td><td>'.$activity->policy_type_name.'</td><td>'.date('Y-m-d H:i:s',strtotime($activity->create_time)).'</td></tr>';
                }
            }
            echo $table_data;
            exit;
        }

        if($org) {
            // $users = \App\Models\Device::where('organisation_id', $org)->where('company_id', $company)->get();
            $client = new Client();
            $url = "https://console.automox.com/api/servers?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $users = json_decode($response->getBody());
            $client = new Client();
            $url = "https://console.automox.com/api/servergroups/?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $groups = json_decode($response->getBody(), TRUE);

            foreach ($users as $key => $user){
                $group_name = $groups[array_search($user->server_group_id, array_column($groups, 'id'))]['name'];
                $users[$key]->server_group_id = ($group_name?$group_name:'Default');
            }
        }
        return view('device-report',compact('users', 'orgs', 'org', 'daterange', 'company_detail'));
    }

    public function prepatch_report(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $group = 0;
        $groups = [];
        $company_detail = '';
        $reports = [];

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($request->has('group')){
            $group = $request->get('group');
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/servergroups?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $groups = json_decode($response->getBody());

            $client = new Client();
            $url = "https://console.automox.com/api/reports/prepatch?o=".$org.($group?"&groupId=".$group:"");
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $reports = json_decode($response->getBody());
        }

        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
            $data[] = ['Device ID','Device Name','Group','OS','Company Name','MSP','Software', 'Severity', 'Create Time','Patch Time'];
            if($reports) {
                if ($reports->prepatch->total) {
                    foreach ($reports->prepatch->devices as $report) {
                        foreach ($report->patches as $patch){
                            $data[] = [
                                $report->id,
                                $report->name,
                                $report->group,
                                $report->os_family,
                                $orgs->name,
                                $company_detail->name,
                                $patch->name,
                                ($patch->severity ? ucwords($patch->severity): 'Unknown'),
                                explode('T', $patch->createTime)[0],
                                explode('T', $patch->patchTime)[0]
                            ];
                        }
                        $data[] = ['','','','','','','', '', '',''];
                    }
                }
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.prepatch-report', compact('reports', 'orgs', 'org', 'group', 'groups', 'company_detail'))->setPaper('a4', 'landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }

        return view('prepatch-report',compact('reports', 'orgs', 'org', 'group', 'groups', 'company_detail'));
    }

    public function group_details(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $groups = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/servergroups?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $groups = json_decode($response->getBody());
        }

        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
            $data[] = ['Group ID','Group Name','Scan Interval','Device Count', 'Policies'];
            if($groups) {
                foreach ($groups as $group){
                    $data[] = [
                        $group->id,
                        ($group->name ? $group->name : 'Default'),
                        floor($group->refresh_interval / 60).' hours',
                        $group->server_count,
                        count($group->policies) ? count($group->policies) : '0'
                    ];
                }
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.group-details', compact('orgs', 'org', 'groups', 'company_detail'))->setPaper('a4', 'landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }

        return view('group-details',compact('orgs', 'org', 'groups', 'company_detail'));
    }

    public function manual_approvals(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $approvals = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/approvals?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $approvals = json_decode($response->getBody())->results;
        }

        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
            $data[] = ['Package ID','Package Name','OS','Status', 'Created', 'Policy'];
            if ($approvals) {
                foreach ($approvals as $approval){
                    if($approval->status) {
                        $data[] = [
                            $approval->id,
                            $approval->software->display_name,
                            $approval->software->os_family,
                            ucwords($approval->status),
                            $approval->manual_approval_time,
                            $approval->policy->name
                        ];
                    }
                }
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.manual-approval', compact('orgs', 'org', 'approvals', 'company_detail'))->setPaper
            ('a4', 'landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }

        return view('manual-approval',compact('orgs', 'org', 'approvals', 'company_detail'));
    }

    public function needs_attention_report(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $reports = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/reports/needs-attention?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $reports = json_decode($response->getBody())->nonCompliant;
        }

        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }
            $data[] = ['Device ID','Device Name','OS','Need Reboot', 'Connected', 'Group ID'];
            if(count($reports->devices)) {
                foreach ($reports->devices as $device){
                    $data[] = [
                        $device->id,
                        ($device->name ? $device->name : $device->customName),
                        $device->os_family,
                        ($device->needsReboot?'YES':'NO'),
                        ($device->connected?'YES':'NO'),
                        $device->groupId
                    ];
                }
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.need-attention-report', compact('orgs', 'org', 'reports', 'company_detail'))->setPaper('a4', 'landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }

        return view('need-attention-report',compact('orgs', 'org', 'reports', 'company_detail'));
    }

    public function policies(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $policies = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/policies?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $policies = json_decode($response->getBody());
        }

        // if($request->has('excel') || $request->has('csv')){
        //     $filename = $orgs->name.'.xlsx';
        //     if($request->has('csv')){
        //         $filename = $orgs->name.'.csv';
        //     }
        //     $data[] = ['Policy ID','Policy Name','Patch Type','Reboot user','Notification Max Delays','Reboot User Message Timeout','Pending Reboot Notification Delays','Status','Created At'];
        //     if($policies) {
        //         foreach ($policies as $policy) {
                        
        //                 $data[] = [
        //                     $policy->id,
        //                     $policy->name,
        //                     ucwords(str_replace('_', '', $policy->policy_type_name)),
        //                     $policy->configuration->notify_deferred_reboot_user,
        //         $policy->configuration->custom_notification_max_delays,
        //         $policy->configuration->notify_deferred_reboot_user_message_timeout,
        //         $policy->configuration->custom_pending_reboot_notification_max_delays,
        //                     $policy->status,
        //                     explode('T', $policy->create_time)[0]
        //                 ];
        //         }
        //     }
        //     $export = new ExcelExport($data);
        //     return \Excel::download($export, $filename);
        // }
if ($request->has('excel') || $request->has('csv')) {
    $filename = $orgs->name . '.xlsx';
    if ($request->has('csv')) {
        $filename = $orgs->name . '.csv';
    }
    $data[] = ['Policy ID', 'Policy Name', 'Patch Type', 'Reboot user', 'Notification Max Delays', 'Reboot User Message Timeout', 'Pending Reboot Notification Delays', 'Status', 'Created At'];
    if ($policies) {
        foreach ($policies as $policy) {
            $notifyDeferredRebootUser = isset($policy->configuration->notify_deferred_reboot_user) ? $policy->configuration->notify_deferred_reboot_user : False;
            $custom_notification_max_delays = isset($policy->configuration->custom_notification_max_delays) ? $policy->configuration->custom_notification_max_delays: 'N/A' ;
            $notify_deferred_reboot_user_message_timeout = isset($policy->configuration->notify_deferred_reboot_user_message_timeout) ? $policy->configuration->notify_deferred_reboot_user_message_timeout: 'N/A';
            $custom_pending_reboot_notification_max_delays = isset($policy->configuration->custom_pending_reboot_notification_max_delays) ? $policy->configuration->custom_pending_reboot_notification_max_delays: 'N/A';
            
            $data[] = [
                $policy->id,
                $policy->name,
                ucwords(str_replace('_', '', $policy->policy_type_name)),
                $notifyDeferredRebootUser,
                $custom_notification_max_delays,
                $notify_deferred_reboot_user_message_timeout,
                $custom_pending_reboot_notification_max_delays,
                // $policy->custom_notification_max_delays,
                // $policy->notify_deferred_reboot_user_message_timeout,
                // $policy->custom_pending_reboot_notification_max_delays,
                $policy->status,
                explode('T', $policy->create_time)[0]
            ];
        }
    }
    $export = new ExcelExport($data);
    return \Excel::download($export, $filename);
}

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.policies', compact('orgs', 'org', 'policies', 'company_detail'))->setPaper('a4','landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }

        return view('policies',compact('orgs', 'org', 'policies', 'company_detail'));
    }
    //Class for user_login_details
    public function user_login_details(Request $request)
 {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $users      = [];
        $orgs       = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
        
        if($request->has('daterange')){
            if($request->get('daterange')){
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }

        if($request->has('id')){
            $userId = $request->get('id');
            $client = new Client();
            if($daterange){
                $url = "https://console.automox.com/api/servers?o=".$org."&userId=".$userId."&startDate=".$start."&endDate=".$end;
            }else{
                $url = "https://console.automox.com/api/servers?o=".$org."&userId=".$userId;
            }
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $user_activity = json_decode($response->getBody());
            

            $table_data = '';
            if($user_activity){
                foreach($user_activity as $activity){
                    list($date, $time) = explode('T',$activity->create_time)[0];
                    $data_flg = 0;
                    if($activity->data){
                        $data_flg = 1;
                    }
                    $table_data .= '<tr><td>'.$activity->id.'</td><td>'.ucwords(str_replace('.', ' ', $activity->name)).'</td><td>'.($data_flg ? $activity->data->orgname : 'N/A').'</td><td>'.($data_flg?$activity->data->firstname.' '.$activity->data->lastname:'N/A').'</td><td>'.($data_flg?$activity->data->email:'N/A').'</td><td>'.date('Y-m-d H:i:s',strtotime($activity->create_time)).'</td></tr>';
                }
            }
            echo $table_data;
            exit;
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/servers?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $users = json_decode($response->getBody());
        }
        return view('user-login-details',compact('users', 'orgs', 'org', 'daterange', 'company_detail'));
    }
    
//Class for added devices
    public function added_devices(Request $request)
 {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $users      = [];
        $orgs       = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
        
        if($request->has('daterange')){
            if($request->get('daterange')){
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }

        if($request->has('id')){
            $userId = $request->get('id');
            $client = new Client();
            if($daterange){
                $url = "https://console.automox.com/api/servers?o=".$org."&userId=".$userId."&startDate=".$start."&endDate=".$end;
            }else{
                $url = "https://console.automox.com/api/servers?o=".$org."&userId=".$userId;
            }
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $user_activity = json_decode($response->getBody());
            

            $table_data = '';
            if($user_activity){
                foreach($user_activity as $activity){
                    list($date, $time) = explode('T',$activity->create_time)[0];
                    $data_flg = 0;
                    if($activity->data){
                        $data_flg = 1;
                    }
                    $table_data .= '<tr><td>'.$activity->id.'</td><td>'.ucwords(str_replace('.', ' ', $activity->name)).'</td><td>'.($data_flg ? $activity->data->orgname : 'N/A').'</td><td>'.($data_flg?$activity->data->firstname.' '.$activity->data->lastname:'N/A').'</td><td>'.($data_flg?$activity->data->email:'N/A').'</td><td>'.date('Y-m-d H:i:s',strtotime($activity->create_time)).'</td></tr>';
                }
            }
            echo $table_data;
            exit;
        }

        if($org) {
            $client = new Client();
            $page = 0; // Start with the first page
            $users = collect(); // Initialize a Laravel Collection
            
           while(true){
                $url = "https://console.automox.com/api/servers?o=".$org. "&page=" . $page;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $usersPage = json_decode($response->getBody());
             if ($usersPage) {
            $users = $users->merge($usersPage);
            $page++; // Increment the page number for the next iteration
            } else {
                break; // No more users, exit the loop
            }
           }
        }
        return view('added-devices',compact('users', 'orgs', 'org', 'daterange', 'company_detail'));
    }
    

//Class for user_policy_report
    public function user_policy_report(Request $request)
 {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org        = '';
        $daterange  = '';
        $userpolicies      = [];
        $orgs       = [];
        $users = [];
        $company_detail = '';

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }
        
        if($request->has('daterange')){
            if($request->get('daterange')){
                $daterange = $request->get('daterange');
                list($start, $end) = explode(' - ', $daterange);
                $start = date('Y-m-d', strtotime($start));
                $end = date('Y-m-d', strtotime($end));
            }
        }

        if($request->has('id')){
            $userId = $request->get('id');
            $client = new Client();
            if($daterange){
                $url = "https://console.automox.com/api/servers?o=".$org."&userId=".$userId."&startDate=".$start."&endDate=".$end;
            }else{
                $url = "https://console.automox.com/api/servers?o=".$org."&userId=".$userId;
            }
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $user_activity = json_decode($response->getBody());
            

            $table_data = '';
            if($user_activity){
                foreach($user_activity as $activity){
                    list($date, $time) = explode('T',$activity->create_time)[0];
                    $data_flg = 0;
                    if($activity->data){
                        $data_flg = 1;
                    }
                    $table_data .= '<tr><td>'.$activity->id.'</td><td>'.ucwords(str_replace('.', ' ', $activity->name)).'</td><td>'.($data_flg ? $activity->data->orgname : 'N/A').'</td><td>'.($data_flg?$activity->data->firstname.' '.$activity->data->lastname:'N/A').'</td><td>'.($data_flg?$activity->data->email:'N/A').'</td><td>'.date('Y-m-d H:i:s',strtotime($activity->create_time)).'</td></tr>';
                }
            }
            echo $table_data;
            exit;
        }
        
        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/servers?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $users = json_decode($response->getBody());
        }

       if ($org) {
    $client = new Client();
    $url1 = "https://console.automox.com/api/events?o=" . $org;
    $url2 = "https://console.automox.com/api/users?o=" . $org;

    // Make the first API request
    $response1 = $client->get($url1, [
        'headers' => [
            'Authorization' => 'Bearer ' . $company_detail->api,
        ]
    ]);
    $userpolicies = json_decode($response1->getBody());

    // Make the second API request
    $response2 = $client->get($url2, [
        'headers' => [
            'Authorization' => 'Bearer ' . $company_detail->api,
        ]
    ]);
    $users = json_decode($response2->getBody());
}

        return view('user-policy-report',compact('userpolicies', 'users','orgs', 'org', 'daterange', 'company_detail'));
    }

      
    public function device_information(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $devices = [];
        $company_detail = '';

        if($request->has('text')){
            $text = $request->get('text');
            $old_values = \Session::get('device_information');
            $new_value = [$text => $request->get('status')];
            \Session::put('device_information' , array_merge($old_values,$new_value));
            \Session::save();
            echo 1;
            exit;
        }

        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            $org = $request->get('organisation');
            $client = new Client();
            $url = "https://console.automox.com/api/orgs";
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $orgs = json_decode($response->getBody());
            $key = array_search($org, array_column($orgs, 'id'));
            $orgs = $orgs[$key];
        }

        if($org) {
            $client = new Client();
            $url = "https://console.automox.com/api/servers?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $devices = json_decode($response->getBody());
          
            $client = new Client();
            $url = "https://console.automox.com/api/servergroups/?o=".$org;
            $response = $client->get($url, [
                'headers' => [
                    'Authorization' => 'Bearer '.$company_detail->api,
                ]
            ]);
            $groups = json_decode($response->getBody(), TRUE);

            foreach ($devices as $key => $device){
                $group_name = $groups[array_search($device->server_group_id, array_column($groups, 'id'))]['name'];
                $devices[$key]->server_group_id = ($group_name?$group_name:'Default');
            }
        }else{
            \Session::put('device_information', ['Device ID' => 1, 'Device Name' => 1, 'OS' => 1, 'Need Attention' =>
                1, 'Disconnect Time' => 1, 'Group Id' => 1, 'Tags' => 0, 'IP Address' => 0, 'OS Version' => 0, 'Scheduled Patches' => 0, 'Status' => 0, 'Agent Version' => 0, 'Disconnected For' => 0, 'Last Logged In User' => 0, 'Active Directory OU' => 0, 'Total Patched' => 0, 'Created' => 0, 'RAM' => 0, 'CPU' => 0, 'DISK SPACE' => 0, 'AVAILABLE SPACE' => 0]);
        }

        if($request->has('excel') || $request->has('csv')){
            $filename = $orgs->name.'.xlsx';
            if($request->has('csv')){
                $filename = $orgs->name.'.csv';
            }

            $data[] = ['Device ID','Device Name','OS','Need Attention','Disconnect Time','Group','Tags','IP Address','OS Version','Scheduled Patches','Status','Agent Version','Disconnected For','Last Logged In User','Active Directory OU','Total Patched','Created', 'RAM', 'CPU', 'DISK SPACE', 'AVAILABLE SPACE'];
            if($devices) {
                $ram='';
                $cpu='';
                $disk='';
                $fr_disk='';
                foreach ($devices as $device) {
                  
                    $detail = (array) $device->detail;
                     if(count($detail))
                     {
                        if(isset($detail['RAM'])){
                       $ram = formatBytes($detail['RAM']);
                     }
                     else{
                            $ram = '-';
                     }
                    }
                    if (count($detail)) {
                        if (isset($detail['CPU'])){
                            $cpu = $detail['CPU'];
                    } else {
                        $cpu = '-';
                    }
                }
                    if (count($detail)) {
                        if (isset($detail['DISKS'][0])){
                            $disk = formatBytes($detail['DISKS'][0]->SIZE);
                    } else {
                        $disk = '-';
                    }
                }
                    if(isset($detail['VOLUME']))
                    {
                         if(count($detail['VOLUME']))
                         {
                           $fr_disk =  formatBytes($detail['VOLUME'][0]->FREE);
                        }
                        else{
                            $fr_disk='-';
                        }
                    }
                                           
                                                             
                                        
                    $last_disconnect_time = new \DateTime($device->last_disconnect_time, new \DateTimeZone("UTC"));
                    $last_disconnect_time = $last_disconnect_time->format("d M Y H:i:s O");
                    $disconnected_for = new \DateTime("now", new \DateTimeZone('UTC'));
                    $last_disconnect = new \DateTime($device->last_disconnect_time, new \DateTimeZone("UTC"));
                    $disconnected_for = ($last_disconnect->diff($disconnected_for)->days);
                    $createtime = new \DateTime($device->create_time, new \DateTimeZone("UTC"));
                    $data[] = [
                        $device->id,
                        ($device->display_name?$device->display_name:$device->custom_name),
                        ($device->os_family.' '.$device->os_name),
                        ($device->needs_reboot?"Needs Reboot":''),
                        $last_disconnect_time,
                        $device->server_group_id,
                        ($device->tags?implode(', ', $device->tags):''),
                        ($device->ip_addrs?$device->ip_addrs[0]:''),
                        $device->os_version,
                        ($device->pending_patches?$device->pending_patches:'0'),
                        ucwords($device->status->device_status),
                        $device->agent_version,
                        $disconnected_for,
                        $device->last_logged_in_user,
                        '-',
                        ($device->patches?$device->patches:'0'),
                         $createtime->format("d M Y H:i:s"),
                         $ram,
                         $cpu,
                         $disk,
                         $fr_disk,
                        // ucwords($device->status->agent_status),
                        // ucwords($device->status->policy_status),
                        // $device->agent_version,
                        // $disconnected_for,
                        // $device->last_logged_in_user,
                        // '-',
                        // ($device->patches?$device->patches:'0'),
                        // $createtime->format("d M Y H:i:s"),
                        // !empty($detail)?$detail['RAM']:'-',
                    ];
                }
            }
            $export = new ExcelExport($data);
            return \Excel::download($export, $filename);
        }

        if($request->has('pdf')){
            $pdf = Pdf::loadView('pdf.device-information', compact('orgs', 'org', 'devices', 'company_detail'))->setPaper('a4','landscape');
            return $pdf->stream($orgs->name.'.pdf');
        }

        return view('device-information',compact('orgs', 'org', 'devices', 'company_detail'));
    }

    public function data_extract(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $org = '';
        $orgs = '';
        $company_detail = '';
        $data = [];
        $type = '';
        $status = [];
        if($request->has('company')){
            $company = $request->get('company');
            $company_detail = \App\Models\Company::where('id',$company)->first();
            if(!$company_detail){
                dd('Invalid Company details');
            }
        }

        if($request->has('organisation')){
            try {
                $org = $request->get('organisation');
                $client = new Client();
                $url = "https://console.automox.com/api/orgs";
                $response = $client->get($url, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $company_detail->api,
                    ]
                ]);
                $orgs = json_decode($response->getBody());
                $key = array_search($org, array_column($orgs, 'id'));
                $orgs = $orgs[$key];
            }catch (\Exception $e){
                return view('error-page', ['code' => $e->getCode(), 'message' => getErrorMessage($e->getCode())]);
            }
        }

        if($org) {
            try {
                $client = new Client();
                $url = "https://console.automox.com/api/data-extracts?o=".$org."&sort=created_at:asc";
                if($request->has('type')){
                    if($request->get('type')){
                        $type = $request->get('type');
                        $url .= "&type:equals=".$request->get('type');
                    }
                }

                if($request->has('status')){
                    if($request->get('status')){
                        $status = $request->get('status');
                        $url .= "&status:in=".implode(',', $request->get('status'));
                    }
                }

                $response = $client->get($url, [
                    'headers' => [
                        'Authorization' => 'Bearer '.$company_detail->api,
                    ]
                ]);
                $data = json_decode($response->getBody());
                if($data->size){
                    $data = $data->results;
                    foreach ($data as $key => $d){
                        $client = new Client();
                        $url = "https://console.automox.com/api/users/".$d->user_id;
                        $response = $client->get($url, [
                            'headers' => [
                                'Authorization' => 'Bearer '.$company_detail->api,
                            ]
                        ]);
                        $user_data = json_decode($response->getBody());
                        $data[$key]->username = $user_data->firstname.' '.$user_data->lastname;
                    }
                }else{
                    $data = [];
                }
            }catch (\Exception $e){
                return view('error-page', ['code' => $e->getCode(), 'message' => getErrorMessage($e->getCode())]);
            }
        }
        return view('data-extract', compact( 'orgs', 'org',  'company_detail', 'data', 'type', 'status'));
    }

    public function manage_company(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        if($request->has('type')){
            $type = $request->get('type');
            if($type == 'insert'){
                $company = new \App\Models\Company;
                $company->name = $request->get('name');
                $company->api = $request->get('api');
                $company->organisation_id = $request->get('org_id');
                $company->user_id = $request->get('user_id');
                $company->save();
                company_update($company);
                organisation_insert();
                get_devices($company->id);
                return redirect()->back();
            }
            if($type == 'update'){
                $company = \App\Models\Company::find($request->get('company_id'));
                $company->name = $request->get('company_name');
                $company->save();
                return 1;
            }
            if($type == 'delete'){
                $company = \App\Models\Company::find($request->get('id'));
                $company->delete();
                \App\Models\Organisation::where('company_id', $request->get('id'))->delete();
                \App\Models\Device::where('company_id', $request->get('id'))->delete();
                return redirect()->back();
            }
            if($type == 'user_list'){
                try {
                    $client = new Client();
                    $url = "https://console.automox.com/api/users?o=" . $request->get('org_id');
                    $response = $client->get($url, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $request->get('api'),
                        ]
                    ]);
                    $users = json_decode($response->getBody());
                    $table_data = '';
                    if($users){
                        foreach($users as $user){
                            $table_data .= '<tr><td>'.$user->id.'</td><td>'.$user->firstname.' '.$user->lastname.'</td><td>'.$user->email
                                .'</td></tr>';
                        }
                    }
                    return $table_data;
                }catch (\Exception $e){
                    return response()->json(['error' => $e->getMessage()]);
                }
            }
        }
        return view('manage-company');
    }

    public function manage_user_edit(Request $request, $user_id)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        $user = \App\Models\User::find($user_id);
        return view('manage-user-edit', compact('user'));
    }

    public function manage_user(Request $request)
    {
        if(!checkPermission()){
            return redirect()->route('errorview');
        }
        if($request->has('method_type')) {
            $type = $request->get('method_type');
            if ($type == 'insert') {
                //\App\Models\User::insert($request->insert);
                $insert = $request->get('insert');
                $insert['password'] = \Hash::make($insert['password']);
                $insert['permission'] = json_encode($insert['permission']);
                $insert['organisation'] = json_encode($insert['organisation']);
                \App\Models\User::insert($insert);
                return redirect()->back();
            }
            if($type == 'delete'){
                $company = \App\Models\User::find($request->get('id'));
                $company->delete();
                return redirect()->back();
            }

            if($type == 'update'){
                $insert = $request->get('insert');
                \App\Models\User::where('id', $request->input('user_id'))->update($insert);
                return redirect()->route('manageuser');
            }
        }
        return view('manage-user');
    }

    public function profile(Request $request)
    {
        if($request->has('method_type')) {
            $type = $request->get('method_type');
            if ($type == 'update') {
                $insert = $request->get('insert');
                if(!$insert['password']){
                    unset($insert['password']);
                }else{
                    $insert['password'] = \Hash::make($insert['password']);
                }
                \App\Models\User::where('id', $request->get('id'))->update($insert);
                return redirect()->back();
            }
        }
        return view('profile');
    }

    public function organisation_in_company()
    {
        organisation_insert();
    }

    public function devices_in_organisation()
    {
        get_devices();
    }

    public function cron_job_company()
    {
        company_update();
    }
}

class ExcelExport implements FromArray, ShouldAutoSize
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }
}