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

                    const {{$model}}Chart = {{$model}}Root.container.children.push(
                        am5percent.PieChart.new({{$model}}Root, {
                            layout: {{$model}}Root.verticalLayout
                        })
                    );

                    const {{$model}}Series = {{$model}}Chart.series.push(
                        am5percent.PieSeries.new({{$model}}Root, {
                            valueField: "value",
                            categoryField: "type",
                            fillField: "color",
                            alignLabels: false,
                            tooltip: am5.Tooltip.new({{$model}}Root, {
                                labelText: "{type}: {value}"
                            })
                        })
                    );

                    {{$model}}Series.labels.template.setAll({
                        maxWidth: 150,
                        oversizedBehavior: "wrap" // to truncate labels, use "truncate"
                    });

                    {{$model}}Chart.children.unshift(am5.Label.new({{$model}}Root, {
                        text: '{{$widget->details}}',
                        fontSize: 25,
                        textAlign: "center",
                        x: am5.percent(50),
                        centerX: am5.percent(50),
                        paddingTop: 0,
                        paddingBottom: 0
                    }));

                    {{$model}}Series.slices.template.set("templateField", "sliceSettings");
                    {{$model}}Series.labels.template.set("radius", -70);
                    {{$model}}Series.slices.template.events.on("click", function(event) {
                        console.log(event.target.dataItem.dataContext)
                        if (event.target.dataItem.dataContext.id !== undefined) {
                            selected = event.target.dataItem.dataContext.id;
                        } else {
                            selected = undefined;
                        }
                        {{$model}}Series.data.setAll(generateChartData());
                    });

                    let selected;
                    const types = @json($types);

                    {{$model}}Series.data.setAll(generateChartData());

                    function generateChartData() {
                        const chartData = [];
                        for (let i = 0; i < types.length; i++) {
                            if (i === selected) {
                                for (let x = 0; x < types[i].subs.length; x++) {
                                    chartData.push({
                                        type: types[i].subs[x].type,
                                        value: types[i].subs[x].value,
                                        color: types[i].color,
                                        pulled: true,
                                        sliceSettings: {
                                            active: true
                                        }
                                    });
                                }
                            } else {
                                chartData.push({
                                    type: types[i].type,
                                    value: types[i].value,
                                    color: types[i].color,
                                    id: i
                                });
                            }
                        }

                        return chartData;
                    }
                });
            </script>
        </div>
    </div>
</div>