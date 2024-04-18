@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <style type="text/css">
        .table-critical{
            background-color : rgba(246, 81, 163, .5);
        }
        .dropdown-menu li{
            padding: 3px 10px
        }
        .badge{
            font-size : 10px
        }
    </style>
@endsection



@section('content')
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">Connections</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('connections')  }}" method="get">
                    <input type="hidden" name="company" id="company" value="{{ ($company_detail?$company_detail->id:'')
                    }}">
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $hasOrganisation = json_decode(auth()->user()->organisation, TRUE);
                            ?>
                            <select class="form-control default-select2" name="organisation" required  onchange="$('#company').val($('option:selected',this).data('company'))" >
                                <option value="" {{ $org == '' ? 'selected': ''  }}>Select Organisation...</option>
                                <?php
                                $organisationHTML = '';
                                foreach (\App\Models\Company::all() as $company){
                                    if(auth()->user()->type == 'admin'){
                                        $option = '';
                                        $checkFlag = 0;
                                        foreach($company->organisation as $orgdata){
                                            $checkFlag = 1;
                                            $option .= '<option data-company="'.$company->id.'" value="'.$orgdata->org_id.'" '.($org == $orgdata->org_id?'selected':'').'>'.$orgdata->name.'</option>';
                                        }
                                        if($checkFlag){
                                            $organisationHTML .= '<optgroup label="'.$company->name.'">'.$option.'</optgroup>';
                                        }
                                    }else{
                                        $option = '';
                                        $checkFlag = 0;
                                        foreach($company->organisation as $orgdata){
                                            if(array_key_exists($orgdata->id, $hasOrganisation)){
                                                $checkFlag = 1;
                                                $option .= '<option data-company="'.$company->id.'" value="'.$orgdata->org_id.'" '.($org == $orgdata->org_id?'selected':'').'>'.$orgdata->name.'</option>';
                                            }
                                        }
                                        if($checkFlag){
//                                            $organisationHTML .= '<optgroup label="'.$company->name.'">'.$option.'</optgroup>';
                                            $organisationHTML .= $option;
                                        }
                                    }
                                }
                                echo $organisationHTML;
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group" id="default-daterange">
                                <input type="text" value="{{ $daterange }}" class="form-control" name="daterange">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <div class="col-md-1">
                            <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickableInside" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        COLUMNS <i class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="0" class="toggle_column" checked /> Device ID
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="1" class="toggle_column" checked /> Name
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="2" class="toggle_column"  /> Organisation Id
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="3" class="toggle_column"  checked /> Server Group
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="4" class="toggle_column"  checked /> IP Address
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="5" class="toggle_column" checked /> Agent Version
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="6" class="toggle_column" /> Device Name
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="7" class="toggle_column" /> OS Family
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="8" class="toggle_column" /> OS Name
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="9" class="toggle_column" /> Pending Patches
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="10" class="toggle_column" /> Device Status
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="11" class="toggle_column" /> Agent Status
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="12" class="toggle_column" /> WMI INTEGRITY CHECK
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="13" class="toggle_column" /> Tags
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="14" class="toggle_column" /> Total Count
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="15" class="toggle_column" /> Patches
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="16" class="toggle_column" /> Pending Patches
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="17" class="toggle_column" /> Create Time
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="18" class="toggle_column" /> Last Disconnect Time
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="19" class="toggle_column" /> Last Refresh Time
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="20" class="toggle_column" /> Last Update Time
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="21" class="toggle_column" /> OS version
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="22" class="toggle_column" /> Last Scan Failed
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="23" class="toggle_column" /> Needs Reboot
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="24" class="toggle_column" /> Volume
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="25" class="toggle_column" /> AUTO UPDATE OPTIONS
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="26" class="toggle_column" /> WSUS Config
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="27" class="toggle_column" /> Device Server
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                        </div>
                        <div>
                            
                        </div>
                        
                    </div>
                </form>
            </div>
            <div style="margin-top : 1rem">
                <table id="data-table-default" class="table table-striped table-bordered" style="text-align: start" >
                    <thead>
                    <tr>
                        <th data-orderable="false">Device ID</th>
                        <th data-orderable="false">Name <button class="copy-btn" onclick="copyColumnData()">Copy</button></th>
                        <th data-orderable="false">Organisation Id</th>
                        <th data-orderable="false">Server Group</th>
                        <th data-orderable="false">IP Address</th>
                        <th data-orderable="false">Agent Version</th>
                        <th data-orderable="false">Device Name</th>
                        <th data-orderable="false">OS Family</th>
                        <th data-orderable="false">OS Name</th>
                        <th data-orderable="false">Pending Patches</th>
                        <th data-orderable="false">Device Status</th>
                        <th data-orderable="false">Agent Status</th>
                        <th data-orderable="false">WMI INTEGRITY CHECK</th>
                        <th data-orderable="false">Tags</th>
                        <th data-orderable="false">Total Count</th>
                        <th data-orderable="false">Patches</th>
                        <th data-orderable="false">Pending Patches</th>
                        <th data-orderable="false">Create Time</th>
                        <th data-orderable="false">Last Disconnect Time</th>
                        <th data-orderable="false">Last Refresh Time</th>
                        <th data-orderable="false">Last Update Time</th>
                        <th data-orderable="false">OS version</th>
                        <th data-orderable="false">last Scan Failed</th>
                        <th data-orderable="false">Needs Reboot</th>
                        <th data-orderable="false">Volume</th>
                        <th data-orderable="false">AUTO UPDATE OPTIONS</th>
                        <th data-orderable="false">WSUS Config</th>
                        <th data-orderable="false">Device Server</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($users)
                        @foreach($users as $user)
                        
                            <tr>
                                <td><a href="javascript:;" onclick="getUserActivity({{ $user->id }})" >{{ $user->id }}</a></td>
                                <td>{{ ($user->name?$user->name:$user->custom_name) }}</td>
                                <td>{{ ($user->organization_id?$user->organization_id:$user->custom_name) }}</td>
                                <td>
                                    @if($groups)
                                        @php
                                           echo $userServerGroupId = $user->server_group_id;
                                        @endphp
                                
                                        @foreach($groups as $group)
                                            @if($userServerGroupId === $group['id'])
                                                {{ $group['name'] ?: 'Default' }}<br>
                                            @endif
                                        @endforeach
                                    @endif
                                </td>

                                <td>@foreach($user->ip_addrs as $key => $value){{ $value }}@endforeach</td>
                                <td>{{ ($user->agent_version?$user->agent_version:$user->custom_name) }}</td>
                                <td>{{ ($user->display_name ? $user->display_name : $user->custom_name) }}</td>
                                <td>{{ ($user->os_family?$user->os_family:$user->custom_name) }}</td>
                                <td>{{ ($user->os_name?$user->os_name:$user->custom_name) }}</td>
                                <td>{{ ($user->pending_patches?$user->pending_patches:"NA") }}</td>
                                <td>{{ ($user->status->device_status?$user->status->device_status:"NA") }}</td>
                                <td>{{ ($user->status->agent_status?$user->status->agent_status:"NA") }}</td>
                                <td> NA</td>
                               
                                <td>@foreach($user->tags as $key => $value)
                                        {{ $value }}
                                    @endforeach
                                </td>
                                <td>{{ ($user->total_count?$user->total_count:"NA") }}</td>
                                <td>{{ ($user->patches?$user->patches:"NA") }}</td>
                                <td>{{ ($user->pending_patches?$user->pending_patches:"NA") }}</td>
                                <td>
                                    @php
                                        $dt = new DateTime($user->create_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
                                 <td>
                                    @php
                                        $dt = new DateTime($user->last_refresh_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
                                 <td>
                                    @php
                                        $dt = new DateTime($user->last_refresh_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
                                 <td>
                                    @php
                                        $dt = new DateTime($user->last_update_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
                                 <td>{{ ($user->os_version?$user->os_version:$user->custom_name) }}</td>
                                 <td>
                                    {{ ($user->last_scan_failed?"True":"False") }}
                                </td>
                                 <td class="text-center">
                                    @if($user->needs_reboot)
                                        TRUE
                                    @else
                                        FALSE
                                    @endif
                                </td>
                                <td>
                                    <p class="p-0 m-0">
                                        @if(isset($user->detail->VOLUME) && is_iterable($user->detail->VOLUME))
                                            @foreach($user->detail->VOLUME as $volume)
                                                Volume: {{ $volume->VOLUME ?? "NA" }}<br>
                                                Free: {{ $volume->FREE ?? "NA" }}<br>
                                                Is System Disk: {{ $volume->IS_SYSTEM_DISK ?? "NA" }}<br>
                                                File System Type: {{ $volume->FSTYPE ?? "NA" }}<br>
                                                Label: {{ $volume->LABEL ?? "NA" }}<br>
                                                Available: {{ $volume->AVAIL ?? "NA" }}<br><br>
                                            @endforeach
                                        @else
                                            Volume information not available.
                                        @endif
                                    </p>
                                </td>

                                <td>
                                    <p class="p-0 m-0">
                                            Options: {{ isset($user->detail->AUTO_UPDATE_OPTIONS->OPTIONS) ? $user->detail->AUTO_UPDATE_OPTIONS->OPTIONS : 'N/A' }}<br>
                                            Enabled: {{ isset($user->detail->AUTO_UPDATE_OPTIONS->ENABLED) ? $user->detail->AUTO_UPDATE_OPTIONS->ENABLED : 'N/A' }}<br>
                                        </p>
                                </td>
                                <td>
                                        <p class="p-0 m-0">
                                            WSUS Reachable: {{ $user->detail->WSUS_CONFIG->WSUS_REACHABLE ?? "NA" }}<br>
                                            WSUS Managed: 
                                            @if($user->detail->WSUS_CONFIG->WSUS_MANAGED ?? "NA")
                                                WSUS
                                            @else
                                                Windows Update Server
                                            @endif<br>
                                            WSUS Server: {{ $user->detail->WSUS_CONFIG->WSUS_SERVER ?? "NA" }}<br>
                                        </p>
                                    </td>
                                     <td>
                                        <p class="p-0 m-0">
                                           @if($user->detail->WSUS_CONFIG->WSUS_MANAGED ?? "NA")
                                                WSUS Server
                                            @else
                                                Windows Update Servers
                                            @endif
                                        </p>
                                    </td>


                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
@endsection
<script>
    function copyColumnData() {
        var cells = document.querySelectorAll('#data-table-default tbody td:nth-child(2)');
        var columnData = '';
        cells.forEach(function(cell) {
            columnData += cell.innerText + '\n';
        });
        var tempTextarea = document.createElement('textarea');
        tempTextarea.value = columnData;
        document.body.appendChild(tempTextarea);
        tempTextarea.select();
        tempTextarea.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(tempTextarea);
        alert('Column data copied to clipboard');
    }
</script>
@section('javascript')
<script src="{{ asset('assets/plugins/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables.net-responsive-bs5/2.5.0/responsive.bootstrap5.min.js" integrity="sha512-ttUzgYsudyu2EEX88COgcLtS+AKeZXAzI45jN2iJUIpbtkiH8lgaWtlmLU6BjECAZAyhhn0UdedzR++CZGZiZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
     <script type="text/javascript">
        $(".default-select2").select2();
        var table = $('#data-table-default').DataTable({
            responsive: true,
             dom: '<"row"<"col-sm-5"B><"col-sm-7"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
             language: {
                    search: "Search in Connection table:"
                },
            "autoWidth": false,
            
            "columnDefs": [
                {
                    "targets": [2,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27],
                    "visible": false,
                }
            ]
        });
       
        $('.toggle_column').each(function () {
            var column_index = $(this).data('column');
            if($(this).is(':checked')){
                var column = table.column(column_index);
                column.visible(true);
            }else{
                var column = table.column(column_index);
                column.visible(false);
            }
        });

        $('.toggle_column').click(function (e) {
            var label_text = $(this).parent('label').text().trim();
            var column_index = $(this).data('column');
            if($(this).is(':checked')){
                $.get('{{ route('deviceinformation') }}?text='+label_text+'&status=1', function (resp) {
                    console.log('Value Chaged');
                });
                var column = table.column(column_index);
                column.visible(true);
            }else{
                $.get('{{ route('deviceinformation') }}?text='+label_text+'&status=0', function (resp) {
                    console.log('Value Chaged');
                });
                var column = table.column(column_index);
                column.visible(false);
            }
        });
    </script>
@endsection