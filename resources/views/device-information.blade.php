@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
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

<!--@section('content')-->
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">DEVICE INFORMATION</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('deviceinformation')  }}" method="get">
                    <input type="hidden" name="company" id="company" value="{{ ($company_detail?$company_detail->id:'')}}">
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
                        <div class="col-md-9 d-flex justify-content-between">
                            <div>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickableInside" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        COLUMNS <i class="fa fa-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="0" class="toggle_colmn" checked />Device ID
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="1" class="toggle_colmn" checked /> Device Name
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="2" class="toggle_colmn" checked /> Host OS
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="3" class="toggle_colmn" /> Need Attention
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="4" class="toggle_colmn" checked />Create Time
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="5" class="toggle_colmn" checked /> Group
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="6" class="toggle_colmn" checked/> Tags
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="7" class="toggle_colmn" /> IP Address
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="8" class="toggle_colmn" /> OS Version
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="9" class="toggle_colmn" />Scheduled Patches
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="10" class="toggle_colmn" /> Status
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="11" class="toggle_colmn" /> Agent Version
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="12" class="toggle_colmn" />Disconnected For
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="13" class="toggle_colmn" /> Last Logged In User
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="14" class="toggle_colmn" />Active Directory OU
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="15" class="toggle_colmn" /> Total Patched
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="16" class="toggle_colmn" /> Created
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="17"
                                                       class="toggle_colmn" /> RAM
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="18"
                                                       class="toggle_colmn" /> CPU
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="19"
                                                       class="toggle_colmn" /> DISK SPACE
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="20"
                                                       class="toggle_colmn" /> AVAILABLE SPACE
                                            </label>
                                        </li>
                                        <li>
                                            <label>
                                                <input type="checkbox" autocomplete="off" data-column="21"
                                                       class="toggle_colmn" checked /> Serial Number
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <button onclick="showLoader()" type="submit" class="btn btn-primary">Submit</button>
                                @if($devices)
                                    &nbsp;
                                    <a target="_blank" href="{{ url()->full() }}&excel=1" class="btn btn-dark"><i class="fa
                            fa-file-excel me-1"></i> Download Excel</a>
                                    &nbsp;
                                    <a target="_blank" href="{{ url()->full() }}&csv=1" class="btn btn-dark"><i class="fa
                            fa-file-csv me-1"></i> Download CSV</a>
                                    &nbsp;
                                    <a target="_blank" href="{{ url()->full() }}&pdf=1" class="btn btn-dark"><i class="fa
                            fa-file-pdf
                            me-1"></i>
                                        Download PDF</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div style="margin-top : 1rem">
                <table id="data-table-default" class="table table-striped table-bordered align-middle">
                    <thead>
                    <tr>
                        <th data-orderable="false">Device ID</th>
                        <th data-orderable="false">Host Name</th>
                        <th data-orderable="false">Host OS</th>
                        <th data-orderable="false" class="text-center">Need Attention</th>
                        <th data-orderable="false">Create Time</th>
                        <th data-orderable="false" class="text-center">Group</th>
                        <th data-orderable="false">Tags</th>
                        <th data-orderable="false">IP Address</th>
                        <th data-orderable="false">OS Version</th>
                        <th data-orderable="false">Scheduled Patches</th>
                        <th data-orderable="false">Status</th>
                        <th data-orderable="false">Agent Version</th>
                        <th data-orderable="false">Disconnected For</th>
                        <th data-orderable="false">Last Logged In User</th>
                        <th data-orderable="false">Active Directory OU</th>
                        <th data-orderable="false">Total Patched</th>
                        <th data-orderable="false">Created</th>
                        <th data-orderable="false">RAM</th>
                        <th data-orderable="false">CPU</th>
                        <th data-orderable="false">Disk Space</th>
                        <th data-orderable="false">Available Space</th>
                        <th data-orderable="false">Serial Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($devices)
                        @foreach($devices as $device)
                            <?php
                                $detail = (array) $device->detail;
                            ?>
                            <tr>
                                <td>{{ $device->id }}</td>
                                <td>{{ ($device->display_name?$device->display_name:$device->custom_name) }}</td>
                                <td>{{ $device->os_family }} {{ $device->os_name }}</td>
                                <td class="text-center">
                                    @if($device->needs_reboot)
                                        <span class="badge rounded-pill bg-danger">
                                            Needs Reboot
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dt = new DateTime($device->create_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s O");
                                    @endphp
                                </td>
                                <td class="text-center">{{ $device->server_group_id }}</td>
                                <td>
                                    @if($device->tags)
                                        @foreach($device->tags as $tag)
                                            <span class="badge bg-yellow text-dark">{{ $tag }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    @if($device->ip_addrs)
                                        {{ $device->ip_addrs[0] }}
                                    @endif
                                </td>
                                <td>{{ $device->os_version }}</td>
                                <td>{{ $device->pending_patches }}</td>
                                <td>
                                    <p class="p-0 m-0">Device Status : {{ ucwords($device->status->device_status) }}</p>
                                    <p class="p-0 m-0">Agent Status : {{ ucwords($device->status->agent_status) }}</p>
                                    <p class="p-0 m-0">Policy Status : {{ ucwords($device->status->policy_status) }}</p>
                                </td>
                                <td>{{ $device->agent_version }}</td>
                                <td>
                                    @php
                                        $start = new DateTime($device->last_disconnect_time, new DateTimeZone("UTC"));
                                        $end = new DateTime("now", new DateTimeZone('UTC'));
                                        echo ($start->diff($end)->days);
                                    @endphp
                                </td>
                                <td>{{ $device->last_logged_in_user }}</td>
                                <td>-</td>
                                <td>{{ $device->patches }}</td>
                                <td>
                                    @php
                                        $time = new DateTime($device->create_time, new DateTimeZone("UTC"));
                                        echo $time->format("d M Y H:i:s");
                                    @endphp
                                </td>
                                <td>
                                    @if(count($detail))
                                        @if(isset($detail['RAM']))
                                            {{ formatBytes($detail['RAM']) }}
                                        @endif
                                        @else 
                                        {{'-'}}
                                        @endif
                                </td>
                                <td>
                                    @if(count($detail))
                                        @if(isset($detail['CPU']))
                                            {{ $detail['CPU'] }}
                                        @endif
                                        @else 
                                        {{'-'}}
                                        @endif
                                </td>
                                <td>
                                    @if(count($detail))
                                        @if(isset($detail['DISKS']))
                                            @if(count($detail['DISKS']))
                                                {{ formatBytes($detail['DISKS'][0]->SIZE) }}
                                            @endif
                                        @endif
                                        @else 
                                        {{'-'}}
                                        @endif
                                </td>
                                <td>
                                    @if(count($detail))
                                        @if(isset($detail['VOLUME']))
                                            @if(count($detail['VOLUME']))
                                                {{ formatBytes($detail['VOLUME'][0]->FREE) }}
                                            @endif
                                            @else 
                                        {{'-'}}
                                        @endif
                                    @endif
                                </td>
                                <td>{{ ($device->serial_number?$device->serial_number:$device->serial_number) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

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
            "autoWidth": false,
            "columnDefs": [
                {
                    "targets": [6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
                    "visible": false,
                }
            ]
        });
        $('.toggle_colmn').each(function () {
            var column_index = $(this).data('column');
            if($(this).is(':checked')){
                var column = table.column(column_index);
                column.visible(true);
            }else{
                var column = table.column(column_index);
                column.visible(false);
            }
        });

        $('.toggle_colmn').click(function (e) {
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