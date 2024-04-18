<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet"> 
    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet" />
    <style>
        body{
            font-family: 'Roboto', sans-serif;
        }
        .alert-danger{
            color: #fff;
            background-color: #f00;
        }
        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
            -webkit-animation: blink-animation 1s steps(5, start) infinite;
        }
        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
        @-webkit-keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }
    </style>
    @yield('css')
</head>

<body>

    <div id="loader" class="app-loader">
        <span class="spinner"></span>
    </div>


    <div id="app" class="app app-header-fixed app-sidebar-fixed">

        <div id="header" class="app-header app-header-inverse">

            <div class="navbar-header">
                <a href="/" class="navbar-brand">
                    <img src="{{ asset('assets/img/logo/logo.png') }}" class="img-fluid">
                </a>
                <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>


            <div class="navbar-nav">

                <div class="navbar-item navbar-user dropdown">
                    <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                        <img src="{{ asset('assets/img/user/user-13.jpg') }}" alt="" />
                        <span>
                            <span class="d-none d-md-inline">
                                @if(auth()->check())
                                    {{ Auth::user()->name }}
                                @else
                                    Test Account
                                @endif
                            </span>
                            <b class="caret"></b>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end me-1">
                        <a href="{{ route('profile') }}" class="dropdown-item">Edit Profile</a>
                        <a href="javascript:;" onclick="document.getElementById('logout-form').submit();" class="dropdown-item">Log Out</a>
                    </div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    
                </div>
            </div>

        </div>

        @if(auth()->check())

        @include('inc.sidebar')

        @endif
        
        <div class="app-sidebar-bg"></div>
        <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>


        <div id="content" class="app-content">
            @php
                $companies = \App\Models\Company::all();
            @endphp
            @foreach($companies as $company)
                @php
                    $date1 = new DateTime(date('Y-m-d'));
                    $date2 = new DateTime($company->api_expires_at);
                    $days  = $date2->diff($date1)->format('%a');
                @endphp
                @if($days <= 15)
                    <div class="alert alert-danger fade show">
                        <h5 class="m-0 p-0 blink">The API key for {{ $company->name }} is going to expire in {{ $days
                        }} {{
                        ($days==1?'day':'days') }}.</h5>
                    </div>
                @endif
            @endforeach
            @yield('content')
        </div>

    </div>


    <script src="{{ asset('assets/js/vendor.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app.min.js') }}" type="text/javascript"></script>
    <script>
        function showLoader(){
            $('#loader').removeClass('loaded');
        }

        function hideLoader(){
            $('#loader').addClass('loaded');
        }
        var search = window.location.search;
        var hrefname = window.location.href;
        hrefname = hrefname.replace(search, '');
        if('dashboard' == '{{ Route::currentRouteName() }}'){
            hrefname = hrefname.replace(/.$/,"");
        }
        $('[href="'+hrefname+'"]').parent('.menu-item').addClass('active');
        setTimeout(function () {
            $('div.alert').hide();
        }, 10000)
    </script>
    @yield('javascript')
</body>

</html>