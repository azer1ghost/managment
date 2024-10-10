@extends('layouts.main')

@section('title', __('translates.navbar.information'))

@section('style')
    <style>
        .datatable-container {
            width: 100%;
            overflow-x: auto;
        }

        table.dataTable {
            table-layout: fixed;
            width: 100% !important;
        }

        table.dataTable th,
        table.dataTable td {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.information')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <div class="datatable-container">
        <table id="works-table" class="display nowrap" style="width:100%">
            <thead>
            <tr>
                <th>Müştəri Adı</th>
                <th>Filial</th>
                <th>Satış Əməkdaşı</th>
                <th>QİB Sayı</th>
                <th>QİB dövriyyə</th>
                <th>GB sayı</th>
                <th>GB dövriyyə</th>
                <th>Təmsilçilik sayı</th>
                <th>Təmsilçilik dövriyyə</th>
                <th>Logistika profit</th>
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
                                @foreach(optional($work->parameters) as $parameter)
                                    @if ($parameter->pivot->parameter_id == 17)
                                        {{ $parameter->pivot->value }} <br>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($group['works'] as $work)
                            @if (in_array($work->service_id, [1, 16, 17, 18]))
                                @foreach(optional($work->parameters) as $parameter)
                                    @if ($parameter->pivot->parameter_id == 17)
                                        {{ $parameter->pivot->value }} <br>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach ($group['works'] as $work)
                            @if ($work->service_id == 5)
                                @foreach(optional($work->parameters) as $parameter)
                                    @if ($parameter->pivot->parameter_id == 20)
                                        {{ $parameter->pivot->value }} <br>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#works-table').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "scrollX": true, // Yatay kaydırma eklendi
                "fixedHeader": true,
                "order": [],
                "columnDefs": [
                    { "width": "150px", "targets": 0 },
                    { "width": "150px", "targets": 1 },
                    { "width": "200px", "targets": 2 },
                    { "width": "250px", "targets": 3 },
                    { "width": "250px", "targets": 4 },
                    { "width": "250px", "targets": 5 },
                    { "width": "250px", "targets": 6 },
                    { "width": "250px", "targets": 7 },
                    { "width": "250px", "targets": 8 },
                    { "width": "250px", "targets": 9 }
                ]
            });
        });
    </script>
@endsection
