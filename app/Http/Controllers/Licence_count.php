<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\UserCount;
use GuzzleHttp\Client;
use PDF;
use DateTime;
use App\Helpers;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class Licence_count extends Controller
    {
     
        public function index()
        {
            // Fetch all company details
            $companies = \App\Models\Company::all();
         
            foreach ($companies as $company) {
                $client = new Client();
                $url = "https://console.automox.com/api/orgs";
                $response = $client->get($url, [
                    'headers' => [
                            'Authorization' => 'Bearer '.$company->api,
                        ]
                    ]);
                $companyapis = json_decode($response->getBody());
                
                foreach ($companyapis as $companyapi){
                  // User Count Process
                $page = 0; // Start with the first page
                $users = collect();
                $client = new Client();
        
                while (true) {
                    $url = "https://console.automox.com/api/servers?o=".$companyapi ->id."&page=".$page;
        
                    $response = $client->get($url, [
                        'headers' => [
                            'Authorization' => 'Bearer '.$company->api,
                        ]
                    ]);
        
                    $users1 = json_decode($response->getBody());
        
                    if ($users1) {
                        $users = $users->merge($users1);
                        $page++;
                    } else {
                        break;
                    }
                }
            
                    // User Count Process
                    $counts = count($users);
                    
                    $NeedsReboot = 0;
                    $needattention = 0;
                    $notcompatible = 0;
                    $countDisconnected = 0;
                    
                    foreach ($users as $userss) {
                            if ($userss->needs_attention) {
                                $needattention++;
                            }
                        
                    }

                    foreach ($users as $userss) {
                            if ($userss->needs_reboot) {
                                $NeedsReboot++;
                            }
                        
                    }
                    foreach ($users as $userss) {
                            if (!$userss->is_compatible) {
                                $notcompatible++;
                            }
                        
                    }
                    
                    foreach ($users as $user) {
                        $lastDisconnectTime = new DateTime($user->last_disconnect_time);
                        $currentDateTime = new DateTime();
                        $daysDifference = $lastDisconnectTime->diff($currentDateTime)->days;
                        if ($daysDifference >= 30) {
                            $countDisconnected++;
                             $user->last_disconnect_time . PHP_EOL;
                        }
                    }
                    
                    
                    
                    $datetime = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));
                    
                    echo $datetime->format('Y-m-d')." | ".$datetime->format('H:i:s');


                    
                      $existingRecord = UserCount::where('company_id', $companyapi->id)
                            ->where('createdAt', $datetime->format('Y-m-d'))
                            ->first();
    
    
    
                
                        if ($existingRecord) {
                            // Update the existing record
                            $existingRecord->update([
                                'user_number' => $counts,
                                'need_attention' => $needattention,
                                'need_reboot' => $NeedsReboot,
                                'not_compatible' => $notcompatible,
                                'disconnected_devices' => $countDisconnected,
                                'createdAt' => $datetime->format('Y-m-d'),
                                'updatedtime' => $datetime->format('H:i:s')
                            ]);
                             echo "Updated " .$companyapi ->name." -> ".$companyapi ->id."<br>";
                        } else {
                            // Insert a new record
                            UserCount::create([
                                'company_name' => $company->name,
                                'company_id' => $companyapi ->id,
                                'license_issue_date'=>$companyapi ->create_time,
                                'msp' => $companyapi ->name,
                                'user_number' => $counts,
                                'createdAt' => $datetime->format('Y-m-d'),
                                'updatedtime' => $datetime->format('H:i:s'),
                                'need_attention' => $needattention,
                                'need_reboot' => $NeedsReboot,
                                'not_compatible' => $notcompatible,
                                'disconnected_devices' => $countDisconnected
                            ]);
                             echo "Inserted" .$companyapi ->name." -> ".$companyapi ->id."<br>";
                        }
                        
                    
                }
                
            }
        }

    }
?>