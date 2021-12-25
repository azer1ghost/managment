<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{config('app.name')}} Coming Soon</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Raleway', sans-serif;
            color: white;
            font-size: 25px;
        }

        .bgimg {
            background-image: url({{asset('assets/images/under_construction.gif')}});
            filter: blur(8px);
            -webkit-filter: blur(8px);
            background-position: center;
            background-size: cover;
            position: absolute;
            height: 100vh;
            width: 100vw;
        }

        .topleft {
            position: absolute;
            top: 0;
            left: 16px;
        }

        .bottomleft {
            position: absolute;
            bottom: 0;
            left: 16px;
        }

        .middle {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        hr {
            margin: auto;
            width: 40%;
        }
        .overlay{
            position: absolute;
            background-color: rgba(12, 84, 96, 0.8);
            height: 100vh;
            width: 100vw;
        }
    </style>
</head>
<body>
<div class="bgimg"> </div>
<div class="overlay"> </div>
<div class="topleft">
    {{--    <img style="margin: 5px" src="{{asset('assets/images/logo.svg')}}" alt="">--}}
</div>
<div class="middle">
{{--    <h1>COMING SOON</h1>--}}
    <h2>@yield('error')</h2>
    <p>@yield('content')</p>
    <hr>
    <p>{{\Carbon\Carbon::parse('25.12.2021')->locale('en')->diffForHumans()}}</p>
</div>
<div class="bottomleft">
    <h2>MobilManagement</h2>
</div>
</body>
</html>

