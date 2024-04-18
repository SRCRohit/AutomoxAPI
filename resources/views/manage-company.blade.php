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
        <div class="col-md-3">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Add Company</h4>
                </div>
                <div class="panel-body">
                    <form id="add-company" method="post" action="{{ route('managecompany') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="type" value="insert"/>
                        <div class="form-group mb-3">
                            <input autocomplete="off" required type="text" class="form-control" name="org_id"
                                   placeholder="Organisation ID"/>
                        </div>
                        <div class="form-group mb-3">
                            <input autocomplete="off" required type="text" class="form-control" name="api" placeholder="API Key"/>
                        </div>
                        <div class="form-group mb-3">
                            <input autocomplete="off" required type="text" class="form-control" name="user_id"
                                   placeholder="User ID"/>
                        </div>
                        <div class="form-group mb-3">
                            <input autocomplete="off" required type="text" class="form-control" name="name" placeholder="Name"/>
                        </div>
                        <div class="form-group">
                            <button onclick="validateForm(event)"
                                    type="submit"
                                    class="btn
                            btn-primary btn-sm"><i
                                        class="fa
                            fa-save me-1
                            text-white"></i> Save Company</button>
                            <button onclick="findUserID()" type="button" class="btn btn-yellow btn-sm"><i class="fa
                            fa-search me-1
                            text-white"></i> Get User ID</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Company List</h4>
                </div>
                <div class="panel-body">
                    <table id="data-table-default" class="table table-striped table-bordered align-middle">
                        <thead>
                            <tr>
                                <th data-orderable="false">#</th>
                                <th>COMPANY NAME</th>
                                <th data-orderable="false">API KEY</th>
                                <th data-orderable="false" class="text-center">CREATED</th>
                                <th data-orderable="false" class="text-center">EXPIRY</th>
                                <th data-orderable="false" class="text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $devices = \App\Models\Company::all();
                            ?>
                            @foreach($devices as $device)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td id="name_{{ $device->id }}">{{ $device->name }}</td>
                                    <td>
                                        @for($loop = 1; $loop <= 15; $loop++)
                                        <i class="fa fa-circle fa-xs"></i>
                                        @endfor
                                    </td>
                                    <td class="text-center">{{ $device->api_created_at }}</td>
                                    <td class="text-center">{{ $device->api_expires_at }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-xs" onclick="$('#deleteCompany_{{ $device->id }}').submit()"><i class="fa fa-trash me-1"></i> Delete</button>
                                        <form id="deleteCompany_{{ $device->id }}" method="post" action="{{ route('managecompany') }}">
                                            @csrf
                                            <input type="hidden" name="type" value="delete"/>
                                            <input type="hidden" name="id" value="{{ $device->id }}"/>
                                            
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
        $('#data-table-default').DataTable({
            responsive: true,
            dom: '<"row"<"col-sm-5"B><"col-sm-7"fr>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
            buttons: [
                { extend: 'csv', className: 'btn-sm' },
                { extend: 'excel', className: 'btn-sm' },
                { extend: 'pdf', className: 'btn-sm' }
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