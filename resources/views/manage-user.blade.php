@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/plugins/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}" rel="stylesheet" />
    <style type="text/css">
        label.error{
            display: none !important;
        }
        .error {
            margin: 0 auto;
            text-align: left;
            height: unset;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-control.error{
            border: 1px solid #c80000;
        }
    </style>
@endsection



@section('content')
    <div class="row">
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active">ADD USER</a>
            </li>
            <li class="nav-item">
                <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link">LIST USER</a>
            </li>
        </ul>
        <div class="tab-content panel p-3 rounded-0 rounded-bottom">
            <div class="tab-pane fade active show" id="default-tab-1">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Add User</h4>
                    </div>
                    <div class="panel-body">
                        <form id="add-company" method="post" action="{{ route('manageuser') }}" class="form-horizontal">
                            @csrf
                            <input type="hidden" name="method_type" value="insert"/>
                            <input type="hidden" name="insert[type]" value="user"/>
                            <div class="form-group mb-3">
                                <input autocomplete="off" required type="text" class="form-control" name="insert[name]"
                                       placeholder="Full Name"/>
                            </div>
                            <div class="form-group mb-3">
                                <input autocomplete="off" required type="email" class="form-control" name="insert[email]"
                                       placeholder="Email Address"/>
                            </div>
                            <div class="form-group mb-3">
                                <input autocomplete="off" required type="password" class="form-control" name="insert[password]"
                                       placeholder="Password" id="password"/>
                            </div>
                            <div class="form-group mb-3">
                                <input autocomplete="off" required type="password" class="form-control"
                                       placeholder="Confirm Password"/>
                            </div>
                            <div class="form-group mb-3">
                                <label>
                                    <h5>Permissions</h5>
                                </label>
                                <br/>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][manage_company]" type="checkbox"
                                           value="1"
                                           id="manage_company">
                                    <label class="form-check-label" for="manage_company">
                                        Manage Company
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][company_access]" type="checkbox"
                                           value="1"
                                           id="company_access">
                                    <label class="form-check-label" for="company_access">
                                        Company Access
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][company_view]" type="checkbox"
                                           value="1"
                                           id="company_view">
                                    <label class="form-check-label" for="company_view">
                                        Company User Count
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][user_activity_report]" type="checkbox"
                                           value="1"
                                           id="user_activity_report">
                                    <label class="form-check-label" for="user_activity_report">
                                        User Activity Report
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][device_report]" type="checkbox"
                                           value="1"
                                           id="device_report">
                                    <label class="form-check-label" for="device_report">
                                        Device Report
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][prepatch_report]" type="checkbox"
                                           value="1"
                                           id="prepatch_report">
                                    <label class="form-check-label" for="prepatch_report">
                                        Prepatch Report
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][group_details]" type="checkbox"
                                           value="1"
                                           id="group_details">
                                    <label class="form-check-label" for="group_details">
                                        Group Details
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][device_information]" type="checkbox"
                                           value="1"
                                           id="device_information">
                                    <label class="form-check-label" for="device_information">
                                        Device Information
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][needs_attention_report]" type="checkbox"
                                           value="1"
                                           id="needs_attention_report">
                                    <label class="form-check-label" for="needs_attention_report">
                                        Needs Attention Report
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][manual_approvals]" type="checkbox"
                                           value="1"
                                           id="manual_approvals">
                                    <label class="form-check-label" for="manual_approvals">
                                        Manual Approvals
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][policies]" type="checkbox"
                                           value="1"
                                           id="policies">
                                    <label class="form-check-label" for="policies">
                                        Policies
                                    </label>
                                </div>
                                 <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][user_login_details]" type="checkbox"
                                           value="1"
                                           id="user_login_data">
                                    <label class="form-check-label" for="user_login_data">
                                        User Login Data
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][user_policy_report]" type="checkbox"
                                           value="1"
                                           id="user_policy_report">
                                    <label class="form-check-label" for="user_policy_report">
                                        User Policy Report
                                    </label>
                                </div>
                                 <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][connections]" type="checkbox"
                                           value="1"
                                           id="connections">
                                    <label class="form-check-label" for="connections">
                                        connections
                                    </label>
                                </div>
                                 <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][tagsremoval]" type="checkbox"
                                           value="1"
                                           id="tagsremoval">
                                    <label class="form-check-label" for="tagsremoval">
                                        Tag Removal
                                    </label>
                                </div>
                                 
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][activity_logs]" type="checkbox"
                                           value="1"
                                           {{ ( isset($permissions['activity_logs'])?'checked':'') }}
                                           id="activity_logs">
                                    <label class="form-check-label" for="activity_logs">
                                        Activity Log
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" name="insert[permission][added_devices]" type="checkbox"
                                           value="1"
                                           id="added_devices">
                                    <label class="form-check-label" for="added_devices">
                                        Added Devices
                                    </label>
                                </div>
                                <div class="form-check  form-check-inline mt-2 mb-2 d-none">
                                    <input class="form-check-input" name="insert[permission][data_extract]" type="checkbox"
                                           value="1"
                                           id="data_extract">
                                    <label class="form-check-label" for="data_extract">
                                        Data Extract
                                    </label>
                                </div>
                            </div>
                            @foreach(\App\Models\Company::all() as $company)
                            <div class="form-group mb-3">
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input class="form-check-input" type="checkbox" onchange="checkCompanies(this,{{ $company->id }})">
                                    <label class="form-check-label">
                                        <h5>{{ $company->name }}</h5>
                                    </label>
                                </div>
                                <br/>
                                @foreach(\App\Models\Organisation::where('company_id', $company->id)->get() as $organisation)
                                <div class="form-check  form-check-inline mt-2 mb-2">
                                    <input id="org_{{$organisation->id}}" class="form-check-input company-check-{{ $company->id }}"
                                           name="insert[organisation][{{
                                    $organisation->id
                                    }}]" type="checkbox"
                                           value="1">
                                    <label for="org_{{$organisation->id}}" class="form-check-label">
                                        {{ $organisation->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                            <div class="form-group">
                                <button onclick="validateForm(event)"
                                        type="submit"
                                        class="btn
                            btn-dark btn-sm"><i
                                            class="fa
                            fa-save me-1
                            text-white"></i> Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="default-tab-2">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">User List</h4>
                    </div>
                    <div class="panel-body">
                        <table width="100%" id="data-table-default" class="table table-striped table-bordered align-middle">
                            <thead>
                            <tr>
                                <th data-orderable="false">#</th>
                                <th data-orderable="false">NAME</th>
                                <th data-orderable="false">Email</th>
                                <th data-orderable="false">PASSWORD</th>
                                <th data-orderable="false">PERMISSION</th>
                                <th data-orderable="false" class="text-center">CREATED</th>
                                <th data-orderable="false" class="text-center">ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $users = \App\Models\User::where('type', 'user')->get();
                            ?>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @for($loop = 1; $loop <= 10; $loop++)
                                            <i class="fa fa-circle fa-xs"></i>
                                        @endfor
                                    </td>
                                    <td>
                                        <?php
                                        foreach (json_decode($user->permission, TRUE) as $permission => $value){
                                            echo '<p class="m-0 p-0"><i class="fa fa-circle fa-xs text-success me-1
                                            "></i>'.ucwords(str_replace('_', ' ',$permission)).'</p>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">{{ date('Y-m-d',$user->created_at) }}</td>
                                    <td class="text-center">
                                                                            <a class="btn btn-purple btn-xs" href="{{ route('manageuseredit',[$user->id]) }}"><i class="fa
                                                                                fa-edit me-1"></i> Edit</a>
                                        <button class="btn btn-danger btn-xs" onclick="$('#deleteCompany_{{ $user->id }}').submit()"><i class="fa fa-trash me-1"></i> Delete</button>
                                        <form id="deleteCompany_{{ $user->id }}" method="post" action="{{ route('manageuser') }}">
                                            @csrf
                                            <input type="hidden" name="method_type" value="delete"/>
                                            <input type="hidden" name="id" value="{{ $user->id }}"/>

                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-9">

        </div>
    </div>
    <div class="modal fade" id="modal-users">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Users List</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
                </div>
                <div class="modal-body">
                    <table style="width: 100%" id="data-table-users" class="table table-striped table-bordered
                    align-middle">
                        <thead>
                        <tr>
                            <th data-orderable="false">User Id</th>
                            <th data-orderable="false">Name</th>
                            <th data-orderable="false">Email Id</th>
                        </tr>
                        </thead>
                        <tbody id="user_data">

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
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript">
        function checkCompanies(obj, id){
            if($(obj).is(':checked')){
                $('.company-check-'+id).prop('checked', true);
            }else{
                $('.company-check-'+id).prop('checked', false);
            }
        }
        $('#data-table-default').DataTable({
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
        $('#data-table-users').DataTable({
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

        function updateCompany(id) {
            showLoader();
            let company_id = id;
            let company_name = $('#name_'+id).text();
            $.post('{{ route('managecompany') }}',{ _token : $('meta[name=csrf-token]').attr('content'), 'company_id'
                    : company_id, 'company_name' : company_name, 'type' : 'update' },function
                (response) {
                location.reload();
            });
        }
        function findUserID()
        {
            showLoader();
            let organisation_id = $('[name=org_id]').val();
            let api_key = $('[name=api]').val();
            console.log(organisation_id, api_key);
            if(!organisation_id){
                hideLoader();
                alert('Organisation Id required.');
                return false;
            }
            if(!api_key){
                hideLoader();
                alert('API key required.');
                return false;
            }
            $.post('{{ route('managecompany') }}', { _token: '{{ csrf_token() }}', type : 'user_list', org_id :
                organisation_id, api : api_key
            }, function (response) {
                if(isObject(response)){
                    hideLoader();
                    alert(response.error);
                }else{
                    $('#data-table-users').DataTable().clear().destroy();
                    $('#user_data').html(response);
                    $('#data-table-users').DataTable({
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
                    $('#modal-users').modal('show');
                }
            });
        }

        function validateForm(e) {
            e.preventDefault();
            if($('#add-company').valid()){
                showLoader();
                $('#add-company').submit();
            }
        }

        function isObject(val) {
            if (val === null) { return false;}
            return ( (typeof val === 'function') || (typeof val === 'object') );
        }
    </script>
@endsection