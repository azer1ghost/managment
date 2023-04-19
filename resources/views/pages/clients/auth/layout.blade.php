<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex justify-content-center">
    <a class="navbar-brand text-center" href="#">Mobil Management</a>


</nav>

<!-- İçerik -->
<div class="container my-5">
    <h1>Welcome!</h1>
    <p>Bla bla bla.</p>
</div>
@yield('content')


<script src="{{asset('assets/js/app.js')}}"></script>
</body>
</html>