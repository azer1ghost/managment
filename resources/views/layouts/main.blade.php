<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Robots -->
    <meta name="robots" content="nofollow,noindex">
    <meta http-equiv="Content-Security-Policy" content="default-src *;
   img-src * 'self' data: https:; script-src 'self' 'unsafe-inline' 'unsafe-eval' *;
   style-src  'self' 'unsafe-inline' *">
    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="{{asset('assets/images/favicons/favicon.ico')}}"/>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicons/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" type="image/png" sizes="180x180" href="{{ asset('assets/images/favicons/apple-touch-icon.png') }}">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.semanticui.min.css" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet"/>
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" rel="stylesheet"/>


    <!-- Google Fonts API -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Flags Icons -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
{{--    <link href="{{asset('assets/css/summernote/summernote.css') }}" rel="stylesheet">--}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Styles -->
    <link href="{{ asset('assets/fonts/fontawesome.pro.min.css') }}" rel="stylesheet">

    <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('assets/css/jquery.orgchart.css')}}">

    @yield('style')
    @stack('style')
    @livewireStyles

    @unless (auth()->check() && (request()->routeIs('account') || auth()->user()->hasVerifiedPhone()) && !request()->routeIs('welcome') && !request()->routeIs('documents.viewer'))
        <style>
            .main-panel {
                width: 100%;
            }
            .page-body-wrapper {
                padding-top: 0;
            }
            .content-wrapper {
                padding: 0;
            }
        </style>
    @endunless
</head>

@auth
    <x-notify-modal/>
@endauth

<body>
<div class="container-scroller">
    @if (auth()->check() && (request()->routeIs('account') || auth()->user()->hasVerifiedPhone()) && !request()->routeIs('welcome') && !request()->routeIs('documents.viewer'))
        <x-navbar/>
    @endif
    <div class="container-fluid page-body-wrapper">
        @if (auth()->check() && (request()->routeIs('account') || auth()->user()->hasVerifiedPhone()) && !request()->routeIs('welcome') && !request()->routeIs('documents.viewer'))
            <x-sidebar/>
        @endif
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
        </div>
    </div>
</div>
<!-- Firebase -->
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-database.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>


<!-- tinyMCE -->
{{--    <script src="https://cdn.tiny.cloud/1/6hi4bok2utssc8368iz75o1mg2sma3bl46qf41q4i2ah6myx/tinymce/5/tinymce.min.js"></script>--}}
{{--    <script src="{{asset('assets/js/tinyMCE/az.js')}}"></script>--}}

<!-- Scripts -->
<script src="{{ mix('assets/js/app.js') }}" ></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.4/datatables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>


@livewireScripts

<!-- Alpine js and Spruce state management for it -->
<script src="https://cdn.jsdelivr.net/npm/@ryangjchandler/spruce@2.x.x/dist/spruce.umd.js"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"></script>

<!-- summernote -->
<script src="{{ asset('assets/js/summernote/summernote.js') }}"></script>


@stack('scripts')
@yield('scripts')

    <x-notify/>

    @auth
{{--        <script src="https://js.pusher.com/7.1/pusher.min.js"></script>--}}
<script src="{{asset('assets/js/pusher/pusher.js')}}" ></script>
        <script>
            $(document).ready(function (){
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
<script>
        $("#search-project").keyup(function () {
            var filter = $(this).val();
            $(".searching-list").each(function () {
                if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                    $(this).addClass('hidden');
                } else {
                    $(this).removeClass('hidden');
                }
            });
        });

    //chat system
    var reciever_id = '';
    var my_id = '{{Auth::id()}}';
    // $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var pusherchat = new Pusher('5e68408656b975a4e1e4', {
            cluster: 'mt1'
        });

        var channelchat = pusherchat.subscribe('my-channel');
        channelchat.bind('my-event', function (data) {

            if (my_id == data.from) {
                $('#' + data.to).click()
            } else if (my_id == data.to) {
                if (reciever_id == data.from) {
                    $('#' + data.from).click()
                } else {
                    var pendinghtml = $('#' + data.from).find('.pending').html()
                    var pending = parseInt(pendinghtml?.replace(/[^0-9.]/g, ""));
                    function playSound(url) {
                        const audio = new Audio(url)
                        audio.play()
                    }
                    console.log(pending)
                    if (pending) {
                        $('#' + data.from).find('.pending').html(pending + 1)
                        $('.unread' + data.from).css('display','block')
                        playSound('{{asset('assets/audio/notify/message.wav')}}')
                    } else {

                        $('#' + data.from).append('<span class="pending">1</span>');
                        $('.unread' + data.from).css('display','block')
                        playSound('{{asset('assets/audio/notify/message.wav')}}')
                    }
                }
            }
        });

        $('.user').click(function () {
            $('.user').removeClass('active');
            $(this).addClass('active');
            $(this).find('.pending').remove();
            $(this).find('.total-unread').css('display','none');
            reciever_id = $(this).attr('id')
            $.ajax({
                type: 'get',
                url: 'message/' + reciever_id,
                data: '',
                cache: false,
                success: function (data) {
                    $('#messages').html(data);
                    scrollToBottomFunc();
                }
            })
        });

        $(document).on('keyup', '.input-text input', function (e) {
            function playSound(url) {
                const audio = new Audio(url)
                audio.play()
            }
            var message = $(this).val();
            if (e.keyCode == 13 && message != '' && reciever_id != '') {
                $(this).val('')
                var datastr = "reciever_id=" + reciever_id + "&message=" + message;
                $.ajax({
                    type: 'post',
                    url: "message",
                    data: datastr,
                    cache: false,
                    success: function ($data) {
                    },
                    error: function (jqXHR, status, err) {
                    },
                    complete: function () {
                        scrollToBottomFunc()
                        playSound('{{asset('assets/audio/notify/send.wav')}}')
                    }
                })
            }
        });
    // });

    function scrollToBottomFunc() {
        $('.message-wrapper').animate({
            scrollTop: $('.message-wrapper').get(0).scrollHeight
        },50);
        $('.chat-input').focus()
    }
</script>
</body>
</html>
