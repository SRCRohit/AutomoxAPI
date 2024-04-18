<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\daily_activity_report;
use GuzzleHttp\Client;
use PDF;
use App\Helpers;
use DateTime;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class Tags extends Controller
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
                
                //api created here : $company->api
                foreach ($companyapis as $companyapi){
                 //Org id found here : $companyapi ->id
                $page = 0;
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
                $deviceCount = 0;
                    foreach ($users as $userss) {
                            if ($companyapi ->id == "102119" &&  $userss->status->device_status == "ready") {
                                foreach ($userss->tags as $tags) {
                                    $deviceCount++;
                                    // echo "Company ".$company->name." " .$tags." " ." with device name : ".$userss->display_name."<br>";
                                    echo "<table border='1'>
                                                <tbody>
                                                <tr>
                                                    <td>".$companyapi ->id."<td>
                                                    <td>".$company->name."<td>
                                                    <td>".$tags."<td>
                                                    <td>".$company->$userss->display_name."<td>
                                                </tr>
                                                </tbody>
                                            </table>";
                                }
                            }
                        
                    }
                
            
            // $i = 0;
            
            // // Start the table
            // echo '<table border="1">
            //         <thead>
            //             <tr>
            //                 <th>ID</th>
            //                 <th>Company Name</th>
            //                 <th>Tag</th>
            //                 <th>Device Name</th>
            //             </tr>
            //         </thead>
            //         <tbody>';
            
            // foreach ($users as $userss) {
            //     if ($companyapi ->id == "102119"&& $userss->status->device_status == "ready") {
            //         foreach ($userss->tags as $tags) {
                 
            //             $i++;
            //             // Output each row of the table
            //             echo '<tr>
            //                     <td>' . $i . '</td>
            //                     <td>' . $company->name . '</td>
            //                     <td>' . $tags . '</td>
            //                     <td>' . $userss->display_name . '</td>
            //                   </tr>';
            //         }
            //     }
            // }
            
            // // Close the table
            // echo '</tbody></table>';
            
            // echo "<br>Total devices with status 'ready': " . $i;
                  
                                
                    
                    // $utcDateTime = new \DateTime('now', new \DateTimeZone('UTC'));
                    //  $datetime = $utcDateTime->setTimezone(new \DateTimeZone('Asia/Kolkata'));
            
                    // $existingRecord = daily_activity_report::where('company_id', $companyapi ->id)
                    //     ->whereDate('createdAt', date('Y-m-d'))
                    //     ->first();
            
                    // if ($existingRecord) {
                    //     // Update the existing record
                    //     $existingRecord->update([
                    //         'company_name' => $company->name,
                    //         'msp' => $companyapi ->name,
                    //         'license_used' => $counts,
                    //         'need_attention' => $needattention,
                    //         'need_reboot' => $NeedsReboot,
                    //         'not_compatible' => $notcompatible,
                    //         'disconnected_devices' => $countDisconnected,
                    //         'createdAt' => $datetime,
                    //         'updatedtime' => $datetime,
                    //     ]);
                    // } else {
                    //     // Insert a new record
                    //     daily_activity_report::create([
                    //         'company_id' => $companyapi ->id,
                    //         'company_name' => $company->name,
                    //         'msp' => $companyapi ->name,
                    //         'license_used' => $counts,
                    //       'need_attention' => $needattention,
                    //         'need_reboot' => $NeedsReboot,
                    //         'not_compatible' => $notcompatible,
                    //         'disconnected_devices' => $countDisconnected,
                    //         'createdAt' => $datetime,
                    //         'updatedtime' => $datetime,
                    //     ]);
                    // }
                    // echo "Update " .$companyapi ->name." -> ".$companyapi ->id."<br>";
                    // echo"updated";
                        
                    
                }
                
            }
        }

    }
?>