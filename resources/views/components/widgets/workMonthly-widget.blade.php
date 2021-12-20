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
                        layout: {{$model}}Root.verticalLayout
                    }));

                    {{$model}}Chart.children.unshift(am5.Label.new({{$model}}Root, {
                        text: '{{$widget->details}}',
                        fontSize: 25,
                        textAlign: "center",
                        x: am5.percent(50),
                        centerX: am5.percent(50),
                        paddingTop: 0,
                        paddingBottom: 0
                    }));

                    const legend = {{$model}}Chart.children.push(
                        am5.Legend.new({{$model}}Root, {
                            centerX: am5.p50,
                            x: am5.p50
                        })
                    );

                    const {{$model}}Data = @json($works);

                    const xAxis = {{$model}}Chart.xAxes.push(am5xy.CategoryAxis.new({{$model}}Root, {
                        categoryField: "day",
                        renderer: am5xy.AxisRendererX.new({{$model}}Root, {
                            cellStartLocation: 0.1,
                            cellEndLocation: 0.9
                        }),
                        tooltip: am5.Tooltip.new({{$model}}Root, {})
                    }));

                    xAxis.data.setAll({{$model}}Data);

                    const yAxis = {{$model}}Chart.yAxes.push(am5xy.ValueAxis.new({{$model}}Root, {
                        renderer: am5xy.AxisRendererY.new({{$model}}Root, {})
                    }));

                    function makeSeries(name, fieldName) {
                        let {{$model}}Series = {{$model}}Chart.series.push(am5xy.ColumnSeries.new({{$model}}Root, {
                            name: name,
                            xAxis: xAxis,
                            yAxis: yAxis,
                            valueYField: fieldName,
                            categoryXField: "day"
                        }));

                        {{$model}}Series.columns.template.setAll({
                            tooltipText: "{name}: {valueY}",
                            width: am5.percent(90),
                            tooltipY: 0
                        });

                        {{$model}}Series.data.setAll({{$model}}Data);

                        {{$model}}Series.appear();

                        {{$model}}Series.bullets.push(function () {
                            return am5.Bullet.new({{$model}}Root, {
                                locationY: 0,
                                sprite: am5.Label.new({{$model}}Root, {
                                    text: "{valueY}",
                                    fill: {{$model}}Root.interfaceColors.get("alternativeText"),
                                    centerY: 0,
                                    centerX: am5.p50,
                                    populateText: true
                                })
                            });
                        });

                        legend.data.push({{$model}}Series);
                    }

                    makeSeries("Total Works", "total");
                    makeSeries("Verified Works", "verified");

                    {{$model}}Chart.appear(1000, 31)

                });
            </script>
        </div>
    </div>
</div>