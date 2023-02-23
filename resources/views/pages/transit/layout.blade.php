<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Robots -->
    <meta name="robots" content="nofollow,noindex">
    <meta http-equiv="Content-Security-Policy" content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicons/favicon.ico')}}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">
    <!-- Google Fonts API -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}" rel="stylesheet">

    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">

    @yield('style')
    @stack('style')

</head>

<body>
<div class="container">
        <div class="text-center py-5">
            <img src="{{asset('assets/images/logomb.png')}}" alt="" style="max-width: 100%">
            @yield('content')
        </div>
</div>


<!-- Scripts -->
<script src="{{ mix('assets/js/app.js') }}" ></script>


@stack('scripts')
@yield('scripts')

</body>
</html>
