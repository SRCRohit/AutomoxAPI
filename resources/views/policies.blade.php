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
        .fa-brands,.fas{
         font-size:20px;
        }
        .fa-windows{
         color:#00a2ed;
         font-size:20px;
        }
        .fa-apple{
         color:#333;
         font-size:20px;
        }
        .fa-linux{
         color:#e9bf22;
         font-size:20px;
        }
        .fa-desktop{
         color:#0D1282;
         font-size:20px;
        }
    </style>
@endsection



@section('content')
    <div class="panel panel-inverse">
        <div class="panel-heading">
            <h4 class="panel-title">POLICIES</h4>
        </div>
        <div class="panel-body">
            <div>
                <form action="{{ route('policies')  }}" method="get">
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
                            @if($policies)
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
                        <th data-orderable="false">Policy Name</th>
                        <th data-orderable="false" class="text-center">Policy Type</th>
                        <th data-orderable="false" class="text-center" label="configuration.notify_deferred_reboot_user">Reboot user</th>
                        <th data-orderable="false" class="text-center" label="configuration.custom_notification_max_delays">Notification Max Delays</th>
                        <th data-orderable="false" class="text-center" label="configuration.notify_deferred_reboot_user_message_timeout">Reboot User Message Timeout</th>
                        <th data-orderable="false" class="text-center" label="configuration.custom_pending_reboot_notification_max_delays">Pending Reboot Notifiy Delays</th>
                        <!--<th data-orderable="false" class="text-center">Associated with</th>-->
                        <th data-orderable="false" class="text-center">Auto Patch</th>
                        <th data-orderable="false" class="text-center">Patch Rule</th>
                        <th data-orderable="false" class="text-center">OS Type</th>
                        <th data-orderable="false" class="text-center">Filter Type</th>
                        <th data-orderable="false" class="text-center">Reboot User Auto Deferral</th>
                        <th data-orderable="false" class="text-center">Server Group</th>
                        <th data-orderable="false" class="text-center">Schedule Time</th>
                        <th data-orderable="false" class="text-center">Created Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($policies)
                        @foreach($policies as $policy)
                            <tr>
                                <td>{{ $policy->id }}</td>
                                <td>{{ $policy->name }}</td>
                                <td class="text-center">{{ ucwords(str_replace('_', '', $policy->policy_type_name)) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ isset($policy->configuration->notify_deferred_reboot_user) && $policy->configuration->notify_deferred_reboot_user ? 'green' : 'danger' }}">
                                        {{ isset($policy->configuration->notify_deferred_reboot_user) && $policy->configuration->notify_deferred_reboot_user ? 'True' : 'False' }}
                                    </span>
                                </td>
                                <td class="text-center">{{ isset($policy->configuration->custom_notification_max_delays) ? $policy->configuration->custom_notification_max_delays: 'N/A' }}</td>
                                <td class="text-center">{{ isset($policy->configuration->notify_deferred_reboot_user_message_timeout) ? $policy->configuration->notify_deferred_reboot_user_message_timeout: 'N/A' }}</td>
                                <td class="text-center">{{ isset($policy->configuration->custom_pending_reboot_notification_max_delays) ? $policy->configuration->custom_pending_reboot_notification_max_delays: 'N/A' }}</td>    
                                <!--<td class="text-center">{{ count($policy->server_groups) }}</td>-->
                                 <td class="text-center">
                                    <span class="badge bg-{{ isset($policy->configuration->auto_patch) && $policy->configuration->auto_patch ? 'green' : 'danger' }}">
                                        {{ isset($policy->configuration->auto_patch) && $policy->configuration->auto_patch ? 'True' : 'False' }}
                                    </span>
                                </td>
                                  <td class="text-center text-uppercase">{{ isset($policy->configuration->patch_rule) ? $policy->configuration->patch_rule: 'N/A' }}</td>
                                 <td class="text-center">
                                        @if (isset($policy->configuration->os_family) && $policy->configuration->os_family === 'Mac')
                                            <i class="fa-brands fa-apple"></i>
                                        @elseif (isset($policy->configuration->os_family) && $policy->configuration->os_family === 'Windows')
                                            <i class="fa-brands fa-windows"></i>
                                        @elseif (isset($policy->configuration->os_family) && $policy->configuration->os_family === 'Linux')
                                            <i class="fa-brands fa-linux"></i>

                                        @else
                                            <i class="fas fa-desktop"></i>
                                        @endif
                                    </td>
                                  <td class="text-center text-uppercase">{{ isset($policy->configuration->filter_type) ? $policy->configuration->filter_type: 'N/A' }}</td>
                                  <td class="text-center">
                                    <span class="badge bg-{{ isset($policy->configuration->notify_deferred_reboot_user_auto_deferral_enabled) && $policy->configuration->notify_deferred_reboot_user_auto_deferral_enabled ? 'green' : 'danger' }}">
                                        {{ isset($policy->configuration->notify_deferred_reboot_user_auto_deferral_enabled) && $policy->configuration->notify_deferred_reboot_user_auto_deferral_enabled ? 'True' : 'False' }}
                                    </span>
                                </td>
                                <!-- HTML Template -->
                                    <td class="text-center">
                                        <?php if (isset($policy->server_groups)): ?>
                                            <?php foreach ($policy->server_groups as $group): ?>
                                                <a id="groupLink" href="/group-details?company=<?php echo $_GET['company'] ?>&organisation=<?php echo $_GET['organisation'] ?>" style="text-decoration:none;" onclick="copyToClipboard('<?php echo $group; ?>')">
                                                    <?php echo $group; ?>
                                                </a>
                                                <br> 
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </td>
                                 <td >
                                     @php
                                        $dt = new DateTime($policy->schedule_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
                                </td>
                                <td>
                                    @php
                                        $dt = new DateTime($policy->create_time, new DateTimeZone("UTC"));
                                        echo $dt->format("d M Y H:i:s ");
                                    @endphp
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

@section('javascript')
<script>
function copyToClipboard(text) {
    // Create a temporary input element
    var tempInput = document.createElement("input");
    tempInput.style.position = "absolute";
    tempInput.style.left = "-9999px";
    tempInput.value = text;

    // Append the temporary input element to the body
    document.body.appendChild(tempInput);

    // Select and copy the text in the input element
    tempInput.select();
    document.execCommand("copy");

    // Remove the temporary input element
    document.body.removeChild(tempInput);

    // Display an alert for a second after copying to the clipboard
    alert("Link copied to clipboard!");

    // Clear the alert after a second
    setTimeout(function() {
        var alertBox = document.getElementsByClassName("alert");
        if (alertBox.length > 0) {
            alertBox[0].style.display = "none";
        }
    }, 1000);
}
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