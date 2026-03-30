@extends('layouts.main')

@section('title', 'Debitor Cədvəli')

@section('style')
    <style>
        .table td, .table th {
            vertical-align: middle !important;
            white-space: nowrap;
        }
        .badge-aciq    { background-color: #dc3545; color: #fff; }
        .badge-bagli   { background-color: #28a745; color: #fff; }
        .badge-qismen  { background-color: #ffc107; color: #212529; }
        .qaime-link    { color: #0056b3; font-weight: 600; text-decoration: underline; }
        .qaime-link:hover { color: #003580; }
        .pm-naqd  { background:#6c757d; color:#fff; padding:2px 6px; border-radius:4px; font-size:.8em; }
        .pm-bank  { background:#0d6efd; color:#fff; padding:2px 6px; border-radius:4px; font-size:.8em; }
        .pm-pbank { background:#6610f2; color:#fff; padding:2px 6px; border-radius:4px; font-size:.8em; }
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

            {{-- Ödəniş Üsulu --}}
            <div class="col-md-2 mb-2">
                <select name="payment_method" class="form-control">
                    <option value="">Ödəniş Üsulu</option>
                    @foreach($paymentMethods as $key => $label)
                        <option value="{{ $key }}" {{ $filters['payment_method'] == $key ? 'selected' : '' }}>
                            {{ $label }}
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
                        <th>Müştəri</th>
                        <th>Qaimə Kodu</th>
                        <th>Qaimə Tarixi</th>
                        <th class="text-right">Qaimə Məbləği</th>
                        <th class="text-right">ƏDV</th>
                        <th>Ödəniş Tarixi</th>
                        <th class="text-right">Ödənilmiş Məbləğ</th>
                        <th class="text-right">Ödəniş ƏDV</th>
                        <th class="text-right">Qalıq Məbləğ</th>
                        <th class="text-right">Qalıq ƏDV</th>
                        <th>Ödəniş Üsulu</th>
                        <th>Vəziyyət</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($works as $row)
                        @php
                            $pmClass = match((int)$row->payment_method) {
                                1       => 'pm-naqd',
                                2       => 'pm-bank',
                                3       => 'pm-pbank',
                                default => 'pm-bank',
                            };
                            $pmLabel = $paymentMethods[$row->payment_method] ?? '-';

                            $badgeClass = match($row->veziyyet) {
                                'Bağlı'  => 'badge-bagli',
                                'Qismən' => 'badge-qismen',
                                default  => 'badge-aciq',
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration + ($works->currentPage() - 1) * $works->perPage() }}</td>
                            <td>{{ $row->invoice_company_name ?? '-' }}</td>
                            <td>{{ $row->voen ?? '-' }}</td>
                            <td>{{ $row->client_name ?? '-' }}</td>
                            <td>
                                @if($row->code)
                                    <a href="{{ route('works.index', ['search' => $row->code]) }}"
                                       target="_blank" class="qaime-link">
                                        {{ $row->code }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $row->invoiced_date ? \Carbon\Carbon::parse($row->invoiced_date)->format('d.m.Y') : '-' }}</td>
                            <td class="text-right">{{ number_format($row->amount, 2) }}</td>
                            <td class="text-right">{{ number_format($row->vat, 2) }}</td>
                            <td>{{ $row->paid_at ? \Carbon\Carbon::parse($row->paid_at)->format('d.m.Y') : '-' }}</td>
                            <td class="text-right">{{ number_format($row->paid, 2) }}</td>
                            <td class="text-right">{{ number_format($row->vat_payment, 2) }}</td>
                            <td class="text-right {{ $row->qaliq > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ number_format($row->qaliq, 2) }}
                            </td>
                            <td class="text-right {{ $row->qaliq_edv > 0 ? 'text-danger font-weight-bold' : '' }}">
                                {{ number_format($row->qaliq_edv, 2) }}
                            </td>
                            <td><span class="{{ $pmClass }}">{{ $pmLabel }}</span></td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $row->veziyyet }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="15">
                                <div class="alert alert-warning text-center mb-0">
                                    @lang('translates.general.empty')
                                </div>
                            </td>
                        </tr>
                    @endforelse

                    {{-- Cəm sətri --}}
                    @if($works->count() > 0)
                        <tr class="table-dark font-weight-bold">
                            <td colspan="6" class="text-right">Cəm:</td>
                            <td class="text-right">{{ number_format($totals['amount'], 2) }}</td>
                            <td class="text-right">{{ number_format($totals['vat'], 2) }}</td>
                            <td></td>
                            <td class="text-right">{{ number_format($totals['paid'], 2) }}</td>
                            <td class="text-right">{{ number_format($totals['vat_payment'], 2) }}</td>
                            <td class="text-right">{{ number_format($totals['qaliq'], 2) }}</td>
                            <td class="text-right">{{ number_format($totals['qaliq_edv'], 2) }}</td>
                            <td colspan="2"></td>
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
