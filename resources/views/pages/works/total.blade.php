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
            <label class="d-block" for="paidAtFilter">{{trans('translates.fields.paid_at')}}</label>
            <input class="form-control custom-daterange mb-1" id="paidAtFilter" type="text" readonly name="paid_at" value="{{$filters['paid_at']}}">
            <input type="checkbox" name="check-paid_at" id="check-paid_at" @if(request()->has('check-paid_at')) checked @endif> <label for="check-paid_at">@lang('translates.filters.filter_by')</label>
        </div>
        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.created_at')}}</label>
            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
        </div>
        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
            <label class="d-block" for="vatDateFilter">{{trans('translates.fields.vat_date')}}</label>
            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="vat_date" value="{{$filters['vat_date']}}">
            <input type="checkbox" name="check-vat_date" id="check-vat_date" @if(request()->has('check-created_at')) checked @endif> <label for="check-vat_date">@lang('translates.filters.filter_by')</label>
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
            <h1>Logistika Ödənmiş Ümumi məbləğ</h1>
            <h2>{{ $logPurchase }}</h2>
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

    <div class="card mt-4" id="company-payments-card">
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

