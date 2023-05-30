@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')

@endsection

@section('content')
    <div class="container">
        <table class="table">
            <thead>
            <tr>
                <th>Hesab Faktura No</th>
                <th>Müştəri</th>
                <th>@lang('')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr>
                    <td>{{$invoice->getAttribute('invoiceNo')}}</td>
                    <td>{{$invoice->getRelationValue('financeClients')->getAttribute('name')}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ route('financeInvoice', $invoice->getAttribute('id')) }}">Bax</a>
                        <a class="btn btn-danger" href="{{ route('deleteInvoice', $invoice->getAttribute('id')) }}">Sil</a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

@endsection