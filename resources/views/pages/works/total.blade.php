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
<div>
    <form action="{{ route('total') }}" method="get">
        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
            <label class="d-block" for="paidAtFilter">{{trans('translates.fields.created_at')}}</label>
            <input class="form-control custom-daterange mb-1" id="paidAtFilter" type="text" readonly name="paid_at" value="{{$filters['paid_at']}}">
            <input type="checkbox" name="check-paid_at" id="check-paid_at" @if(request()->has('check-paid_at')) checked @endif> <label for="check-paid_at">@lang('translates.filters.filter_by')</label>
        </div>
        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.paid_at')}}</label>
            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
        </div>
        <button type="submit" class="btn btn-primary">Filtrele</button>
    </form>
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
    <div class="work-stats">
        <div>
            <h1>Ödənmiş Qeyri-Rəsmi Məbləğ</h1>
            <h2>{{ $totalPaidIllegal }}</h2>
        </div>
        <div>
            <h1>Ödənmiş Rəsmi Məbləğ</h1>
            <h2>{{ $totalPaidAmount }}</h2>
        </div>
        <div>
            <h1>Ödənmiş ƏDV Məbləğ</h1>
            <h2>{{ $totalPaidVat }}</h2>
        </div>
        <div>
            <h1>Ödənmiş Ümumi məbləğ</h1>
            <h2>{{ $totalPaidAll }}</h2>
        </div>
    </div>


    <div class="work-stats">
        <div>
            <h1>Aksizli Mallar Satış</h1>
            <h2><span>Rəsmi məbləğ</span>:{{ $AMBGIAmount }}</h2>
            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $AMBGIIllegal }}</h2>
            <h2><span>ƏDV məbləğ</span>:{{ $AMBGIVat }}</h2>
            <h2><span>Toplam məbləğ</span>:{{ $totalSalesAMBGI }}</h2>
        </div>
        <div class="col-md-8">
            <h1>Aksizli Mallar Ödənənlər</h1>
            <h2><span>Rəsmi məbləğ</span>:{{ $AMBGIPaidAmount }}</h2>
            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $AMBGIPaidIllegal }}</h2>
            <h2><span>ƏDV məbləğ</span>:{{ $AMBGIPaidVat }}</h2>
            <h2><span>Toplam məbləğ</span>:{{ $totalAMBGI }}</h2>
        </div>

    </div>
    <div class="work-stats">
        <div >
            <h1>Bakı Baş Gömrük Satış</h1>
            <h2><span>Rəsmi məbləğ</span>:{{ $BBGIAmount }}</h2>
            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $BBGIIllegal }}</h2>
            <h2><span>ƏDV məbləğ</span>:{{ $BBGIVat }}</h2>
            <h2><span>Toplam məbləğ</span>:{{ $totalSalesBBGI }}</h2>
        </div>
        <div class="col-md-8">
            <h1>Bakı Baş Gömrük Ödənənlər</h1>
            <h2><span>Rəsmi məbləğ</span>:{{ $BBGIPaidAmount }}</h2>
            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $BBGIPaidIllegal }}</h2>
            <h2><span>ƏDV məbləğ</span>:{{ $BBGIPaidVat }}</h2>
            <h2><span>Toplam məbləğ</span>:{{ $totalBBGI }}</h2>
        </div>
    </div>
    <div class="work-stats">
        <div >
            <h1>Hava Nəqliyyatı Baş Gömrük Satış</h1>
            <h2><span>Rəsmi məbləğ</span>:{{ $HNBGIAmount }}</h2>
            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $HNBGIIllegal }}</h2>
            <h2><span>ƏDV məbləğ</span>:{{ $HNBGIVat }}</h2>
            <h2><span>Toplam məbləğ</span>:{{ $totalSalesHNBGI }}</h2>
        </div>
        <div class="col-md-8">
            <h1>Hava Nəqliyyatı Baş Gömrük Ödənənlər</h1>
            <h2><span>Rəsmi məbləğ</span>:{{ $HNBGIPaidAmount }}</h2>
            <h2><span>Qeyri-rəsmi məbləğ</span>:{{ $HNBGIPaidIllegal }}</h2>
            <h2><span>ƏDV məbləğ</span>:{{ $HNBGIPaidVat }}</h2>
            <h2><span>Toplam məbləğ</span>:{{ $totalHNBGI }}</h2>
        </div>
    </div>
</div>
    <table class="table table-striped table-dark">
        <thead>
        <tr>
            <th scope="col" colspan="15" class="text-center">Kassa Hesabatı</th>
        </tr>
        <tr>
            <th scope="col"></th>
            <th scope="col" class="text-center">Tarix</th>
            <th scope="col" colspan="5" class="text-center">NAĞD</th>
            <th scope="col" colspan="6" class="text-center">BANK</th>
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
            <th scope="col">AMBGI</th>
            <th scope="col">BBGI</th>
            <th scope="col">HNBGI</th>
            <th scope="col">Mərkəzi Kassa</th>
            <th scope="col">Cəmi</th>
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
            <th scope="col">0</th>
            <th scope="col">0</th>
            <th scope="col">0</th>
        </tr>
        <tr>
            <th scope="col">1</th>
            <th scope="col">Satışdan gəlir</th>
            <th scope="col">{{$totalAMBGICash}}</th>
            <th scope="col">{{$totalBBGICash}}</th>
            <th scope="col">{{$totalHNBGICash}}</th>
            <th scope="col">0</th>
            <th scope="col">{{$RigelTotal + $DeclareTotal + $GarantTotal + $MobilTotal + $TedoraTotal + $MindTotal + $AsazaTotal}}</th>
            <th scope="col">0</th>
            <th scope="col">{{$totalAMBGI - $totalAMBGICash}}</th>
            <th scope="col">{{$totalBBGI - $totalBBGICash}}</th>
            <th scope="col">{{$totalHNBGI - $totalHNBGICash}}</th>
            <th scope="col">0</th>
            <th scope="col">{{$RigelBankTotal + $DeclareBankTotal + $GarantBankTotal + $MobilBankTotal + $TedoraBankTotal + $MindBankTotal + $AsazaBankTotal}}</th>
            <th scope="col">{{ round($totalPaidVat, 2) }}</th>
            <th scope="col">{{ round($totalPaidAll, 2) }}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td>Asaza FLKS</td>
            <td>{{ $AMBGICashTotals['ASAZA'] }}</td>
            <td>{{ $BBGICashTotals['ASAZA'] }}</td>
            <td>{{ $HNBGICashTotals['ASAZA'] }}</td>
            <td>0</td>
            <td>{{$AsazaTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['ASAZA'] }}</td>
            <td>{{ $BBGIBankTotals['ASAZA'] }}</td>
            <td>{{ $HNBGIBankTotals['ASAZA'] }}</td>
            <td>0</td>
            <td>{{$AsazaBankTotal}}</td>
            <td>0</td>
            <td>{{$AsazaBankTotal + $AsazaTotal}}</td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td>Declare Group</td>
            <td>{{ $AMBGICashTotals['DECLARE'] }}</td>
            <td>{{ $BBGICashTotals['DECLARE'] }}</td>
            <td>{{ $HNBGICashTotals['DECLARE'] }}</td>
            <td>0</td>
            <td>{{$DeclareTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['DECLARE'] }}</td>
            <td>{{ $BBGIBankTotals['DECLARE'] }}</td>
            <td>{{ $HNBGIBankTotals['DECLARE'] }}</td>
            <td>0</td>
            <td>{{$DeclareBankTotal}}</td>
            <td>0</td>
            <td>{{$DeclareBankTotal + $DeclareTotal}}</td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td>Garant Broker</td>
            <td>{{ $AMBGICashTotals['GARANT'] }}</td>
            <td>{{ $BBGICashTotals['GARANT'] }}</td>
            <td>{{ $HNBGICashTotals['GARANT'] }}</td>
            <td>0</td>
            <td>{{$GarantTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['GARANT'] }}</td>
            <td>{{ $BBGIBankTotals['GARANT'] }}</td>
            <td>{{ $HNBGIBankTotals['GARANT'] }}</td>
            <td>0</td>
            <td>{{$GarantBankTotal}}</td>
            <td>0</td>
            <td>{{$GarantBankTotal + $GarantTotal}}</td>
        </tr>
        <tr>
            <th scope="row">4</th>
            <td>Mind Services</td>
            <td>{{ $AMBGICashTotals['MIND'] }}</td>
            <td>{{ $BBGICashTotals['MIND'] }}</td>
            <td>{{ $HNBGICashTotals['MIND'] }}</td>
            <td>0</td>
            <td>{{$MindTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['MIND'] }}</td>
            <td>{{ $BBGIBankTotals['MIND'] }}</td>
            <td>{{ $HNBGIBankTotals['MIND'] }}</td>
            <td>0</td>
            <td>{{$MindBankTotal}}</td>
            <td>0</td>
            <td>{{$MindBankTotal + $MindTotal}}</td>
        </tr>
        <tr>
            <th scope="row">5</th>
            <td>Rigel Group</td>
            <td>{{ $AMBGICashTotals['RIGEL'] }}</td>
            <td>{{ $BBGICashTotals['RIGEL'] }}</td>
            <td>{{ $HNBGICashTotals['RIGEL'] }}</td>
            <td>0</td>
            <td>{{$RigelTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['RIGEL'] }}</td>
            <td>{{ $BBGIBankTotals['RIGEL'] }}</td>
            <td>{{ $HNBGIBankTotals['RIGEL'] }}</td>
            <td>0</td>
            <td>{{$RigelBankTotal}}</td>
            <td>0</td>
            <td>{{$RigelBankTotal + $RigelTotal}}</td>
        </tr>
        <tr>
            <th scope="row">6</th>
            <td>Tedora Group</td>
            <td>{{ $AMBGICashTotals['TEDORA'] }}</td>
            <td>{{ $BBGICashTotals['TEDORA'] }}</td>
            <td>{{ $HNBGICashTotals['TEDORA'] }}</td>
            <td>0</td>
            <td>{{$TedoraTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['TEDORA'] }}</td>
            <td>{{ $BBGIBankTotals['TEDORA'] }}</td>
            <td>{{ $HNBGIBankTotals['TEDORA'] }}</td>
            <td>0</td>
            <td>{{$TedoraBankTotal}}</td>
            <td>0</td>
            <td>{{$TedoraBankTotal + $TedoraTotal}}</td>
        </tr>
        <tr>
            <th scope="row">7</th>
            <td>Mobil Broker</td>
            <td>{{ $AMBGICashTotals['MOBIL'] }}</td>
            <td>{{ $BBGICashTotals['MOBIL'] }}</td>
            <td>{{ $HNBGICashTotals['MOBIL'] }}</td>
            <td>0</td>
            <td>{{$MobilTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['MOBIL'] }}</td>
            <td>{{ $BBGIBankTotals['MOBIL'] }}</td>
            <td>{{ $HNBGIBankTotals['MOBIL'] }}</td>
            <td>0</td>
            <td>{{$MobilBankTotal}}</td>
            <td>0</td>
            <td>{{$MobilBankTotal + $MobilTotal}}</td>
        </tr>
        <tr>
            <th scope="row">8</th>
            <td>Mobil Logistics</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>0</td>
            <td>{{$logSales}}</td>
            <td>{{$logPurchase}}</td>
            <td>0</td>
            <td>0</td>
        </tr>
        </tbody>
    </table>

@endsection
