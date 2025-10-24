<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Dashboard Peserta SIGAP Award 2025" />
    <meta name="author" content="SIGAP Award 2025" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ url('/') }}/">
    
    <title>@yield('title', 'Peserta') | SIGAP Award 2025</title>
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ url('sigap-assets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ url('dashboard-assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ url('dashboard-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('dashboard-assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- SweetAlert2 -->
    <link href="{{ url('dashboard-assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    
    @stack('styles')
</head>

<body>
    <div class="account-pages">
        @yield('content')
    </div>
    <!-- end account-pages -->

    <!-- JAVASCRIPT -->
    <script src="{{ url('dashboard-assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('dashboard-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('dashboard-assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ url('dashboard-assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ url('dashboard-assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ url('dashboard-assets/js/plugin.js') }}"></script>
    
    <!-- App js -->
    <script src="{{ url('dashboard-assets/js/app.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="{{ url('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
