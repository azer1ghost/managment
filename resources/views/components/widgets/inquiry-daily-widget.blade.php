<div class="{{$widget->class_attribute}}">
    <div class="card">
        <div class="py-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%; height: 300px;"></div>
            <script>
                const inquiryDailyChart = am4core.create("{{$widget->key}}", am4charts.XYChart);
                const inquiryDailyData = @json($results);
                const inquiryDailyKeys = @json($keys);
                {{--const colors = @json($colors);--}}
                const inquiryDailyOverall = [];
                inquiryDailyData.forEach(function (value, idx){
                    inquiryDailyOverall.push({'daily': inquiryDailyKeys[idx], 'value': value})
                });
                inquiryDailyChart.data = inquiryDailyOverall;
                inquiryDailyChart.innerRadius = am4core.percent(40);
                inquiryDailyChart.radius = am4core.percent(90);
                inquiryDailyChart.legend = new am4charts.Legend();
                inquiryDailyChart.legend.position = "right";
                const inquiryDailyTitle = inquiryDailyChart.titles.create();
                inquiryDailyTitle.text = '{{$widget->details}}';
                inquiryDailyTitle.fontSize = 25;
                inquiryDailyTitle.marginBottom = 0;
                const inquiryDailyPieSeries = inquiryDailyChart.series.push(new am4charts.PieSeries());
                inquiryDailyPieSeries.dataFields.value = "daily";
                inquiryDailyPieSeries.dataFields.category = "status";
                // pieSeries.slices.template.propertyFields.fill = "color";
                // Disable ticks and labels
                inquiryDailyPieSeries.labels.template.disabled = true;
                inquiryDailyPieSeries.ticks.template.disabled = true;
                const inquiryDailyRgm = new am4core.RadialGradientModifier();
                inquiryDailyRgm.brightnesses.push(-0.8, -0.8, -0.5, 0, - 0.5);
                inquiryDailyPieSeries.slices.template.fillModifier = inquiryDailyRgm;
                inquiryDailyPieSeries.slices.template.strokeModifier = inquiryDailyRgm;
                inquiryDailyPieSeries.slices.template.strokeOpacity = 0.4;
                inquiryDailyPieSeries.slices.template.strokeWidth = 0;
            </script>
        </div>
    </div>
</div>