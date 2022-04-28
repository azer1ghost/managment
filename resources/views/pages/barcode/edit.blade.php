@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('barcode.index')">
                @lang('translates.navbar.barcode')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{optional($data)->getAttribute('code')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>

    <div class="mt-3 pt-3" style="clear: both">
        <livewire:barcode-form :action="$action" :method="$method" :barcode="$data"/>
    </div>

@endsection
