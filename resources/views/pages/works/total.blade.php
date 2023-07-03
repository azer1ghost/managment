@extends('layouts.main')

@section('title', __('translates.navbar.work'))

@section('style')
    <style>
        .table td,
        .table th {
            vertical-align: middle !important;
        }

        .table tr {
            cursor: pointer;
        }

        .hiddenRow {
            padding: 0 4px !important;
            background-color: #eeeeee;
            font-size: 13px;
        }

        .table {
            overflow-x: scroll;
        }

        /* Stil değişiklikleri */
        .work-stats {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .work-stats h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .work-stats h2 {
            font-size: 18px;
            font-weight: normal;
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.work')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <div class="work-stats">
        <div>
            <h1>Ayın Başından Qeyri-Rəsmi Məbləğ</h1>
            <h2>{{ $totalIllegalAmount }}</h2>
        </div>
        <div>
            <h1>Ayın Başından Rəsmi Məbləğ</h1>
            <h2>{{ $totalAmount }}</h2>
        </div>
        <div>
            <h1>Ayın Başından ƏDV Məbləğ</h1>
            <h2>{{ $totalVat }}</h2>
        </div>
    </div>

    <!-- Diğer içerikler -->
@endsection

@section('scripts')
@endsection