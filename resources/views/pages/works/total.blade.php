@extends('layouts.main')

@section('title', __('translates.navbar.total'))

@section('style')
    <style>
        .table td, .table th { vertical-align: middle !important; }
        .table tr { cursor: pointer; }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            border: 1px solid #e3e6f0;
            padding: 16px 20px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,.06);
        }
        .stat-card .stat-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #6c757d;
            margin-bottom: 4px;
        }
        .stat-card .stat-value {
            font-size: 22px;
            font-weight: 700;
            color: #2c3e50;
        }
        .stat-card.stat-green  { border-left: 4px solid #28a745; }
        .stat-card.stat-blue   { border-left: 4px solid #007bff; }
        .stat-card.stat-orange { border-left: 4px solid #fd7e14; }
        .stat-card.stat-purple { border-left: 4px solid #6f42c1; }
        .stat-card.stat-teal   { border-left: 4px solid #20c997; }
        .stat-card.stat-red    { border-left: 4px solid #dc3545; }

        .stats-section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #495057;
            border-bottom: 2px solid #e3e6f0;
            padding-bottom: 6px;
            margin: 20px 0 12px;
        }

        .company-stat-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px 16px;
        }
        .company-stat-card .cs-label {
            font-size: 11px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
        }
        .company-stat-card .cs-value {
            font-size: 15px;
            font-weight: 600;
            color: #343a40;
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

<div class="px-3 pb-4">

    {{-- Filter Card --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <form action="{{ route('total') }}" method="get">
                <div class="row align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="d-block small font-weight-bold" for="paidAtFilter">{{trans('translates.fields.paid_at')}}</label>
                        <input class="form-control form-control-sm custom-daterange mb-1" id="paidAtFilter" type="text" readonly name="paid_at" value="{{$filters['paid_at']}}">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check-paid_at" id="check-paid_at" @if(request()->has('check-paid_at')) checked @endif>
                            <label class="custom-control-label small" for="check-paid_at">@lang('translates.filters.filter_by')</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="d-block small font-weight-bold" for="createdAtFilter">{{trans('translates.fields.created_at')}}</label>
                        <input class="form-control form-control-sm custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif>
                            <label class="custom-control-label small" for="check-created_at">@lang('translates.filters.filter_by')</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="d-block small font-weight-bold" for="vatDateFilter">{{trans('translates.fields.vat_date')}}</label>
                        <input class="form-control form-control-sm custom-daterange mb-1" id="vatDateFilter" type="text" readonly name="vat_date" value="{{$filters['vat_date']}}">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="check-vat_date" id="check-vat_date" @if(request()->has('check-vat_date')) checked @endif>
                            <label class="custom-control-label small" for="check-vat_date">@lang('translates.filters.filter_by')</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="fa fa-filter mr-1"></i> Filtrele
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Ayın əvvəlindən --}}
    <div class="stats-section-title">Ayın əvvəlindən</div>
    <div class="row">
        <div class="col-6 col-md-3">
            <div class="stat-card stat-red">
                <div class="stat-label">Qeyri-Rəsmi Məbləğ</div>
                <div class="stat-value">{{ $totalIllegalAmount }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card stat-blue">
                <div class="stat-label">Rəsmi Məbləğ</div>
                <div class="stat-value">{{ $totalAmount }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card stat-orange">
                <div class="stat-label">ƏDV Məbləğ</div>
                <div class="stat-value">{{ $totalVat }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card stat-purple">
                <div class="stat-label">Ümumi məbləğ</div>
                <div class="stat-value">{{ $totalAll }}</div>
            </div>
        </div>
    </div>

    {{-- Ödənmiş --}}
    <div class="stats-section-title">Ödənmiş</div>
    <div class="row">
        <div class="col-6 col-md-3">
            <div class="stat-card stat-red">
                <div class="stat-label">Qeyri-Rəsmi Məbləğ</div>
                <div class="stat-value">{{ $totalPaidIllegal }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card stat-green">
                <div class="stat-label">Rəsmi Məbləğ</div>
                <div class="stat-value">{{ $totalPaidAmount }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card stat-orange">
                <div class="stat-label">ƏDV Məbləğ</div>
                <div class="stat-value">{{ $totalPaidVat }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card stat-purple">
                <div class="stat-label">Ümumi məbləğ</div>
                <div class="stat-value">{{ $totalPaidAll }}</div>
            </div>
        </div>
    </div>

    {{-- Logistika --}}
    <div class="stats-section-title">Logistika</div>
    <div class="row">
        <div class="col-6 col-md-3">
            <div class="stat-card stat-teal">
                <div class="stat-label">Ödənmiş Ümumi məbləğ</div>
                <div class="stat-value">{{ $logPurchase }}</div>
            </div>
        </div>
    </div>

    {{-- Şirkət bölmələri --}}
    @foreach([
        ['title' => 'Aksizli Mallar (AMBGİ)', 'sale' => ['amount' => $AMBGIAmount, 'illegal' => $AMBGIIllegal, 'vat' => $AMBGIVat, 'total' => $totalSalesAMBGI], 'paid' => ['amount' => $AMBGIPaidAmount, 'illegal' => $AMBGIPaidIllegal, 'vat' => $AMBGIPaidVat, 'total' => $totalAMBGI]],
        ['title' => 'Bakı Baş Gömrük (BBGİ)',  'sale' => ['amount' => $BBGIAmount,  'illegal' => $BBGIIllegal,  'vat' => $BBGIVat,  'total' => $totalSalesBBGI],  'paid' => ['amount' => $BBGIPaidAmount,  'illegal' => $BBGIPaidIllegal,  'vat' => $BBGIPaidVat,  'total' => $totalBBGI]],
        ['title' => 'Hava Nəqliyyatı (HNBGİ)', 'sale' => ['amount' => $HNBGIAmount, 'illegal' => $HNBGIIllegal, 'vat' => $HNBGIVat, 'total' => $totalSalesHNBGI], 'paid' => ['amount' => $HNBGIPaidAmount, 'illegal' => $HNBGIPaidIllegal, 'vat' => $HNBGIPaidVat, 'total' => $totalHNBGI]],
    ] as $section)
    <div class="stats-section-title">{{ $section['title'] }}</div>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header py-2 small font-weight-bold bg-light">Satış</div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <div class="company-stat-card">
                                <div class="cs-label">Rəsmi</div>
                                <div class="cs-value">{{ $section['sale']['amount'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="company-stat-card">
                                <div class="cs-label">Qeyri-rəsmi</div>
                                <div class="cs-value">{{ $section['sale']['illegal'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="company-stat-card">
                                <div class="cs-label">ƏDV</div>
                                <div class="cs-value">{{ $section['sale']['vat'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="company-stat-card" style="border-left:3px solid #6f42c1">
                                <div class="cs-label">Toplam</div>
                                <div class="cs-value font-weight-bold">{{ $section['sale']['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header py-2 small font-weight-bold bg-light">Ödənənlər</div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <div class="company-stat-card">
                                <div class="cs-label">Rəsmi</div>
                                <div class="cs-value">{{ $section['paid']['amount'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="company-stat-card">
                                <div class="cs-label">Qeyri-rəsmi</div>
                                <div class="cs-value">{{ $section['paid']['illegal'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="company-stat-card">
                                <div class="cs-label">ƏDV</div>
                                <div class="cs-value">{{ $section['paid']['vat'] }}</div>
                            </div>
                        </div>
                        <div class="col-6 mb-2">
                            <div class="company-stat-card" style="border-left:3px solid #28a745">
                                <div class="cs-label">Toplam</div>
                                <div class="cs-value font-weight-bold">{{ $section['paid']['total'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

</div>
    <div class="stats-section-title px-3">Kassa Hesabatı</div>
    <div class="card mx-3 mb-4">
    <div class="table-responsive">
    <table class="table table-bordered table-hover mb-0" style="font-size:13px">
        <thead class="thead-dark">
        <tr>
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
            <th scope="col">{{ round($totalAMBGICash + $totalBBGICash + $totalHNBGICash, 2) }}</th>
            <th scope="col">0</th>
            <th scope="col">{{$totalAMBGI - $totalAMBGICash}}</th>
            <th scope="col">{{$totalBBGI - $totalBBGICash}}</th>
            <th scope="col">{{$totalHNBGI - $totalHNBGICash}}</th>
            <th scope="col">0</th>
            <th scope="col">{{ round(($totalAMBGI - $totalAMBGICash) + ($totalBBGI - $totalBBGICash) + ($totalHNBGI - $totalHNBGICash), 2) }}</th>
            <th scope="col">{{ round($totalPaidVat, 2) }}</th>
            <th scope="col">{{ round($totalAMBGI + $totalBBGI + $totalHNBGI, 2) }}</th>
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
            <th scope="row">7</th>
            <td>Mobil Express</td>
            <td>{{ $AMBGICashTotals['MOBEX'] }}</td>
            <td>{{ $BBGICashTotals['MOBEX'] }}</td>
            <td>{{ $HNBGICashTotals['MOBEX'] }}</td>
            <td>0</td>
            <td>{{$MobexTotal}}</td>
            <td>0</td>
            <td>{{ $AMBGIBankTotals['MOBEX'] }}</td>
            <td>{{ $BBGIBankTotals['MOBEX'] }}</td>
            <td>{{ $HNBGIBankTotals['MOBEX'] }}</td>
            <td>0</td>
            <td>{{$MobexBankTotal}}</td>
            <td>0</td>
            <td>{{$MobexBankTotal + $MobexTotal}}</td>
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
    </div>
    </div>

    <div class="card mx-3 mt-2 mb-4" id="company-payments-card">
        <div class="card-header">
            İllik / Son il üzrə toplam
            <small class="text-muted" id="company-payments-since"></small>
        </div>
        <div class="card-body p-0">
            <div class="p-3" id="company-payments-loading">Yüklənir...</div>
            
            <!-- Totals section -->
            <div class="p-3 d-none" id="company-payments-totals">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>Qaimə toplam:</strong>
                        <div id="qaima-total" class="h5">0.00</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Nağd toplam:</strong>
                        <div id="nagd-total" class="h5">0.00</div>
                    </div>
                    <div class="col-md-4">
                        <strong>Ümumi toplam:</strong>
                        <div id="total-amount" class="h5">0.00</div>
                    </div>
                </div>
                <div class="alert alert-danger d-none" id="limit-warning">
                    <strong>⚠️ Xəbərdarlıq:</strong> 200,000 limiti keçildi!
                </div>
            </div>
            
            <div class="table-responsive d-none" id="company-payments-wrapper">
                <table class="table table-striped mb-0" id="company-payments-table">
                    <thead>
                    <tr>
                        <th style="width:70px">#</th>
                        <th>Şirkət</th>
                        <th class="text-right">Ödəniş cəmi</th>
                    </tr>
                    </thead>
                    <tbody><!-- JS dolduracaq --></tbody>
                </table>
            </div>
            <div class="p-3 text-danger d-none" id="company-payments-error"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const url = "{{ route('reports.company_payments_last_year') }}";
            const loadingEl = document.getElementById('company-payments-loading');
            const wrapperEl = document.getElementById('company-payments-wrapper');
            const totalsEl = document.getElementById('company-payments-totals');
            const tbodyEl   = document.querySelector('#company-payments-table tbody');
            const errorEl   = document.getElementById('company-payments-error');
            const sinceEl   = document.getElementById('company-payments-since');
            const cardEl    = document.getElementById('company-payments-card');
            const limitWarningEl = document.getElementById('limit-warning');

            function fmt(n){
                if(n === null || n === undefined || isNaN(n)) return '0.00';
                // Azərbaycan formatında boşluq min separatoru, '.' isə onluq
                return Number(n).toLocaleString('az-AZ', {minimumFractionDigits:2, maximumFractionDigits:2});
            }

            // Tarix hesablaması: 11 ay əvvəlki ayın 1-i (00:00:00) - bugün (23:59:59)
            function calculateDateRange() {
                const now = new Date();
                
                // End date = bugün 23:59:59
                const endDate = new Date(now);
                endDate.setHours(23, 59, 59, 999);
                
                // Start date = 11 ay əvvəlki ayın 1-i 00:00:00
                const startDate = new Date(now);
                startDate.setMonth(startDate.getMonth() - 11);
                startDate.setDate(1);
                startDate.setHours(0, 0, 0, 0);
                
                // Tarix formatı: YYYY-MM-DD HH:mm:ss (timezone problemi olmasın deyə)
                function formatDateForAPI(date) {
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    const hours = String(date.getHours()).padStart(2, '0');
                    const minutes = String(date.getMinutes()).padStart(2, '0');
                    const seconds = String(date.getSeconds()).padStart(2, '0');
                    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
                }
                
                return {
                    start: formatDateForAPI(startDate),
                    end: formatDateForAPI(endDate)
                };
            }

            async function loadCompanyPayments(){
                try{
                    loadingEl.classList.remove('d-none');
                    wrapperEl.classList.add('d-none');
                    totalsEl.classList.add('d-none');
                    errorEl.classList.add('d-none');
                    errorEl.textContent = '';
                    limitWarningEl.classList.add('d-none');
                    
                    // Limit xəbərdarlığı üçün kartın border/background-u təmizlə
                    if(cardEl) {
                        cardEl.style.border = '';
                        cardEl.style.backgroundColor = '';
                    }

                    const dateRange = calculateDateRange();
                    const urlWithParams = new URL(url, window.location.origin);
                    urlWithParams.searchParams.set('start_date', dateRange.start);
                    urlWithParams.searchParams.set('end_date', dateRange.end);

                    const res = await fetch(urlWithParams.toString(), {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    });

                    if(!res.ok){
                        throw new Error('Server xətası: ' + res.status);
                    }

                    const json = await res.json();
                    const rows = json.data || [];
                    
                    // Tarix məlumatını göstər
                    if(json.since && json.until) {
                        sinceEl.textContent = ` (${json.since} - ${json.until})`;
                    } else if(json.since) {
                        sinceEl.textContent = ` (başlanğıc: ${json.since})`;
                    } else {
                        sinceEl.textContent = '';
                    }

                    // Totals göstər
                    const qaimaTotal = json.qaima_total || 0;
                    const nagdTotal = json.nagd_total || 0;
                    const total = json.total || 0;
                    
                    document.getElementById('qaima-total').textContent = fmt(qaimaTotal);
                    document.getElementById('nagd-total').textContent = fmt(nagdTotal);
                    document.getElementById('total-amount').textContent = fmt(total);
                    totalsEl.classList.remove('d-none');

                    // 200,000 limiti yoxla
                    if(total > 200000) {
                        limitWarningEl.classList.remove('d-none');
                        if(cardEl) {
                            cardEl.style.border = '3px solid #dc3545';
                            cardEl.style.backgroundColor = '#fff5f5';
                        }
                    }

                    // Tbody təmizlə
                    tbodyEl.innerHTML = '';

                    if(rows.length === 0){
                        tbodyEl.innerHTML = '<tr><td colspan="3" class="text-center text-muted p-3">Məlumat tapılmadı</td></tr>';
                    }else{
                        rows.forEach((r, i) => {
                            const tr = document.createElement('tr');

                            const tdIdx = document.createElement('td');
                            tdIdx.textContent = (i+1).toString();

                            const tdCompany = document.createElement('td');
                            tdCompany.textContent = r.company_name ?? '—';

                            const tdTotal = document.createElement('td');
                            tdTotal.className = 'text-right';
                            tdTotal.textContent = fmt(r.total_payment);

                            tr.appendChild(tdIdx);
                            tr.appendChild(tdCompany);
                            tr.appendChild(tdTotal);

                            tbodyEl.appendChild(tr);
                        });
                    }

                    wrapperEl.classList.remove('d-none');
                }catch(e){
                    errorEl.textContent = e.message || 'Bilinməyən xəta baş verdi.';
                    errorEl.classList.remove('d-none');
                }finally{
                    loadingEl.classList.add('d-none');
                }
            }

            // İlk yükləmədə çək
            loadCompanyPayments();

            // (İstəyə bağlı) üst form submit olunanda tam səhifə refresh əvəzinə yenilə:
            const filterForm = document.querySelector('form[action="{{ route('total') }}"]');
            if(filterForm){
                filterForm.addEventListener('submit', function(ev){
                    // Əgər bu cədvəl də filterlərdən asılı olsun istəyirsənsə,
                    // burada url-ə query string əlavə edib loadCompanyPayments-i ona görə yazmaq lazımdır.
                    // Hazırda bu cədvəl son 1 ilə görədir deyə, default davranışı saxlayırıq.
                    // Evita edirsənsə, aşağıdakı iki sətri aç və uyğunlaşdır:
                    // ev.preventDefault();
                    // loadCompanyPayments();
                });
            }
        });
    </script>
@endpush

