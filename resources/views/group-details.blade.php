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
            <h4 class="panel-title">GROUP DETAILS</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('groupdetails')  }}" method="get">
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
                            @if($groups)
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
                        <th data-orderable="false" class="text-center">Scan Interval</th>
                        <th data-orderable="false" class="text-center">Devices</th>
                        <th data-orderable="false" class="text-center">Policies</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($groups)
                            @foreach($groups as $group)
                                    <tr>
                                        <td>{{ $group->id }}</td>
                                        <td>{{ ($group->name ? $group->name : 'Default') }}</td>
                                        <td class="text-center">{{ floor($group->refresh_interval / 60) }} hours</td>
                                        <td class="text-center">{{ $group->server_count }}</td>
                                        <td class="text-center">{{ count($group->policies) }}</td>
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
<script>
    // JavaScript on the redirected page
window.onload = function () {
    // Get the URL parameter "search"
    const urlParams = new URLSearchParams(window.location.search);
    const searchValue = urlParams.get('search');

    // If the "search" parameter exists, set it as the value of the DataTable search input
    if (searchValue) {
        var dataTableSearchInput = document.querySelector("#data-table-default_filter input");
        if (dataTableSearchInput) {
            dataTableSearchInput.value = searchValue;
            var searchEvent = new Event('input', { bubbles: true });
            dataTableSearchInput.dispatchEvent(searchEvent);
        }
    }
};

</script>
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