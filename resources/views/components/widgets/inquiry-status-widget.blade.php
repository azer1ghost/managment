<div class="{{$widget->class_attribute}}">
    <div class="card">
        <div class="py-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%; height: 300px;"></div>
            <script>
                const chart = am4core.create("{{$widget->key}}", am4charts.PieChart);
                const data = @json($results);
                const keys = @json($keys);
                {{--const colors = @json($colors);--}}
                const overall = [];
                data.forEach(function (value, idx){
                    overall.push({'status': keys[idx], 'value': value})
                });
                chart.data = overall;
                chart.innerRadius = am4core.percent(40);
                chart.radius = am4core.percent(90);
                chart.legend = new am4charts.Legend();
                chart.legend.position = "right";
                const title = chart.titles.create();
                title.text = '{{$widget->details}}';
                title.fontSize = 25;
                title.marginBottom = 0;
                const pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "value";
                pieSeries.dataFields.category = "status";
                pieSeries.slices.template.propertyFields.fill = "color";
                // Disable ticks and labels
                pieSeries.labels.template.disabled = true;
                pieSeries.ticks.template.disabled = true;
                const rgm = new am4core.RadialGradientModifier();
                rgm.brightnesses.push(-0.8, -0.8, -0.5, 0, - 0.5);
                pieSeries.slices.template.fillModifier = rgm;
                pieSeries.slices.template.strokeModifier = rgm;
                pieSeries.slices.template.strokeOpacity = 0.4;
                pieSeries.slices.template.strokeWidth = 0;
            </script>
        </div>
    </div>
</div>