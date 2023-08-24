@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')

@endsection

@section('content')
    <div class="container">
        <table class="table text-center" id="invoices">
            <thead>
            <tr>
                <th>Hesab Faktura No</th>
                <th>@lang('translates.fields.clientName')</th>
                <th>@lang('translates.columns.created_at')</th>
                <th>@lang('translates.columns.actions')</th>
            </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{$invoice->getAttribute('invoiceNo')}}</td>
                        <td>{{$invoice->getRelationValue('financeClients')->getAttribute('name')}}</td>
                        <td>{{$invoice->getAttribute('created_at')}}</td>
                        <td>
                            <a class="btn btn-success p-2" href="{{ route('financeInvoice', $invoice->getAttribute('id')) }}">Bax</a>
                            <a class="btn btn-danger p-2"  data-toggle="modal" data-target="#confirmDeleteModal-{{$invoice->getAttribute('id')}}">Sil</a>
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
        $('#invoices').DataTable();
    </script>
@endsection