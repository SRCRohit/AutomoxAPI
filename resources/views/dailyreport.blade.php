@extends('layouts.app')

@section('css')
<link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
    rel="stylesheet" />
<link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" />
<style type="text/css">

</style>
@endsection



@section('content')
<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Daily Device Report</h4>
    </div>
    <div class="panel-body">
        <div>
            <form action="{{ route('dailyreport')  }}" method="get">
                <input type="hidden" name="company" id="company" value="{{ ($company_detail?$company_detail->id:'') }}">
                <div class="row">
                    <div class="col-md-3">
                        <?php
                        $hasOrganisation = json_decode(auth()->user()->organisation, TRUE);
                        ?>
                        <select class="form-control default-select2" name="organisation" required
                            onchange="$('#company').val($('option:selected',this).data('company'))">
                            <option value="" {{ $org == '' ? 'selected': ''  }}>Select Organisation...</option>
                            {{--                            <option disabled data-company="all" value="all" {{ $org == 'all' ? 'selected': ''  }}>Select--}}
                            {{--                                All</option>--}}
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
//                                        $organisationHTML .= '<optgroup label="'.$company->name.'">'.$option.'</optgroup>';
                                        $organisationHTML .= $option;
                                    }
                                }
                            }
                            echo $organisationHTML;
                            ?>
                        </select>
                    </div>
                    <!--<div class="col-md-3">-->
                    <!--    <div class="input-group" id="default-daterange">-->
                    <!--        <input required type="text" value="{{ $daterange }}" class="form-control" name="daterange">-->
                    <!--        <div class="input-group-text"><i class="fa fa-calendar"></i></div>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary full-width">Apply Filter</button>
                     
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
                       
                    </div>
                </div>
            </form>
        </div>
        <div style="margin-top : 1rem">
            <table id="data-table-default" class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th data-orderable="false">ID</th>
                        <th data-orderable="false">Company Name</th>
                        <th data-orderable="false">MSP</th>
                        <th data-orderable="false">License Used</th>
                        <th data-orderable="false">Need Attention</th>
                        <th data-orderable="false">Need Reboot</th>
                        <th data-orderable="false">Need Compatible</th>
                        <th data-orderable="false">30+ Days Disconnected Device</th>
                        <th data-orderable="false">Date & Time</th>
                    </tr>
                </thead>
                    @if($org)
                    <tbody>
                        @foreach($dailydevicereport as $UserCounts)
                        <tr>
                            <td class="text-center">{{ $UserCounts->company_id }}</td>
                            <td class="text-center">{{ $UserCounts->company_name }}</td>
                            <td class="text-center">{{ $UserCounts->msp }}</td>
                            <td class="text-center">{{ $UserCounts->license_used }}</td>
                            <td class="text-center">{{ $UserCounts->need_attention }}</td>
                            <td class="text-center">{{ $UserCounts->need_reboot }}</td>
                            <td class="text-center">{{ $UserCounts->not_compatible }}</td>
                            <td class="text-center">{{ $UserCounts->disconnected_devices	 }}</td>
                            <td class="text-center">{{ $UserCounts->createdAt }} | {{ $UserCounts->updatedtime }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                @endif

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
<script src="{{ asset('assets/plugins/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $(".default-select2").select2();
    $('#data-table-default').DataTable({
        responsive: true,
    });
    $("#default-daterange").daterangepicker({
        opens: "right",
        format: "YYYY-MM-DD",
        separator: " to ",
        minDate: "2022-01-01",
        maxDate: moment(),
        ranges: {
            "Today": [moment(), moment()],
            "Yesterday": [moment().subtract(1, "days"), moment().subtract(1, "days")],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf(
                "month")]
        }
    }, function (start, end) {
        $("#default-daterange input").val(start.format("DD-MM-YYYY") + " - " + end.format("DD-MM-YYYY"));
    });
</script>
@endsection