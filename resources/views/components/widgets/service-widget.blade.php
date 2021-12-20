<div class="{{$widget->class_attribute}}">
    <div class="card border-0" style="background: #e9ecef !important;">
        <div class="py-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}"></div>
            <script>
                am5.ready(function() {

                    const {{$model}}Root = am5.Root.new("{{$widget->key}}");

                    {{$model}}Root.setThemes([
                        am5themes_Animated.new({{$model}}Root)
                    ]);

                    const {{$model}}Chart = {{$model}}Root.container.children.push(am5xy.XYChart.new({{$model}}Root, {
                        panX: false,
                        panY: false,
                        wheelX: "panX",
                        wheelY: "zoomX",
                    }));

                    const {{$model}}Cursor = {{$model}}Chart.set("cursor", am5xy.XYCursor.new({{$model}}Root, {}));
                    {{$model}}Cursor.lineY.set("visible", false);

                    {{$model}}Chart.children.unshift(am5.Label.new({{$model}}Root, {
                        text: '{{$widget->details}}',
                        fontSize: 25,
                        textAlign: "center",
                        x: am5.percent(50),
                        centerX: am5.percent(50),
                        paddingTop: 0,
                        paddingBottom: 0
                    }));

                    const {{$model}}Data = @json($services);

                    const {{$model}}XRenderer = am5xy.AxisRendererX.new({{$model}}Root, { minGridDistance: 30 });
                    {{$model}}XRenderer.labels.template.setAll({
                        rotation: -90,
                        centerY: am5.p50,
                        centerX: am5.p100,
                        paddingRight: 15
                    });

                    const {{$model}}XAxis = {{$model}}Chart.xAxes.push(am5xy.CategoryAxis.new({{$model}}Root, {
                        maxDeviation: 0.3,
                        categoryField: "service",
                        renderer: {{$model}}XRenderer,
                        tooltip: am5.Tooltip.new({{$model}}Root, {})
                    }));

                    const {{$model}}YAxis = {{$model}}Chart.yAxes.push(am5xy.ValueAxis.new({{$model}}Root, {
                        maxDeviation: 0.3,
                        renderer: am5xy.AxisRendererY.new({{$model}}Root, {})
                    }));

                    const {{$model}}Series = {{$model}}Chart.series.push(am5xy.ColumnSeries.new({{$model}}Root, {
                        xAxis: {{$model}}XAxis,
                        yAxis: {{$model}}YAxis,
                        valueYField: "total",
                        sequencedInterpolation: true,
                        categoryXField: "service",
                        tooltip: am5.Tooltip.new({{$model}}Root, {
                            labelText:"{valueY}"
                        })
                    }));

                    {{$model}}Series.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5 });
                    {{$model}}Series.columns.template.adapters.add("fill", (fill, target) => {
                        return {{$model}}Chart.get("colors").getIndex({{$model}}Series.columns.indexOf(target));
                    });

                    {{$model}}Series.columns.template.adapters.add("stroke", (stroke, target) => {
                        return {{$model}}Chart.get("colors").getIndex({{$model}}Series.columns.indexOf(target));
                    });

                    {{$model}}XAxis.data.setAll({{$model}}Data);
                    {{$model}}Series.data.setAll({{$model}}Data);

                    {{$model}}Series.appear();
                    {{$model}}Chart.appear(1000, 100);
                });
            </script>
        </div>
    </div>
</div>