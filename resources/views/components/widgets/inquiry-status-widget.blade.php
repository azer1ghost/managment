<div class="{{$widget->class_attribute}}">
    <div class="card">
        <div class="py-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%; height: 300px;"></div>
            <script>
                const inquiryStatusChart = am4core.create("{{$widget->key}}", am4charts.PieChart);
                const inquiryStatusData = @json($results);
                const inquiryStatusKeys = @json($keys);
                {{--const colors = @json($colors);--}}
                const inquiryStatusOverall = [];
                inquiryStatusData.forEach(function (value, idx){
                    inquiryStatusOverall.push({'status': inquiryStatusKeys[idx], 'value': value})
                });
                inquiryStatusChart.data = inquiryStatusOverall;
                inquiryStatusChart.innerRadius = am4core.percent(40);
                inquiryStatusChart.radius = am4core.percent(90);
                inquiryStatusChart.legend = new am4charts.Legend();
                inquiryStatusChart.legend.position = "right";
                const inquiryStatusTitle = inquiryStatusChart.titles.create();
                inquiryStatusTitle.text = '{{$widget->details}}';
                inquiryStatusTitle.fontSize = 25;
                inquiryStatusTitle.marginBottom = 0;
                const inquiryStatusPieSeries = inquiryStatusChart.series.push(new am4charts.PieSeries());
                inquiryStatusPieSeries.dataFields.value = "value";
                inquiryStatusPieSeries.dataFields.category = "status";
                // pieSeries.slices.template.propertyFields.fill = "color";
                // Disable ticks and labels
                inquiryStatusPieSeries.labels.template.disabled = true;
                inquiryStatusPieSeries.ticks.template.disabled = true;
                const inquiryStatusRgm = new am4core.RadialGradientModifier();
                inquiryStatusRgm.brightnesses.push(-0.8, -0.8, -0.5, 0, - 0.5);
                inquiryStatusPieSeries.slices.template.fillModifier = inquiryStatusRgm;
                inquiryStatusPieSeries.slices.template.strokeModifier = inquiryStatusRgm;
                inquiryStatusPieSeries.slices.template.strokeOpacity = 0.4;
                inquiryStatusPieSeries.slices.template.strokeWidth = 0;
            </script>
        </div>
    </div>
</div>