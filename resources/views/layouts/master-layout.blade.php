<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="description" content="Admin Login">
    <meta name="author" content="Techie squad">
    <meta name="keywords" content="techie squad, techie squad pvt Ltd">
    <!-- Favicon -->
    <link rel="icon" href="{{asset('backend/assets/img/brand2/favicon.ico')}}" type="image/x-icon"/>
    <!-- Title -->
    <title>@yield('title','Login')</title>
    <!---Fontawesome css-->
    <link href="{{asset('backend/assets/plugins/fontawesome-free/css/all.min.css')}}" rel="stylesheet">
    <!---Ionicons css-->
    <link href="{{asset('backend/assets/plugins/ionicons/css/ionicons.min.css')}}" rel="stylesheet">
    <!---Typicons css-->
    <link href="{{asset('backend/assets/plugins/typicons.font/typicons.css')}}" rel="stylesheet">
    <!---Feather css-->
    <link href="{{asset('backend/assets/plugins/feather/feather.css')}}" rel="stylesheet">
    <!---Falg-icons css-->
    <link href="{{asset('backend/assets/plugins/flag-icon-css/css/flag-icon.min.css')}}" rel="stylesheet">
    <!---Style css-->
    <link href="{{asset('backend/assets/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/css/custom-style.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/css/skins.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/css/dark-style.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/css/custom-dark-style.css')}}" rel="stylesheet">

    <script src="{{asset('backend/assets/plugins/jquery/jquery.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('backend/my-assets/jquery-confirm-v3.3.4/dist/jquery-confirm.min.css')}}">
    @yield('extra-css')
</head>
<body class="main-body">
<!-- Loader -->
<div id="global-loader">
    <img src="{{asset('backend/assets/img/loader.svg')}}" class="loader-img" alt="Loader">
</div>
<!-- End Loader -->
<!-- Page -->
<div class="page main-signin-wrapper">


    @yield('content')


</div>
<!-- Bootstrap js-->
<script src="{{asset('backend/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- Ionicons js-->
<script src="{{asset('backend/assets/plugins/ionicons/ionicons.js')}}"></script>
<!-- Rating js-->
<script src="{{asset('backend/assets/plugins/rating/jquery.rating-stars.js')}}"></script>
<!-- Custom js-->
<script src="{{asset('backend/assets/js/custom.js')}}"></script>
<script src="{{asset('backend/my-assets/jquery-confirm-v3.3.4/dist/jquery-confirm.min.js')}}"></script>
@yield('extra-js')
</body>
</html>
