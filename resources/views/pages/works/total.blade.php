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
            <h2>{{ $totalIllegalAmount }}</h2>
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ayın əvvəlindən Rəsmi Məbləğ</h1>--}}
            <h2>{{ $totalAmount }}</h2>
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
{{--    <div class="work-stats">--}}
{{--        <div>--}}
{{--            <h1>Ödənmiş Qeyri-Rəsmi Məbləğ</h1>--}}
{{--            <h2>{{ $totalPaidIllegal }}</h2>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ödənmiş Rəsmi Məbləğ</h1>--}}
{{--            <h2>{{ $totalPaidAmount }}</h2>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ödənmiş ƏDV Məbləğ</h1>--}}
{{--            <h2>{{ $totalPaidVat }}</h2>--}}
{{--        </div>--}}
{{--        <div>--}}
{{--            <h1>Ödənmiş Ümumi məbləğ</h1>--}}
{{--            <h2>{{ $totalPaidAll }}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}



{{--    <div class="col-6 col-md-6">--}}
{{--        <div id="chartContainer" style="height: 370px; width: 100%;"></div>--}}
{{--    </div>--}}
{{--    <div class="work-stats">--}}
{{--        <div>--}}
{{--            <h1>Aksizli Mallar</h1>--}}
{{--            <h2><span>Rəsmi məbləğ</span>:{{ $AMBGIPaidAmount }}</h2>--}}
{{--            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $AMBGIPaidIllegal }}</h2>--}}
{{--            <h2><span>ƏDV məbləğ</span>:{{ $AMBGIPaidVat }}</h2>--}}
{{--            <h2><span>Toplam məbləğ</span>:{{ $totalAMBGI }}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="work-stats">--}}
{{--        <div>--}}
{{--            <h1>Bakı Baş Gömrük</h1>--}}
{{--            <h2><span>Rəsmi məbləğ</span>:{{ $BBGIPaidAmount }}</h2>--}}
{{--            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $BBGIPaidIllegal }}</h2>--}}
{{--            <h2><span>ƏDV məbləğ</span>:{{ $BBGIPaidVat }}</h2>--}}
{{--            <h2><span>Toplam məbləğ</span>:{{ $totalBBGI }}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div class="work-stats">--}}
{{--        <div>--}}
{{--            <h1>Hava Nəqliyyatı Baş Gömrük</h1>--}}
{{--            <h2><span>Rəsmi məbləğ</span>:{{ $HNBGIPaidAmount }}</h2>--}}
{{--            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $HNBGIPaidIllegal }}</h2>--}}
{{--            <h2><span>ƏDV məbləğ</span>:{{ $HNBGIPaidVat }}</h2>--}}
{{--            <h2><span>Toplam məbləğ</span>:{{ $totalHNBGI }}</h2>--}}
{{--        </div>--}}
{{--    </div>--}}

    <table class="table table-striped table-dark">
        <thead>
        <tr>
            <th scope="col" colspan="12" class="text-center">Kassa Hesabatı</th>
        </tr>
        <tr>
            <th scope="col"></th>
            <th scope="col" class="text-center">Tarix</th>
            <th scope="col" colspan="5" class="text-center">NAĞD</th>
            <th scope="col" colspan="3" class="text-center">BANK</th>
            <th scope="col">ƏDV</th>
            <th scope="col">CƏMİ</th>
        </tr>
        <tr>
            <th scope="col">No</th>
            <th scope="col"></th>
            <th scope="col">AMBGİ</th>
            <th scope="col">BBGİ</th>
            <th scope="col">HNBGİ</th>
            <th scope="col">Mərkəzi Kassa</th>
            <th scope="col">Cəmi</th>
            <th scope="col">Kart</th>
            <th scope="col">Handle</th>
            <th scope="col">Handle</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        <tr>
            <th scope="col">1</th>
            <th scope="col">İlkin Vəsait</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
        </tr>
        <tr>
            <th scope="col">1</th>
            <th scope="col">Satışdan gəlir</th>
            <th scope="col"></th>
            <th scope="col"></th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Asaza FLKS</td>
            <td>{{$AMBGIASAZACash}}</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
            <td>@mdo</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Declare Group</td>
            <td>{{$AMBGIDECLARECash}}</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
            <td>@fat</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Garant Broker</td>
            <td>{{$AMBGIGARANTCash}}</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Mind Services</td>
            <td>{{$AMBGIMINDCash}}</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Rigel Group</td>
            <td>{{$AMBGIRIGELCash}}</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Tedora Group</td>
            <td>{{$AMBGITEDORACash}}</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Mobil Broker</td>
            <td>{{$AMBGIMOBILCash}}</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
            <td>@twitter</td>
        </tr>
        </tbody>
    </table>

@endsection

@section('scripts')
{{--    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>--}}
{{--    <script>--}}
{{--        window.addEventListener('DOMContentLoaded', (event) => {--}}
{{--            var dataPoints = {!! json_encode($dataPoints) !!};--}}

{{--            var chart = new CanvasJS.Chart("chartContainer", {--}}
{{--                animationEnabled: true,--}}
{{--                theme: "light2",--}}
{{--                title: {--}}
{{--                    text: "Aylıq Satış Cədvəli"--}}
{{--                },--}}
{{--                axisX: {--}}
{{--                    interval: 1,--}}
{{--                    intervalType: "month",--}}
{{--                    valueFormatString: "MMM"--}}
{{--                },--}}
{{--                axisY: {--}}
{{--                    prefix: "₼"--}}
{{--                },--}}
{{--                toolTip: {--}}
{{--                    shared: true--}}
{{--                },--}}
{{--                data: [--}}
{{--                    {--}}
{{--                        type: "column",--}}
{{--                        showInLegend: true,--}}
{{--                        legendText: "Qeyri Rəsmi Məbləğ",--}}
{{--                        name: "Qeyri Rəsmi Məbləğ",--}}
{{--                        dataPoints: [--}}
{{--                            {--}}
{{--                                label: dataPoints[0].label,--}}
{{--                                y: dataPoints[0].y["Illegal Amount"]--}}
{{--                            }--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    {--}}
{{--                        type: "column",--}}
{{--                        showInLegend: true,--}}
{{--                        legendText: "Rəsmi Məbləğ",--}}
{{--                        name: "Rəsmi Məbləğ",--}}
{{--                        dataPoints: [--}}
{{--                            {--}}
{{--                                label: dataPoints[0].label,--}}
{{--                                y: dataPoints[0].y["Amount"]--}}
{{--                            }--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    {--}}
{{--                        type: "column",--}}
{{--                        showInLegend: true,--}}
{{--                        legendText: "ƏDV",--}}
{{--                        name: "ƏDV",--}}
{{--                        dataPoints: [--}}
{{--                            {--}}
{{--                                label: dataPoints[0].label,--}}
{{--                                y: dataPoints[0].y["VAT"]--}}
{{--                            }--}}
{{--                        ]--}}
{{--                    },--}}
{{--                    {--}}
{{--                        type: "column",--}}
{{--                        showInLegend: true,--}}
{{--                        legendText: "Toplam",--}}
{{--                        name: "Toplam",--}}
{{--                        dataPoints: [--}}
{{--                            {--}}
{{--                                label: dataPoints[0].label,--}}
{{--                                y: dataPoints[0].y["Total All"]--}}
{{--                            }--}}
{{--                        ]--}}
{{--                    }--}}
{{--                ]--}}
{{--            });--}}

{{--            chart.render();--}}
{{--        });--}}
{{--    </script>--}}
@endsection