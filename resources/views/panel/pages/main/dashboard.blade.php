@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('style')
    <!-- AmCharts -->
    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="//cdn.amcharts.com/lib/4/themes/animated.js"></script>
    <script>am4core.useTheme(am4themes_animated);</script>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link is-current="1">
            Dashboard
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row">
{{--        <div class="col-12 col-md-3">--}}
{{--            <div class="card text-center">--}}
{{--                <div class="card-header">--}}
{{--                    <b>@lang('translates.navbar.inquiry')</b>--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <i class="far fa-comments-alt fa-3x text-primary"></i>--}}
{{--                    <p class="font-weight-bold mb-0 mt-1" style="font-size: 18px">--}}
{{--                        @lang('translates.date.today'): {{$inquiriesToday}}<br>--}}
{{--                        @lang('translates.date.month'): {{$inquiriesMonth}}--}}
{{--                    </p>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        @foreach($gadgets as $gadget)
                @php($data = eval($gadget->query))
                @php($total = $data['total'])
                @php($results = $data['items'])
                @php($keys = $data['keys'])
                <div class="col-12 col-md-5">
                    <h5 class="text-center">{{$gadget->details}} (Total: {{ $total }})</h5>
                    <div id="{{$gadget->key}}" style="width: 100%; height: 400px;"></div>
                    <script>
                        var chart = am4core.create("{{$gadget->key}}", am4charts.PieChart3D);
                        var data = [{{implode(',' ,$results)}}];
                        var keys = ["{!! implode('","', $keys) !!}"];
                        var colors = ["{!! implode('","', explode(',', $gadget->colors)) !!}"];
                        var overall = [];
                        data.forEach(function (value, idx){
                            overall.push({'status': keys[idx], 'value': value, 'color' : colors[idx]})
                        });
                        chart.data = overall;
                        chart.innerRadius = am4core.percent(40);
                        chart.radius = am4core.percent(90);
                        chart.legend = new am4charts.Legend();
                        chart.legend.position = "right";
                        var pieSeries = chart.series.push(new am4charts.PieSeries3D());
                        pieSeries.dataFields.value = "value";
                        pieSeries.dataFields.category = "status";
                        pieSeries.slices.template.propertyFields.fill = "color";
                        // Disable ticks and labels
                        pieSeries.labels.template.disabled = true;
                        pieSeries.ticks.template.disabled = true;
                        // change text and tooltip
                        // pieSeries.labels.template.text = "{category}: {value.value}";
                        // pieSeries.slices.template.tooltipText = "{category}: {value.value}";
                    </script>
                </div>
        @endforeach
    </div>
@endsection
