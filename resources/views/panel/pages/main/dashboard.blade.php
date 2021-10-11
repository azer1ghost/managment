@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- AmCharts -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script src="{{asset('assets/js/am4langs/am4lang_az_AZ.js')}}"></script>
    <script>am4core.useTheme(am4themes_animated);</script>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link is-current="1">
            Dashboard
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