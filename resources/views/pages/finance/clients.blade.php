@extends('layouts.main')

@section('title', trans('translates.navbar.dashboard'))

@section('style')

@endsection

@section('content')
    <div class="container">
        <table class="table text-center" id="clients">
            <thead>
            <tr>
                <th>@lang('translates.fields.clientName')</th>
                <th>VOEN</th>
                <th>@lang('translates.columns.actions')</th>
            </tr>
            </thead>
            <tbody>
            @foreach($clients as $client)
                <tr>
                    <td>{{$client->getAttribute('name')}}</td>
                    <td>{{$client->getAttribute('voen')}}</td>
                    <td>
                        <a class="btn btn-success p-2" href="{{ route('editFinanceClient', $client->getAttribute('id')) }}"><i class="fas fa-tools"></i></a>
                        <a class="btn btn-danger p-2" href="{{ route('deleteFinanceClient', $client->getAttribute('id')) }}"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>

@endsection
@section('scripts')
    <script>
        $('#clients').DataTable();
    </script>
@endsection