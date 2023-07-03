@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <style>
        .table td, .table th{
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
        .table{
            overflow-x: scroll;
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


    <h1>Ayın Əvvəlindən qeyri-rəsmi məbləğ</h1>
    <h2>{{ $totalIllegalAmount }}</h2>
    <h1>Ayın Əvvəlindən rəsmi məbləğ</h1>
    <h2>{{ $totalAmount }}</h2>
    <h1>Ayın Əvvəlindən ədv məbləğ</h1>
    <h2>{{ $totaVat }}</h2>

@endsection
@section('scripts')
@endsection

