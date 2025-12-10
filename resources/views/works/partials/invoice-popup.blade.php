<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
        <tr class="text-center">
            <th>İş ID</th>
            <th>İş adı</th>
            <th>Qaimə nömrəsi</th>
            <th>Əsas məbləğ (AMOUNT)</th>
            <th>Əsas məbləğdən ödənilən (PAID)</th>
            <th>ƏDV məbləği (VAT)</th>
            <th>ƏDV-dən ödənilən (VATPAYMENT)</th>
            <th>Qeyri-rəsmi məbləğ (ILLEGALAMOUNT)</th>
            <th>Qeyri-rəsmi ödənilən (ILLEGALPAID)</th>
            <th>Əsas ödəniş tarixi</th>
            <th>ƏDV ödəniş tarixi</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach($works as $work)
            @php
                $amount = $work->getParameterValue(\App\Models\Work::AMOUNT) ?? 0;
                $paid = $work->getParameterValue(\App\Models\Work::PAID) ?? 0;
                $vat = $work->getParameterValue(\App\Models\Work::VAT) ?? 0;
                $vatPayment = $work->getParameterValue(\App\Models\Work::VATPAYMENT) ?? 0;
                $illegalAmount = $work->getParameterValue(\App\Models\Work::ILLEGALAMOUNT) ?? 0;
                $illegalPaid = $work->getParameterValue(\App\Models\Work::ILLEGALPAID) ?? 0;
                $isFullyPaid = $work->isFullyPaid();
            @endphp
            <tr>
                <td>{{ $work->id }}</td>
                <td>{{ $work->getRelationValue('service')->getAttribute('name') ?? '-' }}</td>
                <td>{{ $work->code ?? '-' }}</td>
                <td data-amount="{{ $amount }}">{{ number_format($amount, 2) }}</td>
                <td data-paid="{{ $paid }}">{{ number_format($paid, 2) }}</td>
                <td data-vat="{{ $vat }}">{{ number_format($vat, 2) }}</td>
                <td data-vat-payment="{{ $vatPayment }}">{{ number_format($vatPayment, 2) }}</td>
                <td data-illegal-amount="{{ $illegalAmount }}">{{ number_format($illegalAmount, 2) }}</td>
                <td data-illegal-paid="{{ $illegalPaid }}">{{ number_format($illegalPaid, 2) }}</td>
                <td>
                    <input type="date" 
                           name="paid_at[{{ $work->id }}]" 
                           class="form-control form-control-sm" 
                           value="{{ $work->paid_at ? $work->paid_at->format('Y-m-d') : '' }}"
                           @if($isFullyPaid) disabled @endif>
                </td>
                <td>
                    <input type="date" 
                           name="vat_date[{{ $work->id }}]" 
                           class="form-control form-control-sm" 
                           value="{{ $work->vat_date ? $work->vat_date->format('Y-m-d') : '' }}"
                           @if($isFullyPaid) disabled @endif>
                </td>
                <td>
                    @if($isFullyPaid)
                        <span class="badge badge-success">Tam ödənilib</span>
                    @else
                        <button class="btn btn-success btn-sm completePayment" data-id="{{ $work->id }}">
                            Tam ödəniş et
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

