{{--<div class="{{$widget->class_attribute}}">--}}
{{--    <div class="card border-0 widget-container">--}}
{{--        <div class="py-2 px-1">--}}
{{--            <div id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}"></div>--}}
{{--            <script>--}}
{{--                const {{$model}}Chart = am4core.create("{{$widget->key}}", am4charts.XYChart);--}}
{{--                // custom lang--}}
{{--                @if (app()->getLocale() == 'az')--}}
{{--                        {{$model}}Chart.language.locale = am4lang_az_AZ;--}}
{{--                @endif--}}

{{--                // Create daily series and related axes--}}
{{--                const {{$model}}DateAxis1 = {{$model}}Chart.xAxes.push(new am4charts.DateAxis());--}}
{{--                {{$model}}DateAxis1.renderer.grid.template.location = 0;--}}
{{--                {{$model}}DateAxis1.renderer.minGridDistance = 40;--}}
{{--                const {{$model}}ValueAxis1 = {{$model}}Chart.yAxes.push(new am4charts.ValueAxis());--}}

{{--                // inquiries data--}}
{{--                const {{$model}}Data = @json($results);--}}

{{--                // Legend--}}
{{--                const {{$model}}Series1 = {{$model}}Chart.series.push(new am4charts.ColumnSeries());--}}
{{--                {{$model}}Series1.dataFields.valueY = "value";--}}
{{--                {{$model}}Series1.dataFields.dateX = "date";--}}
{{--                {{$model}}Series1.data = generateDailyData();--}}
{{--                {{$model}}Series1.xAxis = {{$model}}DateAxis1;--}}
{{--                {{$model}}Series1.yAxis = {{$model}}ValueAxis1;--}}
{{--                {{$model}}Series1.tooltipText = "{dateX}: [bold]{valueY}[/]";--}}


{{--                // Title--}}
{{--                const {{$model}}Title = {{$model}}Chart.titles.create();--}}
{{--                {{$model}}Title.text = '{{$widget->details}}';--}}
{{--                {{$model}}Title.fontSize = '24px';--}}
{{--                {{$model}}Title.marginBottom = '20px';--}}

{{--                // Add cursor--}}
{{--                const {{$model}}Cursor = new am4charts.XYCursor();--}}
{{--                {{$model}}Chart.cursor = {{$model}}Cursor;--}}
{{--                {{$model}}Cursor.lineX.disabled = true;--}}
{{--                {{$model}}Cursor.lineY.disabled = true;--}}
{{--                {{$model}}Cursor.behavior = "none";--}}

{{--                function generateDailyData() {--}}
{{--                    const data = [];--}}
{{--                    for(let key in {{$model}}Data.data) {--}}
{{--                        data.push({--}}
{{--                            date: key,--}}
{{--                            value: {{$model}}Data.data[key].length--}}
{{--                        });--}}
{{--                    }--}}
{{--                    return data;--}}
{{--                }--}}
{{--            </script>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}