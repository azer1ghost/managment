@extends('layouts.main')

@section('title', __('translates.navbar.information'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.information')
        </x-bread-crumb-link>
    </x-bread-crumb>

<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
    <tr style="background-color: #800000; color: white;">
        <th>İD</th>
        <th>musteri_adi</th>
        <th>Filial adı</th>
        <th>Kordinasiya (satış əməkdaşı)</th>
        <th>service_id=2 and parameter_id=17 value</th>
        <th>service_id=1,16 and parameter_id=17 value</th>
        <th>GB dövriyyə</th>
        <th>Təmsilçilik sayı</th>
        <th>Təmsilçilik dövriyyə</th>
        <th>Logistika (profit)</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($works as $work)
        <tr>
            <td>{{ $work->getAttribute('id') }}</td>
            <td>{{ $work->getRelationValue('client')->getAttribute('fullname') }}</td>
            <td>{{ $work->getRelationValue('department')->getAttribute('short') }}</td>
            <td>
                @foreach($work->client->sales as $sale)
                    {{ $sale->name }} <br>
                @endforeach
            </td>
            <td>
                @if ($work->service_id == 2)
                    @foreach($work->parameters as $parameter)
                        @if ($parameter->parameter_id == 17)
                            {{ $parameter->value }} <br>
                        @endif
                    @endforeach
                @endif
            </td>
            <td>
                @if (in_array($work->service_id, [1, 16, 17, 18]))
                    @foreach($work->parameters as $parameter)
                        @if ($parameter->parameter_id == 17)
                            {{ $parameter->value }} <br>
                        @endif
                    @endforeach
                @endif
            </td>
            <td>
                @if (in_array($work->service_id, [5]))
                    @foreach($work->parameters as $parameter)
                        @if ($parameter->parameter_id == 20)
                            {{ $parameter->value }} <br>
                        @endif
                    @endforeach
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
@endsection
