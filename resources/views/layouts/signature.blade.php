<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome.pro.min.css') }}" rel="stylesheet">


    <style>
        body{
            background-color: #f6f6f6;
            background-opacity: 1;
            background-image: url("{{asset('images/diamond.svg')}}");
            background-repeat: no-repeat;
            background-size: 1000px;
            background-position-x: -25vw;
            background-position-y: -45vh;

            animation: fadeInAnimation ease 1s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }
        @keyframes fadeInAnimation {
            0% {
                background-position-x: -100vh;
            }
            100% {
                background-position-x: -25vw;
            }
        }
        .diamond{
            position: absolute;
            right: -300px;
            bottom: -250px;
        }


    </style>
</head>
<body class="vh-100 overflow-hidden">
    @yield('content')
    <div class="diamond" >
        <x-diamond class="animate__animated animate__fadeInUp" width="900px" color="rgba(62, 132, 58, 0.62)"></x-diamond>
    </div>
</body>
</html>
