<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Styles -->
    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}" rel="stylesheet">

    @yield('style')

    @livewireStyles
</head>
<body>
{{--    @if (Request::root() === 'http://10.10.11.8') {--}}
{{--    <div class="position-sticky">--}}
{{--        <a href="{{route('host.bat')}}" class="btn btn-outline-success"><i class="fas fa-download"></i> Download host file</a>--}}
{{--    </div>--}}
{{--    @endif--}}
    <div class="custom-wrapper">
        @if (auth()->check() && auth()->user()->hasVerifiedPhone())
            <div class="section">
                <div class="top_navbar d-flex justify-content-between">
                    <div class="hamburger">
                        <a href="#">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>
                    @include('components.navbar')
                </div>
            </div>
            <div class="sidebar">
                <div class="profile">
                    <img src="{{image(auth()->user()->getAttribute('avatar'))}}" alt="profile_picture">
                    <h4>{{auth()->user()->getAttribute('fullname')}}</h4>
                    <p>{{auth()->user()->getRelationValue('position')->getAttribute('name')}}</p>
                </div>
                <x-sidebar />
            </div>
        @endif
            <main class="py-4">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
    </div>

    <!-- Scripts -->
    <script src="{{ mix('assets/js/app.js') }}" ></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    @livewireScripts

    @yield('scripts')

    <x-notify/>

    <script>

        const body = $('body');

        body.addClass(sidebarStatus(checkWindowWidth()));

        const hamburger = document.querySelector(".hamburger");

        hamburger.addEventListener("click", function(){
            if(body.hasClass('active')){
                body.removeClass('active');
                body.addClass('inactive');
                localStorage.setItem("navbar", 'inactive');
            }else{
                body.removeClass('inactive');
                body.addClass('active');
                localStorage.setItem("navbar", 'active');
            }
        });

        window.addEventListener('resize', function(event) {
            checkWindowWidth();
        }, true);

        function checkWindowWidth(){
            if($(window).width() < 576){
                return 'active';
            }else{
                return 'inactive';
            }
        }

        function sidebarStatus(status){
            if(localStorage.getItem("navbar") !== null){
                return localStorage.getItem("navbar");
            }else{
                localStorage.setItem("navbar", status);
                return localStorage.getItem("navbar");
            }
        }

    </script>
</body>
</html>
