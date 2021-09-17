@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.inquiry')
        </x-bread-crumb-link>
    </x-bread-crumb>
    @livewire('inquiry-table', ['trashBox' => $trashBox])
@endsection
