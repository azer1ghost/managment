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

    <table id="works-table" class="display">
        <thead>
        <tr>
            <th>Müşteri Adı</th>
            <th>Departman</th>
            <th>Satışlar</th>
            <th>Service ID 2 - Parametre 17</th>
            <th>Service ID 1,16,17,18 - Parametre 17</th>
            <th>Service ID 5 - Parametre 20</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($formattedWorks as $group)
            <tr>
                <td>{{ $group['client']->fullname }}</td>
                <td>{{ $group['department']->short }}</td>
                <td>
                    @foreach($group['client']->sales as $sale)
                        {{ $sale->name }} <br>
                    @endforeach
                </td>
                <td>
                    @foreach ($group['works'] as $work)
                        @if ($work->service_id == 2)
                            @foreach($work->parameters as $parameter)
                                @if ($parameter->parameter_id == 17)
                                    {{ $parameter->value }} <br>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($group['works'] as $work)
                        @if (in_array($work->service_id, [1, 16, 17, 18]))
                            @foreach($work->parameters as $parameter)
                                @if ($parameter->parameter_id == 17)
                                    {{ $parameter->value }} <br>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </td>
                <td>
                    @foreach ($group['works'] as $work)
                        @if ($work->service_id == 5)
                            @foreach($work->parameters as $parameter)
                                @if ($parameter->parameter_id == 20)
                                    {{ $parameter->value }} <br>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#works-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "order": []
            });
        });
    </script>

@endsection
