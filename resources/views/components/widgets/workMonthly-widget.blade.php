<div class="{{$widget->class_attribute}}">
    <div class="card border-0 widget-container">
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

{{--<div class="col-md-6 grid-margin stretch-card">--}}
{{--    <div class="card">--}}
{{--        <div class="card-body">--}}
{{--            <div class="d-flex justify-content-between">--}}
{{--                <p class="card-title">Sales Report</p>--}}
{{--            </div>--}}
{{--            <p class="font-weight-500">The total number of sessions within the date range. It is the period time a user is actively engaged with your website, page or app, etc</p>--}}
{{--            <div id="{{$widget->key}}" style="height: 370px; width: 100%;"></div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--<script>--}}
{{--    window.onload = function () {--}}

{{--        var {{$model}}Chart = new CanvasJS.Chart("{{$widget->key}}", {--}}
{{--            exportEnabled: true,--}}
{{--            animationEnabled: true,--}}
{{--            title:{--}}
{{--                text: "Car Parts Sold in Different States"--}}
{{--            },--}}
{{--            axisX: {--}}
{{--                title: "States"--}}
{{--            },--}}
{{--            axisY: {--}}

{{--                titleFontColor: "#4F81BC",--}}
{{--                lineColor: "#4F81BC",--}}
{{--                labelFontColor: "#4F81BC",--}}
{{--                tickColor: "#4F81BC",--}}
{{--                includeZero: true--}}
{{--            },--}}
{{--            axisY2: {--}}
{{--                titleFontColor: "#C0504E",--}}
{{--                lineColor: "#C0504E",--}}
{{--                labelFontColor: "#C0504E",--}}
{{--                tickColor: "#C0504E",--}}
{{--                includeZero: true--}}
{{--            },--}}
{{--            toolTip: {--}}
{{--                shared: true--}}
{{--            },--}}
{{--            legend: {--}}
{{--                cursor: "pointer",--}}
{{--                itemclick: toggleDataSeries--}}
{{--            },--}}
{{--            data: [{--}}
{{--                type: "column",--}}
{{--                name: "Verified",--}}
{{--                showInLegend: true,--}}
{{--                yValueFormatString: "#,##0.# Units",--}}
{{--                dataPoints: [--}}
{{--                    { label: "New Jersey",  y: 123 },--}}
{{--                    { label: "Texas", y: 877 },--}}
{{--                    { label: "Oregon", y: 445 },--}}
{{--                    { label: "Montana",  y: 45 },--}}
{{--                    { label: "Massachusetts",  y: 454}--}}
{{--                ]--}}
{{--            },--}}
{{--                {--}}
{{--                    type: "column",--}}
{{--                    name: "Clutch",--}}
{{--                    axisYType: "secondary",--}}
{{--                    showInLegend: true,--}}
{{--                    yValueFormatString: "#,##0.# Units",--}}
{{--                    dataPoints: [--}}
{{--                        { label: "New Jersey", y: 210.5 },--}}
{{--                        { label: "Texas", y: 135 },--}}
{{--                        { label: "Oregon", y: 425 },--}}
{{--                        { label: "Montana", y: 130 },--}}
{{--                        { label: "Massachusetts", y: 528 }--}}
{{--                    ]--}}
{{--                }]--}}
{{--        });--}}
{{--        {{$model}}Chart.render();--}}

{{--        function toggleDataSeries(e) {--}}
{{--            if (typeof (e.{{$model}}DataSeries.visible) === "undefined" || e.{{$model}}DataSeries.visible) {--}}
{{--                e.{{$model}}DataSeries.visible = false;--}}
{{--            } else {--}}
{{--                e.{{$model}}DataSeries.visible = true;--}}
{{--            }--}}
{{--            e.{{$model}}Chart.render();--}}
{{--        }--}}

{{--    }--}}
{{--</script>--}}
