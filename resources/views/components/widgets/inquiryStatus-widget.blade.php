{{--<div class="{{$widget->class_attribute}}">--}}
{{--    <div class="card border-0 widget-container">--}}
{{--        <div class="py-2 px-1">--}}
{{--            <div id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}"></div>--}}
{{--            <script>--}}
{{--                const {{$model}}Chart = am4core.create("{{$widget->key}}", am4charts.PieChart);--}}

{{--                // custom lang--}}
{{--                @if (app()->getLocale() == 'az')--}}
{{--                        {{$model}}Chart.language.locale = am4lang_az_AZ;--}}
{{--                @endif--}}

{{--                // Chart data--}}
{{--                const {{$model}}Data = @json($results);--}}
{{--                const {{$model}}Keys = @json($keys);--}}
{{--                --}}{{--const colors = @json($colors);--}}
{{--                const {{$model}}Overall = [];--}}
{{--                {{$model}}Data.forEach(function (value, idx){--}}
{{--                    {{$model}}Overall.push({'status': {{$model}}Keys[idx], 'value': value})--}}
{{--                });--}}
{{--                {{$model}}Chart.data = {{$model}}Overall;--}}

{{--                // Radius--}}
{{--                {{$model}}Chart.innerRadius = am4core.percent(50);--}}
{{--                {{$model}}Chart.radius = am4core.percent(90);--}}

{{--                // Legend--}}
{{--                {{$model}}Chart.legend = new am4charts.Legend();--}}
{{--                {{$model}}Chart.legend.position = "right";--}}

{{--                // Title--}}
{{--                const {{$model}}Title = {{$model}}Chart.titles.create();--}}
{{--                {{$model}}Title.text = '{{$widget->details}}';--}}
{{--                {{$model}}Title.fontSize = 25;--}}
{{--                {{$model}}Title.marginBottom = 10;--}}

{{--                // PieSeries--}}
{{--                const {{$model}}PieSeries = {{$model}}Chart.series.push(new am4charts.PieSeries());--}}
{{--                {{$model}}PieSeries.dataFields.value = "value";--}}
{{--                {{$model}}PieSeries.dataFields.category = "status";--}}
{{--                // pieSeries.slices.template.propertyFields.fill = "color";--}}
{{--                // Disable ticks and labels--}}
{{--                {{$model}}PieSeries.labels.template.disabled = true;--}}
{{--                {{$model}}PieSeries.ticks.template.disabled = true;--}}

{{--                // Total value in the middle--}}
{{--                const {{$model}}Label = {{$model}}PieSeries.createChild(am4core.Label);--}}
{{--                {{$model}}Label.text = "{values.value.sum}";--}}
{{--                {{$model}}Label.horizontalCenter = "middle";--}}
{{--                {{$model}}Label.verticalCenter = "middle";--}}
{{--                {{$model}}Label.fontSize = 35;--}}

{{--                // RGM--}}
{{--                const {{$model}}Rgm = new am4core.RadialGradientModifier();--}}
{{--                {{$model}}Rgm.brightnesses.push(-0.8, -0.8, -0.5, 0, - 0.5);--}}
{{--                {{$model}}PieSeries.slices.template.fillModifier = {{$model}}Rgm;--}}
{{--                {{$model}}PieSeries.slices.template.strokeModifier = {{$model}}Rgm;--}}
{{--                {{$model}}PieSeries.slices.template.strokeOpacity = 0.4;--}}
{{--                {{$model}}PieSeries.slices.template.strokeWidth = 0;--}}
{{--            </script>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}