@extends('layouts.main')

@section('title', __('translates.navbar.task'))

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{route('tasks.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                <i class="fa fa-arrow-left"></i>
                @lang('translates.buttons.back')
            </a>
            @lang('translates.navbar.task')
        </div>
        <div class="card-body">
            <livewire:task-form :action="$action"  :method="$method" :task="$data" />
        </div>
    </div>
@endsection