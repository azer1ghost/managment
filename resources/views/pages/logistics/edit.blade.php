@extends('layouts.main')

@section('title', __('translates.navbar.logistics'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('logistics.index')">
            @lang('translates.navbar.logistics')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getRelationValue('service')->getAttribute('name')}} - {{optional($data)->getRelationValue('client')->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <livewire:logistics-form :action="$action" :method="$method" :data="$data" />
@endsection