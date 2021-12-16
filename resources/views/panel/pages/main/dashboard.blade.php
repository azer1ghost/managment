@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- Amcharts4 -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <!-- Amcharts5 -->
    <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
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
    <div class="row">
        @foreach($widgets as $widget)
            @if(auth()->user()->hasPermission($widget->key))
                <x-dynamic-component component="widgets.{{$widget->key}}" :widget="$widget" />
            @endif
        @endforeach
    </div>
@endsection