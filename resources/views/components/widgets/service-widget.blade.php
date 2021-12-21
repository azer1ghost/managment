<div class="{{$widget->class_attribute}}">
    <div class="card border-0" style="background: #e9ecef !important;">
        <div class="py-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}" class="text-center">
                <h3>{{$widget->details}}</h3>
            </div>
            <script>
                am5.ready(function() {
                    const {{$model}}Root = am5.Root.new("{{$widget->key}}");

                    {{$model}}Root.setThemes([
                        am5themes_Animated.new({{$model}}Root)
                    ]);

                    const {{$model}}Chart = {{$model}}Root.container.children.push(am5percent.PieChart.new({{$model}}Root, {
                        radius: am5.percent(80),
                        innerRadius: am5.percent(50),
                        layout: {{$model}}Root.horizontalLayout
                    }));

                    const {{$model}}Series = {{$model}}Chart.series.push(am5percent.PieSeries.new({{$model}}Root, {
                        valueField: "total",
                        categoryField: "service",
                        tooltip: am5.Tooltip.new({{$model}}Root, {
                            labelText: "{service}: {total}"
                        }),
                    }));

                    {{$model}}Series.data.setAll(@json($services));

                    {{$model}}Series.labels.template.set("visible", false);
                    {{$model}}Series.ticks.template.set("visible", false);
                    {{$model}}Series.slices.template.set("strokeOpacity", 1);

                    const {{$model}}Legend = {{$model}}Chart.children.push(am5.Legend.new({{$model}}Root, {
                        layout: {{$model}}Root.verticalLayout,
                        y: am5.percent(50),
                        centerY: am5.percent(50),
                        maxColumns: 1,
                    }));

                    {{$model}}Legend.markerRectangles.template.setAll({
                        cornerRadiusTL: 10,
                        cornerRadiusTR: 10,
                        cornerRadiusBL: 10,
                        cornerRadiusBR: 10
                    });
                    {{$model}}Legend.data.setAll({{$model}}Series.dataItems);

                    {{$model}}Series.appear(1000, 100);
                });
            </script>
        </div>
    </div>
</div>