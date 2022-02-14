@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- Amcharts4 -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    <!-- Amcharts5 -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
    <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
    <script src="{{asset('assets/js/am4langs/am4lang_az_AZ.js')}}"></script>

    <script>am4core.useTheme(am4themes_animated);</script>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link is-current="1">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
    </x-bread-crumb>
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
                        <i class="fas fa-calendar mr-2"></i> {{now()->format('d F Y')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card tale-bg">
                <div class="card-people mt-auto">
                        <img src="{{asset('assets/images/dashboard/people.svg')}}" alt="people">
                    <div class="weather-info">
                        <div class="d-flex">
                            <div>
                                <h2 class="mb-0 font-weight-normal"><i class="icon-sun mr-2"></i>15<sup>C</sup></h2>
                            </div>
                            <div class="ml-2">
                                <h4 class="location font-weight-normal">Baku</h4>
                                <h6 class="font-weight-normal">Azerbaijan</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin transparent">
            <div class="row">
                @foreach($statistics as $stat)
                    <div class="col-md-6 {{$stat->class}} mb-4 stretch-card transparent">
                        <div class="card card-{{$stat->color}}">
                            <div class="card-body">
                                <p class="mb-4">{{$stat->title}}</p>
                                <p class="fs-30 mb-2">{{$stat->data->total}}</p>
                                <p>{{$stat->data->percentage}}% ( {{$stat->data->text}} )</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card position-relative">
                <div class="card-body">
                    <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="row">
                                    <div class="col-md-12 col-xl-2 d-flex flex-column justify-content-start">
                                        <div class="ml-xl-4 mt-3">
                                            <p class="card-title">Xidmətlər</p>
                                            <h1 class="text-primary">{{\App\Models\Work::count()}}</h1>
                                            <h3 class="font-weight-500 mb-xl-4 text-primary">Toplam İşlərin sayı</h3>
                                            <p class="mb-2 mb-xl-0">Daha ətraflı məlumat üçün <a href="{{ route('works.index') }}" class="text-primary">işlər</a> bölməsindən xidmətlər haqqında məlumat ala bilərsiniz</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-10">
                                        <div class="row">
                                            <div class="col-md-6 border-right">
                                                <div class="table-responsive mb-3 mb-md-0 mt-3">
                                                    <table class="table table-borderless report-table">
                                                        @foreach($services as $service)
                                                        <tr>
                                                            <td class="text-muted w-50">{{$service->getAttribute('name')}}</td>
                                                            <td class="w-100 px-0">
                                                                <div class="progress progress-md mx-4">
                                                                    <div class="progress-bar {{$colors[$loop->iteration]}}" role="progressbar" style="width: {{$service->getAttribute('works_count') / \App\Models\Work::count() * 100}}%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                            <td><h5 class="font-weight-bold mb-0">{{$service->getAttribute('works_count')}}</h5></td>
                                                        </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mt-3">
                                                <div id="chartContainer" style="height: 370px; width: 100%;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#detailedReports" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#detailedReports" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row m-0">
{{--        @foreach($widgets as $widget)--}}
{{--            @if(auth()->user()->hasPermission($widget->key))--}}
{{--                <x-dynamic-component component="widgets.{{$widget->key}}" :widget="$widget" />--}}
{{--            @endif--}}
{{--        @endforeach--}}
    </div>
    <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                title:{
                    text: "Works",
                    horizontalAlign: "left"
                },
                data: [{
                    type: "doughnut",
                    startAngle: 60,
                    //innerRadius: 60,
                    indexLabelFontSize: 17,
                    indexLabel: "{label} - #percent%",
                    toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                    dataPoints: [
                        { y: 67, label: "EGB" },
                        { y: 28, label: "EQIB" },
                        { y: 10, label: "Labels" },
                        { y: 7, label: "Drafts"},
                        { y: 15, label: "Trash"},
                        { y: 6, label: "Spam"},
                        { y: 6, label: "Spam"},
                    ]
                }]
            });
            chart.render();

        }
    </script>
@endsection
