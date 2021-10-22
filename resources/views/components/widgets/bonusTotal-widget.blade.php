<div class="{{$widget->class_attribute}}">
    <div class="card border-0" style="background: #e9ecef !important;">
        <div class="py-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}"></div>
            <script>
                const {{$model}}Chart = am4core.create("{{$widget->key}}", am4charts.PieChart);
                {{$model}}Chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

                {{$model}}Chart.data = @json($results)

                {{$model}}Chart.radius = am4core.percent(70);
                {{$model}}Chart.innerRadius = am4core.percent(40);
                {{$model}}Chart.startAngle = 180;
                {{$model}}Chart.endAngle = 360;

                // Title
                const {{$model}}Title = {{$model}}Chart.titles.create();
                {{$model}}Title.text = '{{$widget->details}}';
                {{$model}}Title.fontSize = 25;
                {{$model}}Title.marginBottom = 10;

                // Series
                const {{$model}}Series = {{$model}}Chart.series.push(new am4charts.PieSeries());
                {{$model}}Series.dataFields.value = "total";
                {{$model}}Series.dataFields.category = "users";
                {{$model}}Series.slices.template.cornerRadius = 10;
                {{$model}}Series.slices.template.innerCornerRadius = 7;
                {{$model}}Series.slices.template.draggable = true;
                {{$model}}Series.slices.template.inert = true;
                {{$model}}Series.alignLabels = false;
                {{$model}}Series.labels.template.disabled = true;
                {{$model}}Series.ticks.template.disabled = true;

                {{$model}}Series.hiddenState.properties.startAngle = 90;
                {{$model}}Series.hiddenState.properties.endAngle = 90;

                {{$model}}Chart.legend = new am4charts.Legend();
            </script>
        </div>
    </div>
</div>