<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" ></script>


    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.css">
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/fontawesome.pro.min.css') }}" rel="stylesheet">

    @yield('style')
    <style>
        @font-face{
            font-family: "Sequel";
            src: url("{{asset('fonts/Sequel.ttf')}}") format("truetype");
        }

        body{
            background-color: #f6f6f6;
        }

        .diamond{
            position: absolute;
            height: 90vh;
            width: 100%;
            overflow-x: hidden;
            background-repeat: no-repeat;
            background-size: 900px;
            z-index: -100;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        .diamond-blue{
            left: 0;
            top: 0;
            background-image: url("{{asset('images/diamond-blue.png')}}");
            background-position-x: -25vw;
            background-position-y: 0;
            animation: blueAnime ease 1s;
        }

        @keyframes blueAnime {
            0% {
                background-position-x: -50vw;
            }
            100% {
                background-position-x: -25vw;
            }
        }

        .diamond-green{
            right: 0;
            top: 0;
            background-image: url("{{asset('images/diamond-green.png')}}");
            background-position-x: 77vw;
            background-position-y: 0;
            animation: greenAnime ease 1s;
        }

        @keyframes greenAnime {
            0% {
                background-position-x: 100vw;
            }
            100% {
                background-position-x: 77vw;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        @include('components.navbar')
        <main class="py-4">
            @yield('content')
        </main>
        <span class="diamond diamond-blue"></span>
        <span class="diamond diamond-green"></span>
    </div>

    @yield('scripts')
    <script>
        @if(session()->has('notify'))
            $.notify("{{session()->get('notify')['message']}}", "{{session()->get('notify')['type']}}", { style: 'bootstrap' });
        @endif
    </script>
</body>
</html>
