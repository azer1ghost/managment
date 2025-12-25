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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');
        
        * {
            font-family: 'Poppins', 'Inter', sans-serif;
        }
        
        body {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            padding: 20px 0;
            position: relative;
            overflow-x: hidden;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Floating Background Elements */
        body::before {
            content: '';
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -250px;
            left: -250px;
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }
        
        body::after {
            content: '';
            position: fixed;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -200px;
            right: -200px;
            animation: float 25s ease-in-out infinite reverse;
            z-index: 0;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .transit-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .transit-logo {
            max-width: 250px;
            margin-bottom: 30px;
            filter: drop-shadow(0 10px 30px rgba(0,0,0,0.3));
            animation: logoFloat 3s ease-in-out infinite;
            transition: transform 0.3s ease;
        }
        
        .transit-logo:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .transit-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3), 
                        0 0 0 1px rgba(255,255,255,0.2) inset;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            animation: cardSlideIn 0.6s ease-out;
        }
        
        @keyframes cardSlideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .transit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }
        
        .transit-card:hover::before {
            left: 100%;
        }
        
        .transit-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 30px 80px rgba(0,0,0,0.4),
                        0 0 0 1px rgba(255,255,255,0.3) inset;
        }
        
        .nav-pills {
            background: rgba(255,255,255,0.1);
            border-radius: 15px;
            padding: 5px;
            backdrop-filter: blur(10px);
        }
        
        .nav-pills .nav-link {
            border-radius: 12px;
            margin: 0 3px;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            position: relative;
            overflow: hidden;
            color: #333;
        }
        
        .nav-pills .nav-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .nav-pills .nav-link:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5),
                        0 0 0 3px rgba(255,255,255,0.3) inset;
            color: white;
            transform: scale(1.05);
        }
        
        .nav-pills .nav-link:not(.active):hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            padding: 15px 20px;
            transition: all 0.3s ease;
            background: rgba(255,255,255,0.9);
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2),
                        0 10px 30px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
            background: white;
        }
        
        .btn {
            border-radius: 15px;
            padding: 15px 35px;
            font-weight: 600;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 14px;
        }
        
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:hover::before {
            width: 400px;
            height: 400px;
        }
        
        .btn span, .btn i {
            position: relative;
            z-index: 1;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
        }
        
        .btn-primary:active {
            transform: translateY(-2px) scale(1.02);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            box-shadow: 0 10px 30px rgba(245, 87, 108, 0.4);
        }
        
        .btn-warning:hover {
            transform: translateY(-5px) scale(1.05);
            box-shadow: 0 15px 40px rgba(245, 87, 108, 0.6);
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
            padding: 30px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border: 3px dashed rgba(102, 126, 234, 0.5);
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
        }
        
        .file-upload-label::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
            transform: rotate(45deg);
            transition: all 0.6s;
        }
        
        .file-upload-label:hover::before {
            animation: shine 1.5s infinite;
        }
        
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .file-upload-label:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
            border-color: #667eea;
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3);
        }
        
        .file-upload-label.has-file {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(40, 167, 69, 0.1) 100%);
            border-color: #28a745;
            border-style: solid;
        }
        
        /* Pulse Animation for Important Elements */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .pulse-animation {
            animation: pulse 2s ease-in-out infinite;
        }
        
        /* Glow Effect */
        .glow {
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.5),
                        0 0 40px rgba(102, 126, 234, 0.3),
                        0 0 60px rgba(102, 126, 234, 0.1);
        }
        
        .language-selector-wrapper {
            position: relative;
            z-index: 10;
        }
        
        .language-selector {
            display: flex;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .lang-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        
        .lang-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.4s, height 0.4s;
        }
        
        .lang-btn:hover::before {
            width: 100px;
            height: 100px;
        }
        
        .lang-btn:hover {
            transform: scale(1.15) translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .lang-btn.active {
            background: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.5) inset,
                        0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .lang-btn .flag-icon {
            font-size: 24px;
            position: relative;
            z-index: 1;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }
        
        @media (max-width: 768px) {
            .transit-logo {
                max-width: 180px;
            }
            .transit-card {
                border-radius: 20px;
            }
            body::before, body::after {
                display: none;
            }
            .language-selector {
                padding: 6px;
                gap: 6px;
            }
            .lang-btn {
                width: 35px;
                height: 35px;
            }
            .lang-btn .flag-icon {
                font-size: 20px;
            }
        }
    </style>

    @yield('style')
    @stack('style')
</head>

<body>
<div class="transit-container">
    <div class="text-center py-4">
        <div class="d-flex justify-content-end mb-3 language-selector-wrapper">
            <div class="language-selector">
                @foreach(config('app.locales') as $locale => $name)
                    <a href="{{ route('locale', $locale) }}" 
                       class="lang-btn @if(app()->getLocale() == $locale) active @endif"
                       title="{{ $name }}">
                        @if($locale == 'az')
                            <span class="flag-icon flag-icon-az"></span>
                        @elseif($locale == 'en')
                            <span class="flag-icon flag-icon-gb"></span>
                        @elseif($locale == 'ru')
                            <span class="flag-icon flag-icon-ru"></span>
                        @elseif($locale == 'tr')
                            <span class="flag-icon flag-icon-tr"></span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
        <img src="{{asset('assets/images/logomb.png')}}" alt="Logo" class="transit-logo">
        @yield('content')
    </div>
</div>

<script src="{{ mix('assets/js/app.js') }}"></script>
@stack('scripts')
@yield('scripts')
</body>
</html>
