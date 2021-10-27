<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- Styles -->
    <link href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}" rel="stylesheet">
    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">
    @yield('style')

    @livewireStyles

    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>
</head>
<body class="custom-scrollbar">
    <div class="custom-wrapper">
        @if (auth()->check() && (request()->routeIs('account') || auth()->user()->hasVerifiedPhone()) && !request()->routeIs('welcome'))
            <div class="section">
                <div class="top_navbar d-flex justify-content-between align-items-center">
                    <div style="position: relative;top: 2px">
                        <button class="hamburger hamburger--slider" type="button">
                          <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                          </span>
                        </button>
                    </div>
                    @include('components.navbar')
                </div>
            </div>
            <div class="sidebar custom-scrollbar">
                <div class="profile-container">
                    <img src="{{image(auth()->user()->getAttribute('avatar'))}}" alt="profile_picture">
                    <h4>{{auth()->user()->getAttribute('fullname')}}</h4>
                    <p>{{auth()->user()->getRelationValue('compartment')->getAttribute('name')}}</p>
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

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/4.8.1/firebase.js"></script>
    <!-- Scripts -->
    <script src="{{ mix('assets/js/app.js') }}" ></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    @livewireScripts


    @stack('scripts')

    @yield('scripts')

    <x-notify/>

    <script>
        $(document).ready(function (){
            $(function () {
                $('[data-toggle="tooltip"]').tooltip({
                    content: function(){
                        return $(this).attr('title');
                    }
                })
            });

            const body = $('body');
            const hamburger = document.querySelector(".hamburger");

            body.addClass(sidebarStatus(checkWindowWidth()));

            if(!body.hasClass('active')){
                hamburger.classList.add('is-active');
            }

            hamburger.addEventListener("click", function(){
                if(body.hasClass('active')){
                    hamburger.classList.add('is-active');
                    body.removeClass('active');
                    body.addClass('inactive');
                    localStorage.setItem("navbar", 'inactive');
                }else{
                    hamburger.classList.remove('is-active');
                    body.removeClass('inactive');
                    body.addClass('active');
                    localStorage.setItem("navbar", 'active');
                }
            });

            function checkWindowWidth(){
                if($(window).width() < 576){
                    return 'active';
                }else{
                    return 'inactive';
                }
            }

            function sidebarStatus(status){
                if($(window).width() < 576){
                    return 'active';
                }
                if(localStorage.getItem("navbar") !== null){
                    return localStorage.getItem("navbar");
                }else{
                    localStorage.setItem("navbar", status);
                    return localStorage.getItem("navbar");
                }
            }

            $(document).ready(function() {
                $(body).trigger('click');
            });

            const notification = new Audio('{{asset('assets/audio/notify/notify.wav')}}');
            Livewire.on('newNotifications', function () {
                notification.play();
            })
        });
    </script>
    <script>
        const dbRef = firebase.database().ref();
        const usersRef = dbRef.child('users');

        usersRef.on("child_added", snap => {
            console.log(snap.val());
        });
    </script>
</body>
</html>
