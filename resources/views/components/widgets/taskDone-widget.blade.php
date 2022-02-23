<div class="{{$widget->class_attribute}}">
    <div class="card border-0 widget-container">
        <div class="d-flex pt-2 px-3 justify-content-between">
            <div class="d-flex">
                @lang('translates.navbar.user')
                <div class="custom-control custom-switch ml-2">
                    <input type="checkbox" class="custom-control-input" id="toggle-taskable">
                    <label class="custom-control-label" for="toggle-taskable"></label>
                </div>
                @lang('translates.navbar.department')
            </div>
            <script>
                const {{$model}}Chart = am4core.create("{{$widget->key}}", am4charts.XYChart);
                {{$model}}Chart.hiddenState.properties.opacity = 0; // this creates initial fade-in
                {{$model}}Chart.paddingRight = 40;

                // custom lang
                @if (app()->getLocale() == 'az')
                        {{$model}}Chart.language.locale = am4lang_az_AZ;
                @endif

                // tasks done data
                {{$model}}Chart.data = (@json($results['users'])).reverse();

                document.getElementById('toggle-taskable').addEventListener('change', function(event) {
                    if (event.currentTarget.checked) {
                        {{$model}}Chart.data = (@json($results['departments'])).reverse();
                    } else {
                        {{$model}}Chart.data = (@json($results['users'])).reverse();
                    }
                    reanimate();
                });

                const {{$model}}CategoryAxis = {{$model}}Chart.yAxes.push(new am4charts.CategoryAxis());
                {{$model}}CategoryAxis.dataFields.category = "name";
                {{$model}}CategoryAxis.renderer.grid.template.strokeOpacity = 0;
                {{$model}}CategoryAxis.renderer.minGridDistance = 10;
                {{$model}}CategoryAxis.renderer.labels.template.dx = -40;
                {{$model}}CategoryAxis.renderer.minWidth = 230;
                {{$model}}CategoryAxis.renderer.tooltip.dx = -10;

                const {{$model}}ValueAxis = {{$model}}Chart.xAxes.push(new am4charts.ValueAxis());
                {{$model}}ValueAxis.renderer.inside = true;
                {{$model}}ValueAxis.renderer.labels.template.fillOpacity = 0.3;
                {{$model}}ValueAxis.renderer.grid.template.strokeOpacity = 0;
                {{$model}}ValueAxis.min = 0;
                {{$model}}ValueAxis.cursorTooltipEnabled = false;
                {{$model}}ValueAxis.renderer.baseGrid.strokeOpacity = 0;
                {{$model}}ValueAxis.renderer.labels.template.dy = 20;

                const {{$model}}Series = {{$model}}Chart.series.push(new am4charts.ColumnSeries);
                {{$model}}Series.dataFields.valueX = "steps";
                {{$model}}Series.dataFields.categoryY = "name";
                {{$model}}Series.tooltipText = "{valueX.value}";
                {{$model}}Series.tooltip.pointerOrientation = "vertical";
                {{$model}}Series.tooltip.dy = - 30;
                {{$model}}Series.columnsContainer.zIndex = 9000;

                const {{$model}}ColumnTemplate = {{$model}}Series.columns.template;
                {{$model}}ColumnTemplate.height = am4core.percent(50);
                {{$model}}ColumnTemplate.maxHeight = 50;
                {{$model}}ColumnTemplate.column.cornerRadius(60, 10, 60, 10);
                {{$model}}ColumnTemplate.strokeOpacity = 0;

                {{$model}}Series.heatRules.push({ target: {{$model}}ColumnTemplate, property: "fill", dataField: "valueX", min: am4core.color("#e5dc36"), max: am4core.color("#5faa46") });
                {{$model}}Series.mainContainer.mask = undefined;

                // Title
                const {{$model}}Title = {{$model}}Chart.titles.create();
                {{$model}}Title.text = '{{$widget->details}}';
                {{$model}}Title.fontSize = '24px';
                {{$model}}Title.marginBottom = '20px';

                const {{$model}}Cursor = new am4charts.XYCursor();
                {{$model}}Chart.cursor = {{$model}}Cursor;
                {{$model}}Cursor.lineX.disabled = true;
                {{$model}}Cursor.lineY.disabled = true;
                {{$model}}Cursor.behavior = "none";

                const {{$model}}Bullet = {{$model}}ColumnTemplate.createChild(am4charts.CircleBullet);
                {{$model}}Bullet.circle.radius = 30;
                {{$model}}Bullet.valign = "middle";
                {{$model}}Bullet.align = "left";
                {{$model}}Bullet.isMeasured = true;
                {{$model}}Bullet.interactionsEnabled = false;
                {{$model}}Bullet.horizontalCenter = "right";
                {{$model}}Bullet.interactionsEnabled = false;

                const hoverState = {{$model}}Bullet.states.create("hover");
                const outlineCircle = {{$model}}Bullet.createChild(am4core.Circle);
                outlineCircle.adapter.add("radius", function (radius, target) {
                    const circleBullet = target.parent;
                    return circleBullet.circle.pixelRadius + 10;
                })

                const image = {{$model}}Bullet.createChild(am4core.Image);
                image.width = 60;
                image.height = 60;
                image.horizontalCenter = "middle";
                image.verticalCenter = "middle";
                image.propertyFields.href = "href";

                image.adapter.add("mask", function (mask, target) {
                    const circleBullet = target.parent;
                    return circleBullet.circle;
                })

                function reanimate() {
                    {{$model}}Chart.appear();
                }
            </script>
        </div>
        <div class="pb-2 px-1">
            <div id="{{$widget->key}}" style="width: 100%;{{$widget->style_attribute}}"></div>
        </div>
    </div>
</div>