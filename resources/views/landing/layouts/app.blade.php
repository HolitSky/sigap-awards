<!doctype html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <link rel="icon" type="image/svg+xml" href="{{ asset('sigap-assets/images/favicon.ico') }}">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Form Penilaian SIGAP Award 2025 - Penilaian Komponen Tata Kelola Informasi Geospasial Kehutanan">

        <!-- Open Graph Meta Tags for Social Media Sharing -->
        <meta property="og:title" content="SIGAP Award 2025 - Form Penilaian">
        <meta property="og:description" content="Form Penilaian SIGAP Award 2025 - Penilaian Komponen Tata Kelola Informasi Geospasial Kehutanan">
        <meta property="og:image" content="{{ asset('sigap-assets/images/favicon.ico') }}">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="SIGAP Award 2025">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary">
        <meta name="twitter:title" content="SIGAP Award 2025 - Form Penilaian">
        <meta name="twitter:description" content="Form Penilaian SIGAP Award 2025 - Penilaian Komponen Tata Kelola Informasi Geospasial Kehutanan">
        <meta name="twitter:image" content="{{ asset('sigap-assets/images/favicon.ico') }}">

        <!-- WhatsApp and other platforms -->
        <meta property="og:image:width" content="512">
        <meta property="og:image:height" content="512">
        <meta property="og:image:type" content="image/x-icon">

        <link rel="stylesheet" href="{{ asset('sigap-assets/css/main.css') }}">
        @stack('styles')
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

