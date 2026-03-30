@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- Canvas JS -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
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
            50%       { box-shadow: 0 8px 30px rgba(255, 215, 0, 0.7); }
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
                <h4 class="mb-0">🎉 Bu gün ad günüdür! 🎂</h4>
            </div>
            <div class="birthday-body">
                <div class="birthday-users-list mb-3">
                    <strong>Bu gün ad gününü qeyd edənlər:</strong>
                    <ul class="birthday-list">
                        @foreach($birthdayUsers as $user)
                            <li>🎉 {{ $user->getAttribute('name') }} {{ $user->getAttribute('surname') }}</li>
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
                <img style="border-radius: 20px"
                     src="{{ asset('https://img.freepik.com/free-vector/meeting-office-interior-business-conference-room-with-people-managers-working-team-cartoon-interior_80590-7766.jpg?w=1380') }}"
                     alt="people">
            </div>
        </div>

        <div class="col-md-6 grid-margin transparent">
            <div class="row">
                @if(in_array(auth()->user()->company_id, [1,2,3,4,6,10,11,12,14,15,16]))
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
        $('.copy').click(function () {
            const newCustomer = '{{$newCustomer}}';
            const meetings    = '{{$meetings}}';
            const recall      = '{{$recall}}';
            let data = `Yeni Müştəri: ${newCustomer}, Təkrar Zəng: ${recall}, Görüşmə: ${meetings}`;
            navigator.clipboard.writeText(data);
            $(this).text('@lang('translates.buttons.copied')');
        });
    </script>
@endsection
