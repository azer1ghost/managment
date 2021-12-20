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

                    const chart = {{$model}}Root.container.children.push(am5xy.XYChart.new({{$model}}Root, {
                        panX: false,
                        panY: false,
                        wheelX: "panX",
                        wheelY: "zoomX",
                        layout: {{$model}}Root.verticalLayout
                    }));
                    const colors = chart.get("colors");

                    const data = [{
                        country: "US",
                        visits: 725,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/united-states.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "UK",
                        visits: 625,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/united-kingdom.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "China",
                        visits: 602,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/china.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "Japan",
                        visits: 509,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/japan.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "Germany",
                        visits: 322,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/germany.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "France",
                        visits: 214,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/france.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "India",
                        visits: 204,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/india.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "Spain",
                        visits: 198,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/spain.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "Netherlands",
                        visits: 165,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/netherlands.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "Russia",
                        visits: 130,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/russia.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "South Korea",
                        visits: 93,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/south-korea.svg",
                        columnSettings: { fill: colors.next() }
                    }, {
                        country: "Canada",
                        visits: 41,
                        icon: "https://www.amcharts.com/wp-content/uploads/flags/canada.svg",
                        columnSettings: { fill: colors.next() }
                    }];

                    const xAxis = chart.xAxes.push(am5xy.CategoryAxis.new({{$model}}Root, {
                        categoryField: "country",
                        renderer: am5xy.AxisRendererX.new({{$model}}Root, {
                            minGridDistance: 30
                        }),
                        bullet: function ({{$model}}Root, axis, dataItem) {
                            return am5xy.AxisBullet.new({{$model}}Root, {
                                locationY: 0.5,
                                sprite: am5.Picture.new({{$model}}Root, {
                                    width: 24,
                                    height: 24,
                                    centerY: am5.p50,
                                    centerX: am5.p50,
                                    src: dataItem.dataContext.icon
                                })
                            });
                        }
                    }));

                    xAxis.get("renderer").labels.template.setAll({
                        paddingTop: 20
                    });

                    xAxis.data.setAll(data);

                    const yAxis = chart.yAxes.push(am5xy.ValueAxis.new({{$model}}Root, {
                        renderer: am5xy.AxisRendererY.new({{$model}}Root, {})
                    }));

                    const series = chart.series.push(am5xy.ColumnSeries.new({{$model}}Root, {
                        xAxis: xAxis,
                        yAxis: yAxis,
                        valueYField: "visits",
                        categoryXField: "country"
                    }));

                    series.columns.template.setAll({
                        tooltipText: "{categoryX}: {valueY}",
                        tooltipY: 0,
                        strokeOpacity: 0,
                        templateField: "columnSettings"
                    });

                    series.data.setAll(data);
                    series.appear();
                    chart.appear(1000, 100);

                }); // end am5.ready()

            </script>
        </div>
    </div>
</div>