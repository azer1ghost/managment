@extends('layouts.main')

@section('title', __('translates.navbar.commands'))
@section('style')
    <style>
        table {
            table-layout:fixed;
            width:100%;
        }
        td, th {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.commands')
        </x-bread-crumb-link>
    </x-bread-crumb>


    <div class="col-2">
        <div class="card">
            <div class="card-title">
                <form action="{{ route('rooms.create') }}">
                    <input type="hidden" name="department_id" value="2">
                    <button type="submit" class="btn-info" href="{{ route('rooms.create') }}">Müştəri Əlaqələri şöbəsi</button>
                </form>

            </div>
        </div>

    </div>
@endsection
@section('scripts')

@endsection
