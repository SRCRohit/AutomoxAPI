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
    </style>
@endsection



@section('content')
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">MANUAL APPROVAL</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('manualapprovals')  }}" method="get">
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
                        <div class="col-md-7">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @if($approvals)
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
                </form>
            </div>
            <div style="margin-top : 1rem">
                <table id="data-table-default" class="table table-striped table-bordered align-middle">
                    <thead>
                    <tr>
                        <th data-orderable="false">ID</th>
                        <th data-orderable="false">Name</th>
                        <th data-orderable="false" class="text-center">OS</th>
                        <th data-orderable="false" class="text-center">Current Status</th>
                        <th data-orderable="false" class="text-center">Created</th>
                        <th data-orderable="false" class="text-center">Policy</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($approvals)
                        @foreach($approvals as $approval)
                            @if($approval->status != '')
                                <tr>
                                    <td>{{ $approval->id }}</td>
                                    <td>{{ $approval->software->display_name }}</td>
                                    <td class="text-center">{{ $approval->software->os_family }}</td>
                                    <td class="text-center" class="text-center">
                                        <?php
                                            $status = $approval->status; // Assuming $approval->status contains the status value
                                            
                                            if ($status == 'approved') {
                                                $badgeClass = 'bg-success';
                                            } else {
                                                $badgeClass = 'bg-danger';
                                            }
                                            
                                            $capitalizedStatus = ucwords($status);
                                            ?>
                                       <span class="badge <?php echo $badgeClass; ?> rounded-pill">
                                            <?php echo $capitalizedStatus; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $approval->manual_approval_time }}</td>
                                    <td class="text-center">{{ $approval->policy->name }}</td>
                                </tr>
                            @endif
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
    <script src="{{ asset('assets/plugins/select2/dist/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(".default-select2").select2();
        $('#data-table-default').DataTable({
            responsive: true
        });
    </script>
@endsection