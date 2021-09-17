@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('inquiry.index')">
            @lang('translates.navbar.inquiry')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('inquiry.show', $inquiry)">
            {{ $inquiry->getAttribute('code')}}
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Logs
        </x-bread-crumb-link>
    </x-bread-crumb>
    <pre>{!! print_r($inquiry->logs->toArray(), true) !!}</pre>
@endsection

