<!doctype html>
<html lang="en">

<head>

        <meta charset="utf-8" />
        <title>Login | SIGAP Award 2025</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('sigap-assets/images/favicon.ico') }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('dashboard-assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('dashboard-assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('dashboard-assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <link href="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App js -->
        <script src="{{ asset('dashboard-assets/js/plugin.js') }}"></script>


    </head>

    <body>
        <div class="account-pages">
            @yield('content')
        </div>
        <!-- end account-pages -->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('dashboard-assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('dashboard-assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset('dashboard-assets/js/app.js') }}"></script>


        <script src="{{ asset('dashboard-assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        @stack('scripts')
    </body>

</html>
