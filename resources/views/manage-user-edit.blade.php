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
    <div class="panel panel-inverse">
        <div class="panel-heading">
            @php
                $permissions = json_decode($user->permission, TRUE);
                $organisations = json_decode($user->organisation, TRUE);
            @endphp
            <h4 class="panel-title">Edit User ( {{ $user->name }} )</h4>
        </div>
        <div class="panel-body">
            <form id="add-company" method="post" action="{{ route('manageuser') }}" class="form-horizontal">
                @csrf
                <input type="hidden" name="method_type" value="update"/>
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="form-group mb-3">
                    <input autocomplete="off" required type="text" class="form-control" name="insert[name]"
                           placeholder="Full Name" value="{{ $user->name }}"/>
                </div>
                <div class="form-group mb-3">
                    <label>
                        <h5>Permissions</h5>
                    </label>
                    <br/>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][manage_company]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['manage_company'])?'checked':'') }}
                               id="manage_company">
                        <label class="form-check-label" for="manage_company">
                            Manage Company
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][company_access]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['company_access'])?'checked':'') }}
                               id="company_access">
                        <label class="form-check-label" for="company_access">
                            Company Access
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][company_view]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['company_view'])?'checked':'') }}
                               id="company_view">
                        <label class="form-check-label" for="company_view">
                            Company User Count
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][user_activity_report]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['user_activity_report'])?'checked':'') }}
                               id="user_activity_report">
                        <label class="form-check-label" for="user_activity_report">
                            User Activity Report
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][device_report]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['device_report'])?'checked':'') }}
                               id="device_report">
                        <label class="form-check-label" for="device_report">
                            Device Report
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][prepatch_report]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['prepatch_report'])?'checked':'') }}
                               id="prepatch_report">
                        <label class="form-check-label" for="prepatch_report">
                            Prepatch Report
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][group_details]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['group_details'])?'checked':'') }}
                               id="group_details">
                        <label class="form-check-label" for="group_details">
                            Group Details
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][device_information]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['device_information'])?'checked':'') }}
                               id="device_information">
                        <label class="form-check-label" for="device_information">
                            Device Information
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][needs_attention_report]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['needs_attention_report'])?'checked':'') }}
                               id="needs_attention_report">
                        <label class="form-check-label" for="needs_attention_report">
                            Needs Attention Report
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][manual_approvals]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['manual_approvals'])?'checked':'') }}
                               id="manual_approvals">
                        <label class="form-check-label" for="manual_approvals">
                            Manual Approvals
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][policies]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['policies'])?'checked':'') }}
                               id="policies">
                        <label class="form-check-label" for="policies">
                            Policies
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][user_login_details]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['user_login_details'])?'checked':'') }}
                               id="User_Login_Data">
                        <label class="form-check-label" for="User_Login_Data">
                            User Login Data
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][user_policy_report]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['user_policy_report'])?'checked':'') }}
                               id="User_Policy_Report">
                        <label class="form-check-label" for="User_Policy_Report">
                            User Policy Report
                        </label>
                    </div>
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][added_devices]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['added_devices'])?'checked':'') }}
                               id="added_devices">
                        <label class="form-check-label" for="added_devices">
                            Added Devices
                        </label>
                    </div>
                     <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][connections]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['connections'])?'checked':'') }}
                               id="connections">
                        <label class="form-check-label" for="connections">
                            Connections
                        </label>
                    </div>
                    
                    <div class="form-check  form-check-inline mt-2 mb-2">
                        <input class="form-check-input" name="insert[permission][tagsremoval]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['tagsremoval'])?'checked':'') }}
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
                    <div class="form-check  form-check-inline mt-2 mb-2 d-none">
                        <input class="form-check-input" name="insert[permission][data_extract]" type="checkbox"
                               value="1"
                               {{ ( isset($permissions['data_extract'])?'checked':'') }}
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
                                       {{ (isset($organisations[$organisation->id]) ? 'checked' : '') }}
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