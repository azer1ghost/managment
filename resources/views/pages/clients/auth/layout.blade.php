<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}">

</head>
<body>
<nav class="navbar navbar-light bg-light d-flex justify-content-center">
    <a class="navbar-brand text-center" href="#"> <img src="{{asset('assets/images/logomb.png')}}" alt="" style="max-width: 100%"></a>
</nav>


<div class="container my-5 mr-5">
    <h1>Müştəri Kabineti</h1>
    <h2>Xoş Gəlmişsiniz!</h2>
</div>
@yield('content')


<script src="{{asset('assets/js/app.js')}}"></script>

@stack('scripts')
@yield('scripts')
</body>
</html>