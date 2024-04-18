@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
    <style type="text/css">

    </style>
@endsection



@section('content')
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">USER LOGIN REPORT</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('userlogindetails')  }}" method="get">
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
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div style="margin-top : 1rem">
                <table id="data-table-default" class="table table-striped table-bordered align-middle" style="text-align: center";>
                    <thead>
                    <tr>
                        <th data-orderable="false">Device ID</th>
                        <th data-orderable="false">Device Name</th>
                        <th data-orderable="false">Create Time</th>
                        <th data-orderable="false">User Login Time</th>
                        <th data-orderable="false">Last Porcess Time</th>
                        <th data-orderable="false">Last Refresh Time</th>
                        <th data-orderable="false">Last Update Time</th>
                        <th data-orderable="false">Last Disconnect Time</th>
                        <th data-orderable="false">Needs Reboot</th>
                        <th data-orderable="false"> Policy Name</th>
                        <th data-orderable="false">Policy Type Name</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    @if($users)
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    <a href="javascript:;" onclick="getUserActivity({{ $user->id }})" >
                                        {{ $user->id }}
                                    </a>
                                </td>
                                <td>{{ ($user->display_name?$user->display_name:$user->custom_name) }}</td>
                                <td>
                                    @php
                                        $dt = new DateTime($user->create_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
<td>
    <?php 
        if(isset($user->detail->LAST_USER_LOGON)){
            $lastUserLogonTime = $user->detail->LAST_USER_LOGON->TIME;
            if(strpos($lastUserLogonTime, '/') !== false){
                $dt = DateTime::createFromFormat('m/d/Y h:i:s A', $lastUserLogonTime, new DateTimeZone("UTC"));
            } else {
                $dt = DateTime::createFromFormat('d-m-Y H:i:s', $lastUserLogonTime, new DateTimeZone("UTC"));
            }
            if ($dt !== false) {
                echo $dt->format("d M Y H:i:s");
            } else {
                echo "NA";
            }
        } else {
            echo "NA";
        }
    ?>
</td>

                                <td>
                                    @php
                                        $dt = new DateTime($user->last_process_time, new DateTimeZone("UTC"));
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
                                 <td>
                                    @php
                                        $dt = new DateTime($user->last_disconnect_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
                                 <td class="text-center">
                                    @if($user->needs_reboot)
                                        TRUE
                                    @else
                                        FALSE
                                    @endif
                                </td>
<td>
    <?php 
        if(isset($user->status->policy_status->policy_name)){
            
            echo $user->status->policy_status->policy_name;
        } else {
            echo "N/A";
        }
    ?>
</td>
<td>
    <?php 
        if(isset($user->detail->status->policy_status->policy_type_name)){
            echo $user->policy_status->policy_type_name;
        } else {
            echo "N/A";
        }
    ?>
</td>

                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-activity">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">USER ACTIVITY FOR USER : <span id="for-user"
                                                                           class="text-danger"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <table style="width: 100%" id="data-table-activity" class="table table-striped table-bordered
                    align-middle">
                        <thead>
                            <tr>
                                 <th data-orderable="false">Device ID</th>
                        <th data-orderable="false">Device Name</th>
                        <th data-orderable="false">Create Time</th>
                        <th data-orderable="false">User Login Time</th>
                        <th data-orderable="false">Last Porcess Time</th>
                        <th data-orderable="false">Last Refresh Time</th>
                        <th data-orderable="false">Last Update Time</th>
                        <th data-orderable="false">Last Disconnect Time</th>
                        <th data-orderable="false">Needs Reboot</th>
                        <th data-orderable="false"> Policy Name</th>
                        <th data-orderable="false">Policy Type Name</th>
                            </tr>
                        </thead>
                        <tbody id="activity_data">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
                </div>
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
    <script type="text/javascript">
        $(".default-select2").select2();
        $('#data-table-default').DataTable({
            responsive: true,
            dom: '<"row"<"col-sm-5"B><"col-sm-7"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                { extend: 'copy', className: 'btn-sm' },
                { extend: 'csv', className: 'btn-sm' },
                { extend: 'excel', className: 'btn-sm' },
                { extend: 'pdf', className: 'btn-sm',orientation: 'landscape', },
                { extend: 'print', className: 'btn-sm' }
            ],
        });
        function getUserActivity(userid) {
            showLoader();
            $('#for-user').html(userid);
            $.post('{{ route('userlogindetails')  }}',{ _token : $('meta[name=csrf-token]').attr('content') ,
                'organisation' : {{ ($org?$org:0) }},'user_id' : userid , 'daterange' : '{{
                ($daterange?$daterange:'') }}', 'company' : $('#company').val()},function (response) {
                $('#data-table-activity').DataTable().clear().destroy();
                $('#activity_data').html(response);
                $('#data-table-activity').DataTable({
                    responsive: true,
                    dom: '<"row"<"col-sm-5"B><"col-sm-7"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
                    buttons: [
                        { extend: 'copy', className: 'btn-sm' },
                        { extend: 'csv', className: 'btn-sm' },
                        { extend: 'excel', className: 'btn-sm' },
                        { extend: 'pdf', className: 'btn-sm' },
                        { extend: 'print', className: 'btn-sm' }
                    ],
                });
                hideLoader();
                $('#modal-activity').modal('show');
            });
        }
        $("#default-daterange").daterangepicker({
            opens: "right",
            format: "YYYY-MM-DD",
            separator: " to ",
            maxDate: moment(),
            ranges: {
                "Today": [moment(), moment()],
                "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [moment().startOf("month"), moment().endOf("month")],
                "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")]
            }
        }, function (start, end) {
            $("#default-daterange input").val(start.format("DD-MM-YYYY") + " - " + end.format("DD-MM-YYYY"));
        });
    </script>
@endsection