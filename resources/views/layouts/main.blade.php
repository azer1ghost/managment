<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicons/favicon.ico')}}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">
    <!-- Google Fonts API -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Flags Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}" rel="stylesheet">

    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">

    @yield('style')
    @stack('style')
    @livewireStyles
</head>

@auth
    <x-notify-modal/>
@endauth

<body>
<div class="container-scroller">

    @if (auth()->check() && (request()->routeIs('account') || auth()->user()->hasVerifiedPhone()) && !request()->routeIs('welcome') && !request()->routeIs('documents.viewer'))
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}"><h6 class="m-0">Mobil Management</h6></a>
                <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{asset('assets/images/logo.svg')}}" alt="logo"/></a>
            </div>
            <x-navbar/>
        </nav>

        <div class="container-fluid page-body-wrapper">
{{--            <div class="theme-setting-wrapper">--}}
{{--                <div id="settings-trigger"><i class="fas fa-cog"></i></div>--}}
{{--                <div id="theme-settings" class="settings-panel">--}}
{{--                    <i class="fas fa-times settings-close"></i>--}}
{{--                    <p class="settings-heading">SIDEBAR SKINS</p>--}}
{{--                    <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>--}}
{{--                    <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>--}}
{{--                    <p class="settings-heading mt-2">HEADER SKINS</p>--}}
{{--                    <div class="color-tiles mx-0 px-4">--}}
{{--                        <div class="tiles success"></div>--}}
{{--                        <div class="tiles warning"></div>--}}
{{--                        <div class="tiles danger"></div>--}}
{{--                        <div class="tiles info"></div>--}}
{{--                        <div class="tiles dark"></div>--}}
{{--                        <div class="tiles default"></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <x-sidebar/>
            @endif

        <div class="p-3 w-100 main-content">
            @yield('content')
        </div>
    </div>
</div>

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>
    <!-- tinyMCE -->
    <script src="https://cdn.tiny.cloud/1/6hi4bok2utssc8368iz75o1mg2sma3bl46qf41q4i2ah6myx/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{asset('assets/js/tinyMCE/az.js')}}"></script>

    <!-- Scripts -->
    <script src="{{ mix('assets/js/app.js') }}" ></script>

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
                // Tiny MCE
                tinymce.init({
                    selector: '.tinyMCE',
                    readonly: {{ str_contains(url()->current(), '/create') ||
                str_contains(url()->current(), '/edit')
                    ? '0'
                    : '1' }},
                    height: 500,
                    language: '{{app()->getLocale()}}',
                    plugins: 'autosave paste directionality visualblocks visualchars fullscreen link table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars emoticons',
                    menubar: 'edit view insert format table help',
                    toolbar: 'restoredraft undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen preview | link anchor | ltr rtl',
                    autosave_ask_before_unload: false,
                    toolbar_sticky: true,
                    toolbar_mode: 'sliding',
                    quickbars_insert_toolbar: '',
                    quickbars_selection_toolbar: '',
                });

                @if(app()->environment('production'))
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
                        });
                    });
                @endif
            });
        </script>
    @endauth
</body>
</html>
