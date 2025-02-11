@extends('layouts.main')

@section('title', __('translates.navbar.information'))

@section('style')
    <style>
        /* Tabloyu kapsayan container */
        .datatable-container {
            width: 100%;
            overflow-x: auto;
        }

        /* Tablo genişliğini sabitleme */
        table.dataTable {
            table-layout: fixed;
            width: 100% !important;
        }

        /* Hücrelerin taşmasını önlemek ve kaydırma eklemek */
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

    <table id="works-table" class="display">
        <thead>
        <tr>
            <th>Müştəri Adı</th>
            <th>Filial</th>
            <th>Satışlar</th>
            <th>Service ID 2 - Parametre 17</th>
            <th>Service ID 1,16,17,18 - Parametre 17</th>
            <th>Service ID 5 - Parametre 20</th>
            <th>Yeni Sütun 1</th>
            <th>Yeni Sütun 2</th>
            <th>Yeni Sütun 3</th>
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
                <td>Veri 1</td> <!-- Yeni sütun 1 için örnek veri -->
                <td>Veri 2</td> <!-- Yeni sütun 2 için örnek veri -->
                <td>Veri 3</td> <!-- Yeni sütun 3 için örnek veri -->
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')

    <!-- jQuery Kütüphanesi -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#works-table').DataTable({
                "paging": true, // Sayfalama
                "searching": true, // Arama
                "ordering": true, // Sıralama
                "info": true, // Bilgi gösterimi
                "autoWidth": false, // Otomatik genişlik kapalı
                "scrollX": true, // Yatay kaydırma ekler
                "fixedHeader": true, // Başlıkların sabit kalmasını sağlar
                "order": [], // Varsayılan sıralama yok
                "columnDefs": [
                    { "width": "150px", "targets": 0 }, // Her sütun için genişlik ayarı
                    { "width": "150px", "targets": 1 },
                    { "width": "200px", "targets": 2 },
                    { "width": "250px", "targets": 3 },
                    { "width": "250px", "targets": 4 },
                    { "width": "250px", "targets": 5 },
                    { "width": "150px", "targets": 6 },
                    { "width": "150px", "targets": 7 },
                    { "width": "150px", "targets": 8 }
                ]
            });
        });
    </script>

@endsection
