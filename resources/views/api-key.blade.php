<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Laravel') }}</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

    <link href="{{ asset('assets/css/vendor.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/default/app.min.css') }}" rel="stylesheet">


</head>

<body class='pace-top'>


    <div id="app" class="app">

        <div class="coming-soon">

            <div class="coming-soon-header">
                <div class="bg-cover"></div>
                <div class="brand">
                    <img src="{{ asset('assets/img/logo/logo.png') }}">
                </div>
            </div>


            <div class="coming-soon-content">
                <form method='post' action='{{ route('submitapi') }}'>
                    @csrf
                    <div class="desc" style="margin-bottom : 30px">
                        <h3>
                            Enter an API Key to access for user : {{ auth()->user()->email }}
                        </h3>
                    </div>
                    <div class="input-group input-group-lg mx-auto mb-2">
                        <span class="input-group-text border-0 bg-light"><i class="fa fa-key"></i></span>
                        <input required type="text" class="form-control fs-13px border-0 shadow-none ps-0 bg-light" placeholder="API KEY" name="api"/>
                        <button type="submit" class="btn fs-13px btn-dark">Submit</button>
                    </div>
                </form>
            </div>

        </div>

    </div>


    <script src="{{ asset('assets/js/vendor.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/app.min.js') }}" type="text/javascript"></script>

</body>

</html>