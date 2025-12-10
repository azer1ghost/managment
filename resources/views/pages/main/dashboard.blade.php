@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- Canvas JS -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- New Year Styles -->
    <style>
        .newyear-banner {
            text-align: center;
            padding: 12px;
            font-size: 28px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(90deg, #ff2e63, #ff9a00);
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 0 15px #ff9a00;
            animation: glow 2s infinite alternate;
        }
        
        @keyframes glow {
            from { box-shadow: 0 0 8px #ff9a00; }
            to   { box-shadow: 0 0 20px #ff2e63; }
        }
        
        .card {
            border-radius: 16px !important;
            box-shadow: 0 0 10px rgba(255, 182, 193, 0.2);
            transition: 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 0 16px rgba(255, 82, 82, 0.45);
            transform: translateY(-3px);
        }
        
        .ny-countdown {
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(255, 46, 99, 0.9), rgba(255, 154, 0, 0.9));
            backdrop-filter: blur(8px);
            border-radius: 14px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 15px rgba(255, 154, 0, 0.3);
        }
        
        .ny-countdown h3 {
            color: #fff;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        #countdown {
            font-size: 28px;
            font-weight: 700;
            margin-top: 10px;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        
        .snowflake {
            position: fixed;
            top: -10px;
            color: white;
            font-size: 18px;
            animation: fall linear infinite;
            pointer-events: none;
            z-index: 9999;
        }
        
        @keyframes fall {
            to { transform: translateY(110vh); }
        }
        
        /* Birthday Card Styles */
        .birthday-card {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4);
            overflow: hidden;
            animation: birthdayPulse 3s ease-in-out infinite;
        }
        
        @keyframes birthdayPulse {
            0%, 100% { box-shadow: 0 8px 20px rgba(255, 215, 0, 0.4); }
            50% { box-shadow: 0 8px 30px rgba(255, 215, 0, 0.7); }
        }
        
        .birthday-header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .birthday-header h4 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .birthday-body {
            padding: 25px;
            background: rgba(255, 255, 255, 0.95);
        }
        
        .birthday-users-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #ffd700;
        }
        
        .birthday-list {
            list-style: none;
            padding: 0;
            margin: 10px 0 0 0;
        }
        
        .birthday-list li {
            padding: 8px 0;
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }
        
        .birthday-message {
            margin-top: 20px;
            padding: 20px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 10px;
            border: 2px solid #90caf9;
        }
        
        .birthday-message p {
            font-size: 15px;
            line-height: 1.8;
            color: #1565c0;
            margin-bottom: 10px;
        }
        
        .birthday-message p:first-child {
            font-size: 18px;
            font-weight: 600;
            color: #0d47a1;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link is-current="1">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
    </x-bread-crumb>
    
    <!-- Birthday Congratulations Box -->
    @if($birthdayUsers->isNotEmpty())
        <div class="birthday-card mb-4">
            <div class="birthday-header">
                <h4 class="mb-0">
                    🎉 Bu gün ad günüdür! 🎂
                </h4>
            </div>
            <div class="birthday-body">
                <div class="birthday-users-list mb-3">
                    <strong>Bu gün ad gününü qeyd edənlər:</strong>
                    <ul class="birthday-list">
                        @foreach($birthdayUsers as $user)
                            <li>
                                🎉 {{ $user->getAttribute('name') }} {{ $user->getAttribute('surname') }}
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="birthday-message">
                    <p class="mb-2">🎉 Ad gününüz mübarək! 🎂</p>
                    <p class="mb-2">Bu gün sizin üçün xüsusi bir gündür — həyatınıza yeni yaş, yeni ümidlər və yeni uğurlar gətirsin!</p>
                    <p class="mb-2">Sizə bol enerji, rahatlıq, sağlamlıq və istədiklərinizi həyata keçirmə gücü arzulayırıq.</p>
                    <p class="mb-0">Uğurlarınız daim artsın, xoş günləriniz çox olsun! ✨</p>
                </div>
            </div>
        </div>
    @endif
    
    <!-- Happy New Year 2026 Banner -->
    <div class="newyear-banner">
        🎄 Happy New Year 2026 🎆
    </div>
    
    <!-- New Year Countdown Widget -->
    <div class="ny-countdown">
        <h3>⏳ New Year Countdown</h3>
        <div id="countdown">Loading...</div>
    </div>
    <div class="row m-0">
        <div class="col-md-12 grid-margin">
            <div class="row">
                <div class="col-12 col-xl-8 mb-4 mb-xl-0 px-0 pb-3">
                    <h3 class="font-weight-bold">@lang('translates.navbar.welcome') {{auth()->user()->getAttribute('fullname')}}</h3>
                    <h6 class="font-weight-normal mb-0">
                        @lang('translates.widgets.welcome_msg')!
                        <a href="{{route('tasks.index')}}" class="text-primary">@lang('translates.widgets.you_have', ['count' => $tasksCount])</a>
                    </h6>
                </div>
                <div class="col-12 col-xl-4">
                    <div class="justify-content-end d-flex align-items-center">
                        <i class="fas fa-calendar mr-1"></i>
                        {{now()->format('d F Y')}}
                        <span id="dashboard-clock" class="ml-1"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card tale-bg">
                <img style="border-radius: 20px" src="{{asset('https://img.freepik.com/free-vector/meeting-office-interior-business-conference-room-with-people-managers-working-team-cartoon-interior_80590-7766.jpg?w=1380')}}" alt="people">
{{--                <div style="position: absolute; color: #e70d0d; font-weight: bold">--}}
{{--                    @foreach($currencies as $currency => $value)--}}
{{--                        <ul class="list-group">--}}
{{--                            <li class="list-group-item border-0 bg-transparent p-1"><i class="far fa-{{$value['flag']}}-sign "></i> {{$currency}} {{$value['value']}} AZN</li>--}}
{{--                        </ul>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--                @if($weather)--}}
{{--                    <div class="weather-info">--}}
{{--                        <div class="d-flex align-items-center">--}}
{{--                            <div class="d-flex align-items-center">--}}
{{--                                <img src="{{$weather['icon']}}" alt="" width="70" height="70"/>--}}
{{--                                <h2 class="mb-0 font-weight-normal">--}}
{{--                                    {{$weather['temp']}}<sup>C</sup>--}}
{{--                                </h2>--}}
{{--                            </div>--}}
{{--                            <div class="ml-2">--}}
{{--                                <h4 class="location font-weight-normal">Baku</h4>--}}
{{--                                <h6 class="font-weight-normal">Azerbaijan</h6>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="text-right">--}}
{{--                            <p class="text-capitalize">{{$weather['description'][app()->getLocale()]}}</p>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
        </div>

        <div class="col-md-6 grid-margin transparent">
            <div class="row">
                @if(in_array(auth()->user()->company_id , [1,2,3,4,6,10,11,12,14,15,16]) )
                    @foreach($statistics as $stat)
                        <div class="col-md-6 {{$stat['class'] ?? ''}} mb-4 stretch-card transparent">
                            <div class="card card-{{$stat['color'] ?? ''}}">
                                <div class="card-body">
                                    <p class="mb-4">{{$stat['title'] ?? ''}}</p>
                                    <p class="fs-30 mb-2">{{$stat['data']['total'] ?? 0}}</p>
                                    <p>{{$stat['data']['percentage'] ?? 0}}% ( {{$stat['data']['text'] ?? ''}} )</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                        <div class="col-md-6 dark-blue mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">TEST</p>
                                    <p class="fs-30 mb-2">15</p>
                                    <p>15% ( TEST )</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 dark-blue mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">TEST</p>
                                    <p class="fs-30 mb-2">20</p>
                                    <p>20% ( TEST )</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 dark-blue mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">TEST</p>
                                    <p class="fs-30 mb-2">20</p>
                                    <p>20% ( TEST )</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 dark-blue mb-4 stretch-card transparent">
                            <div class="card card-dark-blue">
                                <div class="card-body">
                                    <p class="mb-4">TEST</p>
                                    <p class="fs-30 mb-2">500</p>
                                    <p>500% ( TEST )</p>
                                </div>
                            </div>
                        </div>

                @endif

            </div>
            @if(auth()->user()->getAttribute('department_id') == \App\Models\Department::SALES)
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    @lang('translates.navbar.report')
                </button>

                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">@lang('translates.navbar.report')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <ul class="list-group">
                                    <li class="list-group-item">Yeni Müştəri: {{$newCustomer}}</li>
                                    <li class="list-group-item">Təkrar Zəng: {{$recall}}</li>
                                    <li class="list-group-item">Görüş: {{$meetings}}</li>
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                                <button type="button" class="btn btn-success copy">@lang('translates.buttons.copy')</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        @foreach($widgets as $widget)
            @if(auth()->user()->hasPermission($widget->key))
                <x-dynamic-component component="widgets.{{$widget->key}}" :widget="$widget" />
            @endif
        @endforeach
    </div>
@endsection

@section('scripts')
    <script>
        // New Year Countdown
        const target = new Date("Jan 1, 2026 00:00:00").getTime();
        
        setInterval(() => {
            const now = new Date().getTime();
            const diff = target - now;
            
            if (diff > 0) {
                const days = Math.floor(diff / (1000*60*60*24));
                const hours = Math.floor((diff % (1000*60*60*24)) / (1000*60*60));
                const mins = Math.floor((diff % (1000*60*60)) / (1000*60));
                const secs = Math.floor((diff % (1000*60)) / 1000);
                
                document.getElementById("countdown").innerHTML =
                    `${days}d ${hours}h ${mins}m ${secs}s`;
            } else {
                document.getElementById("countdown").innerHTML = "Happy New Year 2026! 🎉";
            }
        }, 1000);
        
        // Snowfall Animation
        document.addEventListener("DOMContentLoaded", () => {
            const body = document.body;
            for (let i = 0; i < 60; i++) {
                let snow = document.createElement("div");
                snow.classList.add("snowflake");
                snow.style.left = Math.random() * 100 + "vw";
                snow.style.animationDuration = (2 + Math.random() * 5) + "s";
                snow.style.opacity = Math.random();
                snow.innerHTML = "❅";
                body.appendChild(snow);
            }
        });
    </script>
    
    {{--@section('scripts')--}}
    {{--    <script>--}}
    {{--        $('.copy').click(function () {--}}
    {{--            const newCustomer = '{{$newCustomer}}';--}}
    {{--            const meetings = '{{$meetings}}';--}}
    {{--            const recall = '{{$recall}}';--}}
    {{--            let data = `Yeni Müştəri: ${newCustomer}, Təkrakr Zəng: ${recall}, Görüşmə: ${meetings}`;--}}
    {{--            navigator.clipboard.writeText(data);--}}
    {{--            $(this).text('@lang('translates.buttons.copied')');--}}
    {{--        });--}}
    {{--    </script>--}}
    {{--@endsection--}}
@endsection