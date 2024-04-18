<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>403 Unauthorized | SRC Cyber Solutions LLP</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />

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
    </style>
</head>
<body class="pace-top">
<div id="loader" class="app-loader">
    <span class="spinner"></span>
</div>

<div id="app" class="app">
    <div class="error">
        <div class="error-code">403</div>
        <div class="error-content">
            <div class="error-message">Unauthorized</div>
            <div class="error-desc mb-4">
                You do not have permission to perform this action.
            </div>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-success px-3">Go Dashboard</a>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/vendor.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/app.min.js') }}" type="text/javascript"></script>

</body>
</html>
