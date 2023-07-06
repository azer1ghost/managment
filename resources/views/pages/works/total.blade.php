@extends('layouts.main')

@section('title', __('translates.navbar.total'))

@section('style')
    <style>
        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .table tr {
            cursor: pointer;
        }



        /* Stil değişiklikleri */
        .work-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .work-stats h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .work-stats h2 {
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.total')
        </x-bread-crumb-link>
    </x-bread-crumb>

{{--    <div class="work-stats">--}}
{{--        <div>--}}
{{--            <h1>Ayın əvvəlindən Qeyri-Rəsmi Məbləğ</h1>--}}
{{--            <h2>{{ $totalIllegalAmount }}</h2>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ayın əvvəlindən Rəsmi Məbləğ</h1>--}}
{{--            <h2>{{ $totalAmount }}</h2>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ayın əvvəlindən ƏDV Məbləğ</h1>--}}
{{--            <h2>{{ $totalVat }}</h2>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ümumi məbləğ</h1>--}}
{{--            <h2>{{ $totalAll }}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div class="col-6 col-md-6">
        <div id="chartContainer" style="height: 370px; width: 100%;"></div>
    </div>
{{--    <div class="col-6 col-md-6">--}}
{{--        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>--}}
{{--    </div>--}}

@endsection

@section('scripts')
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', (event) => {
            var dataPoints = {!! json_encode($dataPoints) !!};

            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                theme: "light2",
                title: {
                    text: "Aylıq Satış Cədvəli"
                },
                axisX: {
                    interval: 1,
                    intervalType: "month",
                    valueFormatString: "MMM"
                },
                axisY: {
                    prefix: "₼"
                },
                toolTip: {
                    shared: true
                },
                data: [
                    {
                        type: "column",
                        showInLegend: true,
                        legendText: "Qeyri Rəsmi Məbləğ",
                        name: "Qeyri Rəsmi Məbləğ",
                        dataPoints: [
                            {
                                label: dataPoints[0].label,
                                y: dataPoints[0].y["Illegal Amount"]
                            }
                        ]
                    },
                    {
                        type: "column",
                        showInLegend: true,
                        legendText: "Rəsmi Məbləğ",
                        name: "Rəsmi Məbləğ",
                        dataPoints: [
                            {
                                label: dataPoints[0].label,
                                y: dataPoints[0].y["Amount"]
                            }
                        ]
                    },
                    {
                        type: "column",
                        showInLegend: true,
                        legendText: "ƏDV",
                        name: "ƏDV",
                        dataPoints: [
                            {
                                label: dataPoints[0].label,
                                y: dataPoints[0].y["VAT"]
                            }
                        ]
                    },
                    {
                        type: "column",
                        showInLegend: true,
                        legendText: "Toplam",
                        name: "Toplam",
                        dataPoints: [
                            {
                                label: dataPoints[0].label,
                                y: dataPoints[0].y["Total All"]
                            }
                        ]
                    }
                ]
            });

            chart.render();
        });
    </script>
@endsection