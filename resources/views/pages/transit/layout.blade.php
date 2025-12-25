<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="nofollow,noindex">
    <meta http-equiv="Content-Security-Policy" content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicons/favicon.ico')}}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">

    <!-- Google Fonts API -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}" rel="stylesheet">
    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', 'Nunito', sans-serif;
        }
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }
        .transit-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .transit-logo {
            max-width: 250px;
            margin-bottom: 30px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
        }
        .transit-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .transit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }
        .nav-pills .nav-link {
            border-radius: 10px;
            margin: 0 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
        }
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(245, 87, 108, 0.4);
        }
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
        }
        .file-upload-wrapper input[type=file] {
            position: absolute;
            left: -9999px;
        }
        .file-upload-label {
            display: block;
            padding: 15px;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-upload-label:hover {
            background: #e9ecef;
            border-color: #667eea;
        }
        .file-upload-label.has-file {
            background: #d4edda;
            border-color: #28a745;
        }
        @media (max-width: 768px) {
            .transit-logo {
                max-width: 180px;
            }
            .transit-card {
                border-radius: 15px;
            }
        }
    </style>

    @yield('style')
    @stack('style')
</head>

<body>
<div class="transit-container">
    <div class="text-center py-4">
        <img src="{{asset('assets/images/logomb.png')}}" alt="Logo" class="transit-logo">
        @yield('content')
    </div>
</div>

<script src="{{ mix('assets/js/app.js') }}"></script>
@stack('scripts')
@yield('scripts')
</body>
</html>
