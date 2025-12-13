@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('content')
    <div class="container">
        <div class="mb-3">
            <a href="{{ route('invoices.trash') }}" class="btn btn-warning">
                <i class="fa fa-trash"></i> Zibil Qutusu
            </a>
        </div>
        <table class="table text-center" id="invoices">
            <thead>
            <tr>
                <th>Hesab Faktura No</th>
                <th>@lang('translates.fields.company')</th>
                <th>@lang('translates.fields.clientName')</th>
                <th>Məbləğ</th>
                <th>Yaradan</th>
                <th>@lang('translates.columns.created_at')</th>
                <th>@lang('translates.columns.status')</th>
                <th>Əməliyyatlar</th>
            </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{$invoice->getAttribute('invoiceNo')}}</td>
                        <td>{{$invoice->getAttribute('company')}}</td>
                        <td>{{$invoice->getRelationValue('financeClients')->getAttribute('name')}}</td>
                        <td>{{ number_format($invoice->total_amount, 2, '.', ' ') }} AZN</td>
                        <td>{{ $invoice->getRelationValue('creator')->getAttribute('name') ?? '-' }} {{ $invoice->getRelationValue('creator')->getAttribute('surname') ?? '' }}</td>
                        <td>{{$invoice->getAttribute('created_at')}}</td>
                        <td>{{$invoice->getAttribute('is_signed') == 1 ? 'İmzalanıb' : 'İmzalanmayıb'}} </td>
                        <td>
                            <a class="btn btn-success p-2" href="{{ route('financeInvoice', $invoice->getAttribute('id')) }}">Bax</a>
                            <a class="btn btn-info p-2" href="{{ route('invoices.duplicate', $invoice->getAttribute('id')) }}" title="Kopyala">Kopyala</a>
                            @if(auth()->id() === $invoice->created_by)
                                <a class="btn btn-danger p-2" data-toggle="modal" data-target="#confirmDeleteModal-{{$invoice->getAttribute('id')}}">Sil</a>
                            @endif
                        </td>
                    </tr>
                    <div class="modal fade" id="confirmDeleteModal-{{$invoice->getAttribute('id')}}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel">Silme Onayı</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Kapat">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Silmək istədiyinizə əminsiniz mi?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Xeyr</button>
                                    <a href="{{ route('deleteInvoice', $invoice->getAttribute('id')) }}"><button type="button" class="btn btn-danger" id="confirmDelete">Sil</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
@section('scripts')
    <script>
        $('#invoices').DataTable({
            "order": [[5, "desc"]] // Order by created_at column (0-based index: 5)
        });

    </script>
@endsection