<div id="sidebar" class="app-sidebar">

    <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">

        <div class="menu">
            <div class="menu-header">Navigation</div>
            <?php
                $companies = \App\Models\Company::all();
                $class = 'd-none';
                if(count($companies)){
                    $class = '';
                }
                $menu = array(
                    'manage_company' => '',
                    'company_access' => '', 
                    'company_view' => '',
                    'user_activity_report' => '',
                    'prepatch_report' => '',
                    'data_extract' => 'd-none',
                    'device_report'=> '',
                    'group_details' => '',
                    'device_information' => '',
                    'needs_attention_report' => '',
                    'manual_approvals' => '',
                    'policies' => '',
                    'user_login_details' => '',
                    'user_policy_report' => '',
                    'added_devices' => '',
                    'activity_logs' => '',
                    'connections' => '',
                    'tagsremoval' => ''
                    );
                if(auth()->user()->type == 'user'){
                    $menu = array(
                        'manage_company' => 'd-none',
                        'company_access' => 'd-none',
                        'company_view' => 'd-none',
                        'user_activity_report' => 'd-none',
                        'prepatch_report' => 'd-none',
                        'data_extract' => 'd-none',
                        'device_report' => 'd-none',
                        'group_details' => 'd-none',
                        'device_information' => 'd-none',
                        'needs_attention_report' => 'd-none',
                        'manual_approvals' => 'd-none',
                        'policies' => 'd-none',
                        'user_login_details' => 'd-none',
                        'user_policy_report' => 'd-none',
                        'added_devices' => 'd-none',
                        'activity_logs' => 'd-none',
                        'tagsremoval' => 'd-none',
                        'connections' => 'd-none');
                    $permission = json_decode(auth()->user()->permission, TRUE);
                    foreach ($permission as $key => $value){
                        $menu[$key] = '';
                    }
                }
            ?>
            <div class="menu-item {{ $class }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Dashboard</div>
                </a>
            </div>

            <div class="menu-item {{ $menu['manage_company'] }}">
                <a href="{{ route('managecompany') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Manage Company</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['company_access'] }}">
                <a href="{{ route('companyaccess') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Company Access</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['company_view'] }}">
                <a href="{{ route('companyview') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Company User Count</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['user_activity_report'] }}">
                <a href="{{ route('useractivityreport') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">User Activity Report</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['device_report'] }}">
                <a href="{{ route('devicereport') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Device Report</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['prepatch_report'] }}">
                <a href="{{ route('prepatchreport') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Pre Patch Report</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['data_extract'] }}">
                <a href="{{ route('dataextract') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Data Extract</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['group_details'] }}">
                <a href="{{ route('groupdetails') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Group Details</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['device_information'] }}">
                <a href="{{ route('deviceinformation') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Device Information</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['needs_attention_report'] }}">
                <a href="{{ route('needsattentionreport') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Need Attention Report</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['manual_approvals'] }}">
                <a href="{{ route('manualapprovals') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Manual Approval</div>
                </a>
            </div>

            <div class="menu-item {{ $class }} {{ $menu['policies'] }}">
                <a href="{{ route('policies') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Policies</div>
                </a>
            </div>
            
             <div class="menu-item {{ $class }}  {{ $menu['user_login_details'] }}">
                <a href="{{ route('userlogindetails') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">User Login Data</div>
                </a>
            </div>
            <div class="menu-item {{ $class }}  {{ $menu['user_policy_report'] }}">
                <a href="{{ route('userpolicyreport') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">User Policy Report</div>
                </a>
            </div>
            <div class="menu-item {{ $class }} {{ $menu['added_devices'] }} ">
                <a href="{{ route('addeddevices') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Added Devices</div>
                </a>
            </div>
            <div class="menu-item {{ $class }}  {{ $menu['activity_logs'] }}">
                <a href="{{ route('activity-logs') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Activity Logs</div>
                </a>
            </div>
            <div class="menu-item {{ $class }} {{ $menu['connections'] }}">
                <a href="{{ route('connections') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Connections</div>
                </a>
            </div>
            <div class="menu-item {{ $class }} {{ $menu['tagsremoval'] }}">
                <a href="{{ route('tagsremoval') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Tag Removal</div>
                </a>
            </div>
           
            <div class="menu-item {{ $class }} {{ (auth()->user()->type == 'user'? 'd-none':'') }}">
                <a href="{{ route('manageuser') }}" class="menu-link">
                    <div class="menu-icon">
                        <i class="fa fa-chevron-circle-right"></i>
                    </div>
                    <div class="menu-text">Manage User</div>
                </a>
            </div>
            
            
            
            
            

        </div>

    </div>

</div>
<script>
	window.fwSettings={
	'widget_id':89000000416
	};
	!function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}() 
</script>
<script type='text/javascript' src='https://ind-widget.freshworks.com/widgets/89000000416.js' async defer></script>