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
                    const {{$model}}Data = @json($result);

                    // const data = [
                    //     {
                    //         name: "Monica",
                    //         steps: 45688,
                    //         pictureSettings: {
                    //             src: "https://www.amcharts.com/wp-content/uploads/2019/04/monica.jpg"
                    //         }
                    //     },
                    //     {
                    //         name: "Joey",
                    //         steps: 35781,
                    //         pictureSettings: {
                    //             src: "https://www.amcharts.com/wp-content/uploads/2019/04/joey.jpg"
                    //         }
                    //     },
                    //     {
                    //         name: "Ross",
                    //         steps: 25464,
                    //         pictureSettings: {
                    //             src: "https://www.amcharts.com/wp-content/uploads/2019/04/ross.jpg"
                    //         }
                    //     },
                    //     {
                    //         name: "Phoebe",
                    //         steps: 18788,
                    //         pictureSettings: {
                    //             src: "https://www.amcharts.com/wp-content/uploads/2019/04/phoebe.jpg"
                    //         }
                    //     },
                    //     {
                    //         name: "Rachel",
                    //         steps: 15465,
                    //         pictureSettings: {
                    //             src: "https://www.amcharts.com/wp-content/uploads/2019/04/rachel.jpg"
                    //         }
                    //     },
                    //     {
                    //         name: "Chandler",
                    //         steps: 11561,
                    //         pictureSettings: {
                    //             src: "https://www.amcharts.com/wp-content/uploads/2019/04/chandler.jpg"
                    //         }
                    //     }
                    // ];

                    const {{$model}}Chart = {{$model}}Root.container.children.push(
                        am5xy.XYChart.new({{$model}}Root, {
                            panX: false,
                            panY: false,
                            wheelX: "none",
                            wheelY: "none",
                            paddingLeft: 50,
                            paddingRight: 40
                        })
                    );

                    const yRenderer = am5xy.AxisRendererY.new({{$model}}Root, {});
                    yRenderer.grid.template.set("visible", false);

                    const yAxis = {{$model}}Chart.yAxes.push(
                        am5xy.CategoryAxis.new({{$model}}Root, {
                            categoryField: "name",
                            renderer: yRenderer,
                            paddingRight:40
                        })
                    );

                    const xRenderer = am5xy.AxisRendererX.new({{$model}}Root, {});
                    xRenderer.grid.template.set("strokeDasharray", [3]);

                    const xAxis = {{$model}}Chart.xAxes.push(
                        am5xy.ValueAxis.new({{$model}}Root, {
                            min: 0,
                            renderer: xRenderer
                        })
                    );

                    const {{$model}}Series = {{$model}}Chart.series.push(
                        am5xy.ColumnSeries.new({{$model}}Root, {
                            name: "Income",
                            xAxis: xAxis,
                            yAxis: yAxis,
                            valueXField: "steps",
                            categoryYField: "name",
                            sequencedInterpolation: true,
                            calculateAggregates: true,
                            maskBullets: false,
                            tooltip: am5.Tooltip.new({{$model}}Root, {
                                dy: -30,
                                pointerOrientation: "vertical",
                                labelText: "{valueX}"
                            })
                        })
                    );

                    {{$model}}Series.columns.template.setAll({
                        strokeOpacity: 0,
                        cornerRadiusBR: 10,
                        cornerRadiusTR: 10,
                        cornerRadiusBL: 10,
                        cornerRadiusTL: 10,
                        maxHeight: 50,
                        fillOpacity: 0.8
                    });

                    let currentlyHovered;

                    {{$model}}Series.columns.template.events.on("pointerover", function(e) {
                        handleHover(e.target.dataItem);
                    });

                    {{$model}}Series.columns.template.events.on("pointerout", function(e) {
                        handleOut();
                    });

                    function handleHover(dataItem) {
                        if (dataItem && currentlyHovered != dataItem) {
                            handleOut();
                            currentlyHovered = dataItem;
                            const bullet = dataItem.bullets[0];
                            bullet.animate({
                                key: "locationX",
                                to: 1,
                                duration: 600,
                                easing: am5.ease.out(am5.ease.cubic)
                            });
                        }
                    }

                    function handleOut() {
                        if (currentlyHovered) {
                            const bullet = currentlyHovered.bullets[0];
                            bullet.animate({
                                key: "locationX",
                                to: 0,
                                duration: 600,
                                easing: am5.ease.out(am5.ease.cubic)
                            });
                        }
                    }


                    let circleTemplate = am5.Template.new({});

                    {{$model}}Series.bullets.push(function(root, series, dataItem) {
                        const bulletContainer = am5.Container.new({{$model}}Root, {});
                        const circle = bulletContainer.children.push(
                            am5.Circle.new(
                                    {{$model}}Root,
                                {
                                    radius: 34
                                },
                                circleTemplate
                            )
                        );

                        const maskCircle = bulletContainer.children.push(
                            am5.Circle.new({{$model}}Root, { radius: 27 })
                        );

                        // only containers can be masked, so we add image to another container
                        const imageContainer = bulletContainer.children.push(
                            am5.Container.new({{$model}}Root, {
                                mask: maskCircle
                            })
                        );

                        const image = imageContainer.children.push(
                            am5.Picture.new({{$model}}Root, {
                                templateField: "pictureSettings",
                                centerX: am5.p50,
                                centerY: am5.p50,
                                width: 60,
                                height: 60
                            })
                        );

                        return am5.Bullet.new({{$model}}Root, {
                            locationX: 0,
                            sprite: bulletContainer
                        });
                    });


                    {{$model}}Series.set("heatRules", [
                        {
                            dataField: "valueX",
                            min: am5.color(0xe5dc36),
                            max: am5.color(0x5faa46),
                            target: {{$model}}Series.columns.template,
                            key: "fill"
                        },
                        {
                            dataField: "valueX",
                            min: am5.color(0xe5dc36),
                            max: am5.color(0x5faa46),
                            target: circleTemplate,
                            key: "fill"
                        }
                    ]);

                    {{$model}}Series.data.setAll({{$model}}Data);
                    yAxis.data.setAll({{$model}}Data);

                    const cursor = {{$model}}Chart.set("cursor", am5xy.XYCursor.new({{$model}}Root, {}));
                    cursor.lineX.set("visible", false);
                    cursor.lineY.set("visible", false);

                    cursor.events.on("cursormoved", function() {
                        const dataItem = {{$model}}Series.get("tooltip").dataItem;
                        if (dataItem) {
                            handleHover(dataItem)
                        }
                        else {
                            handleOut();
                        }
                    })

                    {{$model}}Series.appear();
                    {{$model}}Chart.appear(1000, 100);

                });
            </script>

        </div>
    </div>
</div>
