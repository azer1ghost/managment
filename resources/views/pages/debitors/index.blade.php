@extends('layouts.main')

@section('title', 'Debitor Cədvəli')

@section('style')
    <style>
        .table td, .table th {
            vertical-align: middle !important;
        }
        .badge-aciq    { background-color: #dc3545; color: #fff; }
        .badge-bagli   { background-color: #28a745; color: #fff; }
        .badge-qismen  { background-color: #ffc107; color: #212529; }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>Debitor Cədvəli</x-bread-crumb-link>
    </x-bread-crumb>

    <div class="row mb-2">
        <form action="{{ route('debitors.index') }}" method="GET" class="col-12 row">

            {{-- Qaimə Şirkəti --}}
            <div class="col-md-3 mb-2">
                <select name="invoice_company_id" class="form-control">
                    <option value="">Qaimə Şirkəti</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}"
                            {{ $filters['invoice_company_id'] == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Müştəri --}}
            <div class="col-md-3 mb-2">
                <select name="client_id" class="form-control">
                    <option value="">Müştəri</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}"
                            {{ $filters['client_id'] == $client->id ? 'selected' : '' }}>
                            {{ $client->fullname }}
                            @if($client->voen) ({{ $client->voen }}) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Vəziyyət --}}
            <div class="col-md-2 mb-2">
                <select name="debitor_status" class="form-control">
                    <option value="">Vəziyyət</option>
                    <option value="Açıq"   {{ $filters['debitor_status'] == 'Açıq'   ? 'selected' : '' }}>Açıq</option>
                    <option value="Bağlı"  {{ $filters['debitor_status'] == 'Bağlı'  ? 'selected' : '' }}>Bağlı</option>
                    <option value="Qismən" {{ $filters['debitor_status'] == 'Qismən' ? 'selected' : '' }}>Qismən</option>
                </select>
            </div>

            {{-- Tarix aralığı --}}
            <div class="col-md-2 mb-2">
                <input type="date" name="invoiced_date_from" class="form-control"
                       placeholder="Tarixdən"
                       value="{{ $filters['invoiced_date_from'] }}">
            </div>
            <div class="col-md-2 mb-2">
                <input type="date" name="invoiced_date_to" class="form-control"
                       placeholder="Tarixə qədər"
                       value="{{ $filters['invoiced_date_to'] }}">
            </div>

            <div class="col-12 d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <select name="limit" class="custom-select" style="width:auto;">
                        @foreach([25, 50, 100, 250, 500] as $size)
                            <option value="{{ $size }}" {{ $limit == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="btn-group">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter"></i> Filtr
                    </button>
                    <a href="{{ route('debitors.index') }}" class="btn btn-outline-danger">
                        <i class="fal fa-times-circle"></i> Təmizlə
                    </a>
                    <a href="{{ route('debitors.export', array_filter($filters)) }}"
                       class="btn btn-outline-success">
                        <i class="fal fa-file-excel"></i> Export
                    </a>
                </div>
            </div>
        </form>

        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-sm">
                    <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Qaimə Şirkəti</th>
                        <th>VÖEN</th>
                        <th>Qaimə Kodu</th>
                        <th>Qaimə Tarixi</th>
                        <th>Qaimə Məbləği (Əsas)</th>
                        <th>ƏDV</th>
                        <th>Ödəniş Tarixi</th>
                        <th>Ödənilmiş Məbləğ (Əsas)</th>
                        <th>Ödəniş ƏDV</th>
                        <th>Qalıq Məbləğ</th>
                        <th>Qalıq ƏDV</th>
                        <th>Vəziyyət</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $totalAmount     = 0;
                        $totalVat        = 0;
                        $totalPaid       = 0;
                        $totalVatPayment = 0;
                        $totalQaliq      = 0;
                        $totalQaliqEdv   = 0;
                    @endphp

                    @forelse($works as $work)
                        @php
                            $amount     = (float) ($work->getParameter(\App\Models\Work::AMOUNT) ?? 0);
                            $vat        = (float) ($work->getParameter(\App\Models\Work::VAT) ?? 0);
                            $paid       = (float) ($work->getParameter(\App\Models\Work::PAID) ?? 0);
                            $vatPayment = (float) ($work->getParameter(\App\Models\Work::VATPAYMENT) ?? 0);

                            $qaliqMebleg = $amount - $paid;
                            $qaliqEdv    = $vat - $vatPayment;

                            $totalPaid   += $paid;
                            $totalVatPayment += $vatPayment;
                            $totalAmount += $amount;
                            $totalVat    += $vat;
                            $totalQaliq  += $qaliqMebleg;
                            $totalQaliqEdv += $qaliqEdv;

                            $totalRowPaid = $paid + $vatPayment;

                            if ($totalRowPaid <= 0) {
                                $veziyyet = 'Açıq';
                                $badgeClass = 'badge-aciq';
                            } elseif (abs($amount - $paid) < 0.01 && abs($vat - $vatPayment) < 0.01) {
                                $veziyyet = 'Bağlı';
                                $badgeClass = 'badge-bagli';
                            } else {
                                $veziyyet = 'Qismən';
                                $badgeClass = 'badge-qismen';
                            }
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ optional($work->invoiceCompany)->name ?? '-' }}</td>
                            <td>{{ optional($work->client)->voen ?? '-' }}</td>
                            <td>
                                <a href="{{ route('works.show', $work) }}" target="_blank">
                                    {{ $work->code ?? '-' }}
                                </a>
                            </td>
                            <td>{{ optional($work->invoiced_date)->format('d.m.Y') ?? '-' }}</td>
                            <td class="text-right">{{ number_format($amount, 2) }}</td>
                            <td class="text-right">{{ number_format($vat, 2) }}</td>
                            <td>{{ optional($work->paid_at)->format('d.m.Y') ?? '-' }}</td>
                            <td class="text-right">{{ number_format($paid, 2) }}</td>
                            <td class="text-right">{{ number_format($vatPayment, 2) }}</td>
                            <td class="text-right {{ $qaliqMebleg > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ number_format($qaliqMebleg, 2) }}
                            </td>
                            <td class="text-right {{ $qaliqEdv > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ number_format($qaliqEdv, 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $veziyyet }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13">
                                <div class="alert alert-warning text-center mb-0">
                                    @lang('translates.general.empty')
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    {{-- Cəm sətri --}}
                    @if($works->count() > 0)
                        <tr class="table-dark font-weight-bold">
                            <td colspan="5" class="text-right">Cəm:</td>
                            <td class="text-right">{{ number_format($totalAmount, 2) }}</td>
                            <td class="text-right">{{ number_format($totalVat, 2) }}</td>
                            <td></td>
                            <td class="text-right">{{ number_format($totalPaid, 2) }}</td>
                            <td class="text-right">{{ number_format($totalVatPayment, 2) }}</td>
                            <td class="text-right">{{ number_format($totalQaliq, 2) }}</td>
                            <td class="text-right">{{ number_format($totalQaliqEdv, 2) }}</td>
                            <td></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 mt-3">
            {{ $works->appends(request()->input())->links() }}
        </div>
    </div>
@endsection
