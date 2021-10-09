@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.task')
        </x-bread-crumb-link>
    </x-bread-crumb>
    @livewire('task-table')
@endsection