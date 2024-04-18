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
            <h4 class="panel-title">PREPATCH REPORT</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('prepatchreport')  }}" method="get">
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
                        @if($org)
                            <div class="col-md-2">
                                <select class="form-control" name="group">
                                    <option value="0" {{ $group == '0'? 'selected':''  }}>Select Group...</option>
                                    @foreach($groups as $groupdata)
                                        @if($groupdata->name)
                                            <option value="{{ $groupdata->id  }}" {{ $group == $groupdata->id? 'selected':''  }}>{{ $groupdata->name  }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="col-md-7">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            @if($reports)
                                @if($reports->prepatch->total)
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
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <div style="margin-top : 1rem">
                <table id="data-table-default" class="table table-striped table-bordered align-middle">
                    <thead>
                    <tr>
                        <th data-orderable="false">Device ID</th>
                        <th data-orderable="false">Name</th>
                        <th data-orderable="false" class="text-center">Group</th>
                        <th data-orderable="false" class="text-center">OS</th>
                        <th data-orderable="false">Patches</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($reports)
                            @if($reports->prepatch->total)
                                @foreach($reports->prepatch->devices as $report)
                                    <tr>
                                        <td>{{ $report->id }}</td>
                                        <td>{{ $report->name }}</td>
                                        <td class="text-center">{{ $report->group }}</td>
                                        <td class="text-center">{{ $report->os_family }}</td>
                                        <td style="width:60%">
                                            <table class="table table-bordered m-0" border="1">
                                                <tr>
                                                    <th>Software Name</th>
                                                    <th class="text-center">Severity</th>
                                                    <th class="text-center">Create Time</th>
                                                    <th class="text-center">Patch Time</th>
                                                </tr>
                                                @foreach($report->patches as $patch)
                                                    <?php
                                                        $row_color = 'table-active';
                                                        if($patch->severity == 'low'){
                                                            $row_color = 'table-success';
                                                        }elseif($patch->severity == 'high'){
                                                            $row_color = 'table-danger';
                                                        }elseif($patch->severity == 'medium'){
                                                            $row_color = 'table-warning';
                                                        }elseif($patch->severity == 'critical'){
                                                            $row_color = 'table-critical';
                                                        }
                                                    ?>
                                                    <tr class="{{$row_color}}">
                                                        <td style="width:66%">{{ $patch->name }}</td>
                                                        <td class="text-center" style="width:10%">{{ ($patch->severity ? ucwords($patch->severity): 'Unknown') }}</td>
                                                        <td class="text-center" style="width:12%">{{ explode('T', $patch->createTime)[0] }}</td>
                                                        <td class="text-center" style="width:12%">{{ explode('T', $patch->patchTime)[0] }}</td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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