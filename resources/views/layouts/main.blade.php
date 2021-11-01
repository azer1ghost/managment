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
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
    <!-- Scripts -->
    <script src="{{ mix('assets/js/app.js') }}" ></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    @livewireScripts

    <!-- Alpine js and Spruce state management for it -->
    <script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@2.x.x/dist/spruce.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>

    @stack('scripts')
    @yield('scripts')

    <x-notify/>

    @auth
        <script>
            $(document).ready(function (){
                // request for location and store the info
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position){
                        const locationRoute = '{{route('set-location')}}';
                        $.ajax({
                            type: 'POST',
                            url: locationRoute,
                            data: { coordinates: {'latitude': position.coords.latitude, 'longitude': position.coords.longitude } },
                            success: function (){}
                        });
                    });
                } else {
                    console.log("Geolocation is not supported by this browser.");
                }

                // request for notification and store the info
                const messaging = firebase.messaging();
                const userTokens = @json(auth()->user()->deviceFcmTokens());
                Notification.requestPermission().then(function(result) {
                    if(result === "granted"){
                        messaging
                            .requestPermission()
                            .then(function () {
                                return messaging.getToken()
                            })
                            .then(function (response) {
                                if(!userTokens.includes(response)){
                                    $.ajax({
                                        url: '{{ route("store.fcm-token") }}',
                                        type: 'POST',
                                        data: {
                                            fcm_token: response
                                        },
                                        dataType: 'JSON',
                                        success: function (response){},
                                        error: function (error) {
                                            console.log(error);
                                        },
                                    });
                                }
                            })
                            .catch(function (error) {
                                alert(error);
                            });
                    }
                });

                messaging.onMessage(function (payload) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options).addEventListener('click', function(){
                        window.open(payload.notification.click_action, '_blank');
                    });;
                });

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
            });
        </script>
    @endauth
</body>
</html>
