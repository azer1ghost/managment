@extends('layouts.main')

@section('title', 'Zibil Qutusu')

@section('content')
    <div class="container">
        <div class="mb-3">
            <a href="{{ route('invoices') }}" class="btn btn-primary">
                <i class="fa fa-arrow-left"></i> Geri
            </a>
        </div>
        <h3 class="mb-3">Silinmiş Qaimələr</h3>
        <table class="table text-center" id="trashInvoices">
            <thead>
            <tr>
                <th>Hesab Faktura No</th>
                <th>@lang('translates.fields.company')</th>
                <th>@lang('translates.fields.clientName')</th>
                <th>Məbləğ</th>
                <th>Yaradan</th>
                <th>Silinmə Tarixi</th>
                <th>Əməliyyatlar</th>
            </tr>
            </thead>
            <tbody>
                @forelse($deletedInvoices as $invoice)
                    <tr>
                        <td>{{$invoice->getAttribute('invoiceNo')}}</td>
                        <td>{{$invoice->getAttribute('company')}}</td>
                        <td>{{$invoice->getRelationValue('financeClients')->getAttribute('name')}}</td>
                        <td>{{ number_format($invoice->total_amount, 2, '.', ' ') }} AZN</td>
                        <td>{{ $invoice->getRelationValue('creator')->getAttribute('name') ?? '-' }} {{ $invoice->getRelationValue('creator')->getAttribute('surname') ?? '' }}</td>
                        <td>{{$invoice->getAttribute('deleted_at')}}</td>
                        <td>
                            @if(auth()->id() === $invoice->created_by)
                                <a class="btn btn-success p-2" href="{{ route('invoices.restore', $invoice->getAttribute('id')) }}" onclick="return confirm('Bu qaiməni bərpa etmək istədiyinizə əminsiniz?')">Bərpa Et</a>
                                <a class="btn btn-danger p-2" href="{{ route('invoices.force-delete', $invoice->getAttribute('id')) }}" onclick="return confirm('Bu qaiməni tamamilə silmək istədiyinizə əminsiniz? Bu əməliyyat geri alına bilməz!')">Tamamilə Sil</a>
                            @else
                                <span class="text-muted">Yalnız yaradan silə bilər</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Zibil qutusu boşdur.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
@section('scripts')
    <script>
        $('#trashInvoices').DataTable({
            "order": [[5, "desc"]]
        });
    </script>
@endsection

