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
            <h4 class="panel-title">DATA EXTRACT</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('dataextract')  }}" method="get">
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
                        <div class="col-md-2">
                            <select class="form-control" name="type">
                                <option value="patch-history" {{ ($type=='patch-history'?'selected':'') }}>Patch
                                    History</option>
                                <option value="api-activity" {{ ($type=='api-activity'?'selected':'') }}>API
                                    Activity</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-control multi-select2" name="status[]" multiple>
                                <option value="queued" {{ (in_array('queued', $status)?'selected':'') }}>Queued</option>
                                <option value="running" {{ (in_array('running', $status)?'selected':'') }}>Running</option>
                                <option value="complete" {{ (in_array('complete', $status)?'selected':'') }}>Complete</option>
                                <option value="failed" {{ (in_array('failed', $status)?'selected':'') }}>Failed</option>
                                <option value="canceled" {{ (in_array('canceled', $status)?'selected':'') }}>Canceled</option>
                                <option value="expired" {{ (in_array('expired', $status)?'selected':'') }}>Expired</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div style="margin-top : 1rem">
                <table id="data-table-default" class="table table-striped table-bordered align-middle">
                    <thead>
                    <tr>
                        <th data-orderable="false">ID</th>
                        <th data-orderable="false">Company</th>
                        <th data-orderable="false">MSP</th>
                        <th data-orderable="false">User</th>
                        <th data-orderable="false">Date Requested</th>
                        <th data-orderable="false">Date Range</th>
                        <th data-orderable="false">Extract Expiration</th>
                        <th data-orderable="false">Type</th>
                        <th data-orderable="false">Status</th>
                        <th data-orderable="false">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($data)
                            @foreach($data as $extract)
                                <tr>
                                    <td>{{ $extract->id }}</td>
                                    <td>{{ $orgs->name }}</td>
                                    <td>{{ $company_detail->name }}</td>
                                    <td>{{ $extract->username }}</td>
                                    <td>{{ explode('T',$extract->created_at)[0] }}</td>
                                    <td>{{ explode('T',$extract->parameters->start_time)[0] }} - {{ explode('T',$extract->parameters->end_time)[0] }}</td>
                                    <td>{{ explode('T', $extract->download_expires_at)[0] }}</td>
                                    <td>{{ ucwords(str_replace('-',' ',$type)) }}</td>
                                    <td>{{ ucwords($extract->status) }}</td>
                                    <td>
                                        @if($extract->download_url)
                                            <a class="btn btn-dark btn-xs" href="{{ $extract->download_url }}"
                                               target="_blank">Download</a>
                                        @else
                                            <a href="javascript:;" class="btn btn-dark btn-xs disabled">Download</a>
                                        @endif
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
                            <th data-orderable="false">Event Id</th>
                            <th data-orderable="false">Event Type</th>
                            <th data-orderable="false">Organisation</th>
                            <th data-orderable="false">Name</th>
                            <th data-orderable="false">Email Id</th>
                            <th data-orderable="false">Time</th>
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
    <script src="{{ asset('assets/plugins/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(".default-select2").select2();
        $(".multi-select2").select2({ placeholder: "Select status..." });
        $('#data-table-default').DataTable({
            responsive: true,
        });
        function getUserActivity(userid) {
            showLoader();
            $('#for-user').html(userid);
            $.post('{{ route('useractivityreport')  }}',{ _token : $('meta[name=csrf-token]').attr('content') ,
                'organisation' : {{ ($org?$org:0) }},'user_id' : userid , 'company' : $('#company').val()},function (response) {
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