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

    <div class="work-stats">
        <div>
            <h1>Ayın əvvəlindən Qeyri-Rəsmi Məbləğ</h1>
            <h2>{{ $totalIllegalAmount }}</h2>
        </div>
        <div>
            <h1>Ayın əvvəlindən Rəsmi Məbləğ</h1>
            <h2>{{ $totalAmount }}</h2>
        </div>
        <div>
            <h1>Ayın əvvəlindən ƏDV Məbləğ</h1>
            <h2>{{ $totalVat }}</h2>
        </div>
        <div>
            <h1>Ümumi məbləğ</h1>
            <h2>{{ $totalAll }}</h2>
        </div>
    </div>
{{--    <div class="col-6 col-md-6">--}}
{{--        <div id="chartContainer" style="height: 370px; width: 100%;"></div>--}}
{{--    </div>--}}
{{--    <div class="col-6 col-md-6">--}}
{{--        <div id="chartContainer2" style="height: 370px; width: 100%;"></div>--}}
{{--    </div>--}}

@endsection

@section('scripts')
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var dataPoints = @json($dataPoints);
            {{--var dataPaidPoints = @json($dataPaidPoints);--}}

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
                    prefix: "₼",
                    labelFormatter: addSymbols
                },
                toolTip: {
                    shared: true
                },
                legend: {
                    cursor: "pointer",
                    itemclick: toggleDataSeries
                },
                data: [
                    {
                        type: "column",
                        name: "Total Amount",
                        showInLegend: true,
                        xValueFormatString: "MMM YYYY",
                        yValueFormatString: "₼#,##0",
                        dataPoints: dataPoints
                    }
                ]
            });
            chart.render();

            // var paidChart = new CanvasJS.Chart("chartContainer2", {
            //     animationEnabled: true,
            //     theme: "light2",
            //     title: {
            //         text: "Aylıq Ödənilmə Cədvəli"
            //     },
            //     axisX: {
            //         interval: 1,
            //         intervalType: "month",
            //         valueFormatString: "MMM"
            //     },
            //     axisY: {
            //         prefix: "₼",
            //         labelFormatter: addSymbols
            //     },
            //     toolTip: {
            //         shared: true
            //     },
            //     legend: {
            //         cursor: "pointer",
            //         itemclick: toggleDataSeries
            //     },
            //     data: [
            //         {
            //             type: "column",
            //             name: "Total Amount",
            //             showInLegend: true,
            //             xValueFormatString: "MMM YYYY",
            //             yValueFormatString: "₼#,##0",
            //             dataPoints: dataPaidPoints
            //         }
            //     ]
            // });
            // paidChart.render();

            function addSymbols(e) {
                var suffixes = ["", "K", "M", "B"];
                var order = Math.max(Math.floor(Math.log(Math.abs(e.value)) / Math.log(1000)), 0);

                if (order > suffixes.length - 1)
                    order = suffixes.length - 1;

                var suffix = suffixes[order];
                return CanvasJS.formatNumber(e.value / Math.pow(1000, order)) + suffix;
            }

            function toggleDataSeries(e) {
                if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                    e.dataSeries.visible = false;
                } else {
                    e.dataSeries.visible = true;
                }
                e.chart.render();
            }
        }
    </script>
@endsection