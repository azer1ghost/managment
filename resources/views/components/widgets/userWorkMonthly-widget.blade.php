<div class="{{$widget->class_attribute}} grid-margin stretch-card col-md-6" style="">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <p class="card-title">{{$widget->details}}</p>
                <a href="#" class="text-info">View all</a>
            </div>
            <p class="font-weight-500"></p>
            <div></div>
            <div class="col-md-12 col-xl-12">
                <div class="row">
                    <div class="col-md-12 border-right" style="overflow-y: scroll; height: 300px">
                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                            <table class="table table-borderless report-table">
                                @foreach($works as $work)
                                    <tr>
                                        <td class="text-dark bold">{{$work->name . ' ' .$work->surname}}</td>
                                        <td class="w-75 px-0">
                                            <div class="progress progress-md mx-4">
                                                <div class="progress-bar"
                                                     style="width: {{$work->total_value / 3000 * 100}}%;
                                                     background-color: {{rand_color()}}!important;"
                                                     role="progressbar">
                                                </div>
                                            </div>
                                        </td>
                                        <td><h5 class="font-weight-bold mb-0">{{$work->total_value}}</h5></td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    window.onload = function () {

//Better to construct options first and then pass it as a parameter
        var options = {
            animationEnabled: true,
            title: {
                text: "Mobile Phones Used For",
                fontColor: "Peru"
            },
            axisY: {
                tickThickness: 0,
                lineThickness: 0,
                valueFormatString: " ",
                includeZero: true,
                gridThickness: 0
            },
            axisX: {
                tickThickness: 0,
                lineThickness: 0,
                labelFontSize: 18,
                labelFontColor: "Peru"
            },
            data: [{
                indexLabelFontSize: 26,
                toolTipContent: "<span style=\"color:#62C9C3\">{indexLabel}:</span> <span style=\"color:#CD853F\"><strong>{y}</strong></span>",
                indexLabelPlacement: "inside",
                indexLabelFontColor: "white",
                indexLabelFontWeight: 600,
                indexLabelFontFamily: "Verdana",
                color: "#62C9C3",
                type: "bar",
                dataPoints: [
                    {y: 21, label: "21%", indexLabel: "Video"},
                    {y: 25, label: "25%", indexLabel: "Dining"},
                    {y: 33, label: "33%", indexLabel: "Entertainment"},
                    {y: 36, label: "36%", indexLabel: "News"},
                    {y: 42, label: "42%", indexLabel: "Music"},
                    {y: 49, label: "49%", indexLabel: "Social Networking"},
                    {y: 50, label: "50%", indexLabel: "Maps/ Search"},
                    {y: 55, label: "55%", indexLabel: "Weather"},
                    {y: 61, label: "61%", indexLabel: "Games"}
                ]
            }]
        };

        $("#chartContainer").CanvasJSChart(options);
    }
</script>

