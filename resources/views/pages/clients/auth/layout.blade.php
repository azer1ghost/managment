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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Language Selector */
        .language-selector-wrapper-fixed {
            position: fixed !important;
            top: 20px !important;
            right: 20px !important;
            z-index: 999999 !important;
            padding: 0 !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
        
        .language-selector {
            min-width: 250px !important;
            max-width: 280px !important;
            margin-left: auto !important;
            display: block !important;
        }
        
        .language-select {
            background: #ffffff !important;
            border: 3px solid #667eea !important;
            border-radius: 15px !important;
            padding: 16px 55px 16px 20px !important;
            font-weight: 700 !important;
            font-size: 17px !important;
            color: #333 !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 10px 35px rgba(102, 126, 234, 0.5), 
                        0 0 0 3px rgba(102, 126, 234, 0.2) inset !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 12 12'%3E%3Cpath fill='%23667eea' d='M6 9L1 4h10z'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 20px center !important;
            background-size: 16px !important;
            width: 100% !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .language-select:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 15px 45px rgba(102, 126, 234, 0.6), 
                        0 0 0 3px rgba(102, 126, 234, 0.3) inset !important;
        }
        
        /* Modern File Upload */
        .file-upload-wrapper {
            position: relative !important;
            overflow: visible !important;
        }
        
        .file-upload-wrapper input[type=file] {
            position: absolute !important;
            opacity: 0 !important;
            width: 100% !important;
            height: 100% !important;
            cursor: pointer !important;
            z-index: 10 !important;
            top: 0 !important;
            left: 0 !important;
        }
        
        .file-upload-label {
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 40px 20px !important;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15) 0%, rgba(118, 75, 162, 0.15) 100%) !important;
            border: 3px dashed rgba(102, 126, 234, 0.6) !important;
            border-radius: 20px !important;
            text-align: center !important;
            cursor: pointer !important;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) !important;
            position: relative !important;
            overflow: hidden !important;
            min-height: 180px !important;
        }
        
        .file-upload-label:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.25) 0%, rgba(118, 75, 162, 0.25) 100%) !important;
            border-color: #667eea !important;
            transform: scale(1.05) !important;
            box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3) !important;
        }
        
        .file-upload-label.has-file {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.2) 0%, rgba(40, 167, 69, 0.1) 100%) !important;
            border-color: #28a745 !important;
            border-style: solid !important;
        }
        
        /* Hide old custom-file styles */
        .custom-file,
        .custom-file-input,
        .custom-file-label {
            display: none !important;
        }
        
        .input-group-prepend {
            display: none !important;
        }
    </style>

</head>
<body>
<!-- Language Selector -->
<div class="language-selector-wrapper-fixed">
    <div class="language-selector">
        <select id="languageSelect" class="form-select language-select" onchange="changeLanguage(this.value)" title="Select Language">
            @foreach(config('app.locales') as $locale => $name)
                <option value="{{ $locale }}" 
                        @if(app()->getLocale() == $locale) selected @endif>
                    @if($locale == 'az')
                        🇦🇿 Azərbaycan
                    @elseif($locale == 'en')
                        🇬🇧 English
                    @elseif($locale == 'ru')
                        🇷🇺 Русский
                    @elseif($locale == 'tr')
                        🇹🇷 Türkçe
                    @endif
                </option>
            @endforeach
        </select>
    </div>
</div>

<script>
function changeLanguage(locale) {
    const select = document.getElementById('languageSelect');
    select.disabled = true;
    select.style.opacity = '0.6';
    window.location.href = '{{ url("/") }}/locale/' + locale;
}
</script>
<nav class="navbar navbar-light bg-light d-flex justify-content-center">
    <a class="navbar-brand text-center" href="#"> <img src="{{asset('assets/images/logomb.png')}}" alt="" style="max-width: 100%"></a>
</nav>


<div class="container my-5 mr-5">
    <h1>Client Account</h1>
    <h2>Welcome!</h2>
</div>
@yield('content')


<script src="{{asset('assets/js/app.js')}}"></script>

@stack('scripts')
@yield('scripts')
</body>
</html>