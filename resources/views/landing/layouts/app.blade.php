<!doctype html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/svg+xml" href="{{ asset('sigap-assets/images/favicon.ico') }}">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Sigap Award">
        <link rel="stylesheet" href="{{ asset('sigap-assets/css/main.css') }}">
        <style>

        </style>
        <title>Sigap Award 2025</title>
        </head>
    <body>
        @include('landing.layouts.nav')
        <main id="primary">
            @yield('content')
        </main>
        @include('landing.layouts.footer')

        <script rel="preload" src="{{ asset('sigap-assets/static/pace.min3713.js') }}"></script>
        <script>
            window.LAUNCH_DATES = {
                startDate: @json(optional($launchStart)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00') ?? 'October 1, 2025 00:00:00'),
                finishDate: @json(optional($launchFinish)->locale('en')->isoFormat('MMMM D, YYYY 00:00:00') ?? 'October 10, 2025 00:00:00')
            };
        </script>
        <script rel="preload" src="{{ asset('sigap-assets/js/main.js') }}"></script>
        {{-- <script rel="preload" src="{{ asset('sigap-assets/js/app.min3713.js') }}"></script> --}}

        @stack('scripts')
    </body>
</html>

