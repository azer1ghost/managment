@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('tasks.index')">
            @lang('translates.navbar.task')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>

    <livewire:task-form :action="$action"  :method="$method" :task="$data" />

    @if(optional($data)->inquiry_id)
        <div class="card mt-4">
            <div class="card-header">
                Inquiry
            </div>
            <div class="card-body inquiry">
                <livewire:inquiry-form :inquiry="$data->getRelationValue('inquiry')" />
            </div>
        </div>
    @endif

    <x-comments :commentable="$data"/>
@endsection