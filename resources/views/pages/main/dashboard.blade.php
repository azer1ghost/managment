@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- Amcharts4 -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
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
                    <h3 class="font-weight-bold">Welcome {{auth()->user()->getAttribute('fullname')}}</h3>
                    <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have <span class="text-primary">{{$tasksCount}} tasks!</span></h6>
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
    <div class="row m-0">
{{--        @foreach($widgets as $widget)--}}
{{--            @if(auth()->user()->hasPermission($widget->key))--}}
{{--                <x-dynamic-component component="widgets.{{$widget->key}}" :widget="$widget" />--}}
{{--            @endif--}}
{{--        @endforeach--}}
    </div>
@endsection