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
    <div class="panel panel-inverse" >
        <div class="panel-heading">
            <h4 class="panel-title">Tags Removal</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('tagsremoval')  }}" method="get">
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
                              <div class="p-3 bg-light row m-3">
                                  <h4>Filter</h4><hr>
                                <div class="col-6">
                                    <label for="needs_reboot">Needs Reboot</label>
                                    <select name="needs_reboot" id="needs_reboot" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['needs_reboot']) && $_GET['needs_reboot'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="true" {{ isset($_GET['needs_reboot']) && $_GET['needs_reboot'] === 'true' ? 'selected' : '' }}>True</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="not_compatible">Not Compatible</label>
                                    <select name="not_compatible" id="not_compatible" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['not_compatible']) && $_GET['not_compatible'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="0" {{ isset($_GET['not_compatible']) && $_GET['not_compatible'] === '0' ? 'selected' : '' }}>True</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="device_status">Device Status</label>
                                    <select name="device_status" id="device_status" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['device_status']) && $_GET['device_status'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="ready" {{ isset($_GET['device_status']) && $_GET['device_status'] === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="not-ready" {{ isset($_GET['device_status']) && $_GET['device_status'] === 'not-ready' ? 'selected' : '' }}>Not Ready</option>
                                        <option value="refreshing" {{ isset($_GET['device_status']) && $_GET['device_status'] === 'refreshing' ? 'selected' : '' }}>Refreshing</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="connectivity_status">Device Connectivity Status</label>
                                    <select name="connectivity_status" id="connectivity_status" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['connectivity_status']) && $_GET['connectivity_status'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="true" {{ isset($_GET['connectivity_status']) && $_GET['connectivity_status'] === 'true' ? 'selected' : '' }}>Connected</option>
                                        <option value="0" {{ isset($_GET['connectivity_status']) && $_GET['connectivity_status'] === '0' ? 'selected' : '' }}>Disconnected</option>
                                    </select>
                                </div>
                                   <div class="col-12">
                                    <label for="tag">Tags</label>
                                    <select name="tag" id="tag" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['tag']) && $_GET['tag'] === '' ? 'selected' : '' }}>Not Needed</option>
                                 @if($users)
                                    @php
                                        $tags = [];
                            
                                        foreach($users as $user) {
                                            $tags = array_merge($tags, $user->tags);
                                        }
                            
                                        $uniqueTags = array_unique($tags);
                                    @endphp
                            
                                    @foreach($uniqueTags as $tag)
                                         <option value="{{ $tag }}" {{ isset($_GET['tag']) && $_GET['tag'] === '' ? 'selected' : '' }}>{{ $tag }}</option>
                                    @endforeach
                                @endif
                             
                                        
                                    </select>
                                </div>
                              </div>


                               
                        </div>
                         
                            <div class="col-md-6">
                                 <button type="submit" class="btn btn-primary">Submit</button>
                                 <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">Filter</button>
                        @if($org)
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
            <div style="margin-top : 1rem" id="dataTableContainer">
                <table id="data-table-default" class="table table-striped table-bordered" style="text-align: start";>
                    <thead>
                    <tr>
                        <th data-orderable="false">Name <button class="copy-btn" onclick="copyColumnData()">Copy</button></th>
                        <th data-orderable="false">Need Attention </th>
                        <th data-orderable="false">Not Compatible</th>
                        <th data-orderable="false">Need Reboot</th>
                        <th data-orderable="false">Disconnected Devices</th>
                        <th data-orderable="false">Tags</th>
                        <th data-orderable="false">Device Status</th>
                        <th data-orderable="false">Connection Status</th>
                        <th data-orderable="false">Device Remark</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($users)
                        @foreach($users as $user)
                                
                                @if($_GET['device_status'] == Null)
                                            <?php $devicevalue = true; ?>
                                         @else
                                          <?php $devicevalue = ($user->status->device_status == $_GET['device_status']); ?>
                                @endif
                                
                                @if($_GET['not_compatible'] == Null)
                                            <?php $not_compatiblevalue = true; ?>
                                         @else
                                          <?php $not_compatiblevalue = ($user->is_compatible == $_GET['not_compatible']); ?>
                                @endif
                                
                                @if($_GET['needs_reboot'] == Null)
                                            <?php $needs_rebootvalue = true; ?>
                                         @else
                                          <?php $needs_rebootvalue = ($user->needs_reboot == $_GET['needs_reboot']); ?>
                                @endif
                                
                                @if($_GET['connectivity_status'] == Null)
                                            <?php $connectivity_statusvalue = true; ?>
                                         @else
                                          <?php $connectivity_statusvalue = ($user->connected == $_GET['connectivity_status']); ?>
                                @endif
                                   
                                    
                                
                                
                                @if($needs_rebootvalue && $devicevalue && $not_compatiblevalue && $connectivity_statusvalue)
                                @php
                                    $tagFound = false;
                                @endphp
                    
                                @foreach($user->tags as $key => $value)
                                    {{-- Checking that tags should not be null or empty --}}
                                    @if($value != null && !empty($value))
                                        @php
                                            $tagFound = true;
                                        @endphp
                                    @endif
                                @endforeach
                    
                                {{-- Display the row only if at least one non-empty tag is found --}}
                                @if(true)
                                    <tr>
                                        <td id="nameColumn">{{ ($user->name ? $user->name : $user->custom_name) }}</td>
                                         <td>{{ $user->needs_attention ? "Attention Required" : '' }}</td>
                                         <td>{{ $user->is_compatible ? "" : 'Not Compatible' }}</td>
                                         <td>{{ $user->needs_reboot ? "Need Reboot" : "" }}</td>
                                        <td>
                                            @if($user->last_disconnect_time)
                                                @php
                                                    $lastDisconnect = new DateTime($user->last_disconnect_time);
                                                    $currentDate = new DateTime();
                                                    $difference = $currentDate->diff($lastDisconnect);
                                                    $differenceInDays = $difference->days;
                                                    
                                                    $interval = $currentDate->diff($lastDisconnect);
                                                    $daysDifference = $interval->days;
                                                @endphp
                                                
                                                @if($differenceInDays >= 30)
                                                    30+ days Disconnected Devices | {{ $lastDisconnect->format('Y-m-d H:i:s') }}
                                                @else
                                                    {{-- Do something when it's less than 30 days --}}
                                                    {{ $daysDifference }} Days
                                                @endif
                                                
                                            @else
                                            
                                            @endif
                            
                                            </td>
                                        <td>
                                            @foreach($user->tags as $key => $value)
                                                {{-- Display only non-empty tags --}}
                                                @if($value != null && !empty($value))
                                                    {{ $value.' , ' }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ ($user->status->device_status ? $user->status->device_status : "NA") }}</td>
                                        <td>{{ ($user->connected ? "Live" : "Disconnected") }}</td>
                                        <td>
                                            @php
                                            @endphp
                                                @if($user->needs_attention == "true" )
                                                    <p class='text-danger'>Attention</p>
                                                @elseif($user->needs_attention == "false")
                                                    <p class='text-warning'>Pending</p>
                                                @else
                                                    <p class='text-success'>Fully Ready</p>
                                                @endif
                                            </td>

                                    </tr>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    </tbody>

                </table>
            </div>
        </div>
    </div>
    
  
    
@endsection
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Apply Filter for {{ $org }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
<form action="" method="get" id="filterForm">
    <div class="modal-body">
        <div class="col-12">
                                    <label for="needs_reboot">Needs Reboot</label>
                                    <select name="needs_reboot" id="needs_reboot" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['needs_reboot']) && $_GET['needs_reboot'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="true" {{ isset($_GET['needs_reboot']) && $_GET['needs_reboot'] === 'true' ? 'selected' : '' }}>True</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="not_compatible">Not Compatible</label>
                                    <select name="not_compatible" id="not_compatible" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['not_compatible']) && $_GET['not_compatible'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="0" {{ isset($_GET['not_compatible']) && $_GET['not_compatible'] === '0' ? 'selected' : '' }}>True</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="device_status">Device Status</label>
                                    <select name="device_status" id="device_status" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['device_status']) && $_GET['device_status'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="ready" {{ isset($_GET['device_status']) && $_GET['device_status'] === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="not-ready" {{ isset($_GET['device_status']) && $_GET['device_status'] === 'not-ready' ? 'selected' : '' }}>Not Ready</option>
                                        <option value="refreshing" {{ isset($_GET['device_status']) && $_GET['device_status'] === 'refreshing' ? 'selected' : '' }}>Refreshing</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="connectivity_status">Device Connectivity Status</label>
                                    <select name="connectivity_status" id="connectivity_status" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['connectivity_status']) && $_GET['connectivity_status'] === '' ? 'selected' : '' }}>Not Needed</option>
                                        <option value="true" {{ isset($_GET['connectivity_status']) && $_GET['connectivity_status'] === 'true' ? 'selected' : '' }}>Connected</option>
                                        <option value="0" {{ isset($_GET['connectivity_status']) && $_GET['connectivity_status'] === '0' ? 'selected' : '' }}>Disconnected</option>
                                    </select>
                                </div>
                                   <div class="col-12">
                                    <label for="tag">Tags</label>
                                    <select name="tag" id="tag" class="form-control default-select2">
                                        <option value="" {{ isset($_GET['tag']) && $_GET['tag'] === '' ? 'selected' : '' }}>Not Needed</option>
                                 @if($users)
                                    @php
                                        $tags = [];
                            
                                        foreach($users as $user) {
                                            $tags = array_merge($tags, $user->tags);
                                        }
                            
                                        $uniqueTags = array_unique($tags);
                                    @endphp
                            
                                    @foreach($uniqueTags as $tag)
                                         <option value="{{ $tag }}" {{ isset($_GET['tag']) && $_GET['tag'] === '' ? 'selected' : '' }}>{{ $tag }}</option>
                                    @endforeach
                                @endif
                             
                                        
                                    </select>
                                </div>
                              
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Apply Filters</button>
    </div>
</form>






    </div>
  </div>
</div>
<script>
    function copyColumnData() {
        // Get all the cells in the second column
        var cells = document.querySelectorAll('#data-table-default tbody td:nth-child(1)');

        // Variable to store the concatenated column data
        var columnData = '';

        // Loop through each cell in the column
        cells.forEach(function(cell) {
            // Concatenate the text content of each cell with a newline character
            columnData += cell.innerText + '\n';
        });

        // Create a temporary textarea element to hold the column data
        var tempTextarea = document.createElement('textarea');
        tempTextarea.value = columnData;

        // Append the textarea to the document body
        document.body.appendChild(tempTextarea);

        // Select the text within the textarea
        tempTextarea.select();
        tempTextarea.setSelectionRange(0, 99999); // For mobile devices

        // Copy the selected text to the clipboard
        document.execCommand('copy');

        // Remove the temporary textarea
        document.body.removeChild(tempTextarea);

        // Optionally, provide visual feedback to the user
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
<script>
    $(document).ready(function() {
        var table = $('#data-table-default').DataTable();

        // Adding search functionality
        $('#data-table-default').on('keyup', function() {
            table.search("ready").draw();
        });
    });
</script>
@endsection