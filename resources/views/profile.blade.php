@extends('layouts.app')

@section('css')
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
    <div class="row mt-5">
        <div class="col-md-3 mx-auto">
            <div class="panel panel-inverse">
                <div class="panel-heading">
                    <h4 class="panel-title">Edit Profile</h4>
                </div>
                <div class="panel-body">
                    <form id="add-company" method="post" action="{{ route('profile') }}" class="form-horizontal">
                        @csrf
                        <input type="hidden" name="method_type" value="update"/>
                        <input type="hidden" name="id" value="{{ auth()->user()->id }}"/>
                        <div class="form-group mb-3">
                            <input autocomplete="off" required type="text" class="form-control" name="insert[name]"
                                   placeholder="Full Name" value="{{ auth()->user()->name }}"/>
                        </div>
                        <div class="form-group mb-3">
                            <input  autocomplete="nope" required type="email" class="form-control" name="insert[email]"
                                   placeholder="Email Address" value="{{ auth()->user()->email }}"/>
                        </div>
                        <div class="form-group mb-3">
                            <input autocomplete="new-password" type="password" class="form-control"
                                   name="insert[password]"
                                   placeholder="Password" id="password"/>
                        </div>
                        <div class="form-group mb-3">
                            <input autocomplete="off" id="confirm_password" type="password" class="form-control"
                                   placeholder="Confirm Password"/>
                        </div>
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
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script type="text/javascript">

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

        function validateForm(e) {
            e.preventDefault();
            if($('#add-company').valid()){
                let password = $('#password').val();
                let confirm_password = $('#confirm_password').val();
                if(password){
                    if(password.length < 8){
                        alert('Password should be greater then 8 letters');
                        return false;
                    }
                    if(password != confirm_password){
                        alert('Password and confirm password not match');
                        return false;
                    }
                }
                showLoader();
                $('#add-company').submit();
            }
        }
    </script>
@endsection