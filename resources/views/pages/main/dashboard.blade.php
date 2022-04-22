@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- Canvas JS -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link is-current="1">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
    </x-bread-crumb>
{{--    <div class="row m-0">--}}
{{--        <div class="col-md-12 grid-margin">--}}
{{--            <div class="row">--}}
{{--                <div class="col-12 col-xl-8 mb-4 mb-xl-0 px-0 pb-3">--}}
{{--                    <h3 class="font-weight-bold">@lang('translates.navbar.welcome') {{auth()->user()->getAttribute('fullname')}}</h3>--}}
{{--                    <h6 class="font-weight-normal mb-0">--}}
{{--                        @lang('translates.widgets.welcome_msg')!--}}
{{--                        <a href="{{route('tasks.index')}}" class="text-primary">@lang('translates.widgets.you_have', ['count' => $tasksCount])</a>--}}
{{--                    </h6>--}}
{{--                </div>--}}
{{--                <div class="col-12 col-xl-4">--}}
{{--                    <div class="justify-content-end d-flex align-items-center">--}}
{{--                        <i class="fas fa-calendar mr-1"></i>--}}
{{--                        {{now()->format('d F Y')}}--}}
{{--                        <span id="dashboard-clock" class="ml-1"></span>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="row">--}}
{{--        <div class="col-md-6 grid-margin stretch-card">--}}
{{--            <div class="card tale-bg">--}}
{{--                <img style="border-radius: 20px" src="{{asset('https://img.freepik.com/free-vector/meeting-office-interior-business-conference-room-with-people-managers-working-team-cartoon-interior_80590-7766.jpg?w=1380')}}" alt="people">--}}
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
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="col-md-6 grid-margin transparent">--}}
{{--            <div class="row">--}}
{{--                @foreach($statistics as $stat)--}}
{{--                    <div class="col-md-6 {{$stat['class'] ?? ''}} mb-4 stretch-card transparent">--}}
{{--                        <div class="card card-{{$stat['color'] ?? ''}}">--}}
{{--                            <div class="card-body">--}}
{{--                                <p class="mb-4">{{$stat['title'] ?? ''}}</p>--}}
{{--                                <p class="fs-30 mb-2">{{$stat['data']['total'] ?? 0}}</p>--}}
{{--                                <p>{{$stat['data']['percentage'] ?? 0}}% ( {{$stat['data']['text'] ?? ''}} )</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--            @if(auth()->user()->getAttribute('department_id') == \App\Models\Department::SALES)--}}
{{--                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">--}}
{{--                    @lang('translates.navbar.report')--}}
{{--                </button>--}}

{{--                <!-- Modal -->--}}
{{--                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--                    <div class="modal-dialog" role="document">--}}
{{--                        <div class="modal-content">--}}
{{--                            <div class="modal-header">--}}
{{--                                <h5 class="modal-title" id="exampleModalLabel">@lang('translates.navbar.report')</h5>--}}
{{--                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                                    <span aria-hidden="true">&times;</span>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                            <div class="modal-body">--}}
{{--                                <ul class="list-group">--}}
{{--                                    <li class="list-group-item">Yeni Müştəri: {{$newCustomer}}</li>--}}
{{--                                    <li class="list-group-item">Təkrar Zəng: {{$recall}}</li>--}}
{{--                                    <li class="list-group-item">Görüş: {{$meetings}}</li>--}}
{{--                                </ul>--}}
{{--                            </div>--}}
{{--                            <div class="modal-footer">--}}
{{--                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>--}}
{{--                                <button type="button" class="btn btn-success copy">@lang('translates.buttons.copy')</button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endif--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="row">--}}
{{--        @foreach($widgets as $widget)--}}
{{--            @if(auth()->user()->hasPermission($widget->key))--}}
{{--                <x-dynamic-component component="widgets.{{$widget->key}}" :widget="$widget" />--}}
{{--            @endif--}}
{{--        @endforeach--}}
{{--    </div>--}}
@endsection
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