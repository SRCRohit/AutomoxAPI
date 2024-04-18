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
            <h4 class="panel-title">USER POLICY REPORT</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('userpolicyreport')  }}" method="get">
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
                        <th data-orderable="false">Server Id</th> 
                        <th data-orderable="false">Server Name</th>
                        <!--<th data-orderable="false">Device Name</th>-->
                        <th data-orderable="false">Name</th>
                        <th data-orderable="false">Policy Name</th>
                        <th data-orderable="false">Policy Type</th>
                        <th data-orderable="false">Create Time</th>
                        
                        
                    </tr>
                    </thead>
<tbody>
@if($userpolicies || $users)
    @foreach($userpolicies as $userpolicy)
    @if ($userpolicy->server_id != null)
        <tr>
            <td>
                <a href="javascript:;" onclick="getUserActivity({{ $userpolicy->server_id }})" >
                    {{ $userpolicy->server_id }}
                </a>
            </td>
            <td>{{ ($userpolicy->server_name) }}</td>
            <!--<td>-->
            <!--    @foreach($users as $user)-->
                    <!--@if($user->id == $userpolicy->user_id)-->
            <!--            {{ ($user->display_name) }}-->
                    <!--@endif-->
            <!--    @endforeach-->
            <!--</td>-->
            <td>{{ ($userpolicy->name) }}</td>
            <td>{{ $userpolicy->policy_name }}</td>
            <td class="text-center">{{ ucwords(str_replace('_', '', $userpolicy->policy_type_name)) }}</td>
            <td>
                @php
                    $dt = new DateTime($userpolicy->create_time, new DateTimeZone("UTC"));
                    echo $dt->format("d M Y H:i:s ");
                @endphp
            </td>
        </tr>
    @endif
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
                    <h4 class="modal-title">POLICY REPORT FOR USER : <span id="for-user"
                                                                           class="text-danger"></span></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <table style="width: 100%" id="data-table-activity" class="table table-striped table-bordered
                    align-middle">
                        <thead>
                            <tr>
                                <th data-orderable="false">Server Id</th>
                        <th data-orderable="false">Server Name</th>
                        <th data-orderable="false">Display Name</th>
                        <th data-orderable="false">Policy Name</th>
                        <th data-orderable="false">Policy Type</th>
                        <th data-orderable="false">Create Time</th>
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
            $.post('{{ route('userpolicyreport')  }}',{ _token : $('meta[name=csrf-token]').attr('content') ,
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