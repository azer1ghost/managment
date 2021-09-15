@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))
@section('content')
    <div class="card">
        <div class="card-header">@lang('translates.navbar.inquiry')</div>
        <div class="card-body">
            @livewire('inquiry-table', ['trashBox' => $trashBox])
        </div>
    </div>
@endsection
