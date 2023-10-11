<div class="col-12 mb-3 grid-margin stretch-card">
    <div class="card position-relative">
        <div class="card-body">
            <div id="detailedReports" class="carousel slide detailed-report-carousel position-static pt-2" data-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row">
                            <div class="col-md-12 col-xl-2 d-flex flex-column justify-content-start">
                                <div class="ml-xl-4 mt-3">
                                    <p class="card-title">Xidmətlər</p>
                                    @if(in_array(auth()->user()->company_id , [1,2,3,4,6,10,11,12,14,15,16]) )
                                        <h1 class="text-primary">{{\App\Models\Work::count()}}</h1>
                                    @else
                                        <h1 class="text-primary">#</h1>
                                    @endif

                                    <h3 class="font-weight-500 mb-xl-4 text-primary">{{$widget->details}}</h3>
                                    <p class="mb-2 mb-xl-0">Daha ətraflı məlumat üçün <a href="{{ route('works.index') }}" class="text-primary">işlər</a> bölməsindən xidmətlər haqqında məlumat ala bilərsiniz</p>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-10">
                                <div class="row">
                                    <div class="col-md-6 border-right" style="overflow-y: scroll; height: 300px">
                                        <div class="table-responsive mb-3 mb-md-0 mt-3">
                                            <table class="table table-borderless report-table">
                                                @foreach($services as $service)
                                                    @if(in_array(auth()->user()->company_id , [1,2,3,4,6,10,11,12,14,15,16]) )
                                                    <tr>
                                                        <td class="text-muted w-50">{{$service->getAttribute('name')}}</td>
                                                        <td class="w-100 px-0">
                                                                <div class="progress progress-md mx-4">
                                                                    <div class="progress-bar" role="progressbar" style="width: {{$service->getAttribute('works_count') / \App\Models\Work::count() * 100}}%;background-color: {{rand_color()}}!important;"></div>
                                                                </div>
                                                        </td>
                                                        <td><h5 class="font-weight-bold mb-0">{{$service->getAttribute('works_count')}}</h5></td>
                                                    </tr>
                                                    @else

                                                        <tr>
                                                            <td class="text-muted w-50">TEST</td>
                                                            <td class="w-100 px-0">
                                                                <div class="progress progress-md mx-4">
                                                                    <div class="progress-bar" role="progressbar" style="width: 0;background-color: {{rand_color()}}!important;"></div>
                                                                </div>
                                                            </td>
                                                            <td><h5 class="font-weight-bold mb-0">#</h5></td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </table>
                                        </div>
                                    </div>
                                    <div class="{{$widget->class_attribute}}"><div id="{{$widget->key}}" style="{{$widget->style_attribute}}; width: 100%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--                <a class="carousel-control-prev" href="#detailedReports" role="button" data-slide="prev">--}}
{{--                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
{{--                    <span class="sr-only">Previous</span>--}}
{{--                </a>--}}
{{--                <a class="carousel-control-next" href="#detailedReports" role="button" data-slide="next">--}}
{{--                    <span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
{{--                    <span class="sr-only">Next</span>--}}
{{--                </a>--}}
            </div>
        </div>
    </div>
</div>

<script>
    @php
        $authUser = auth()->user();
    @endphp
    window.onload = function () {

        var companyId = {{$authUser->company_id}};
        var chartData = @json($works);

        for (var i = 0; i < chartData.length; i++) {
            if (companyId === 17) {
                chartData[i].y = "15";
            }
        }

        var chart = new CanvasJS.Chart("{{$widget->key}}", {
            animationEnabled: true,
            title: {
                {{--text: '@lang('translates.navbar.services')',--}}
                horizontalAlign: "left"
            },
            data: [{
                type: "doughnut",
                startAngle: 20,
                indexLabelFontSize: 12,
                indexLabel: "{label} - #percent%",
                toolTipContent: "<b>{label}:</b> {y} (#percent%)",
                dataPoints: chartData
            }]
        });
        chart.render();
    }
</script>
