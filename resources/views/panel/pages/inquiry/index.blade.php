@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))
@section('content')

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">@lang('translates.navbar.inquiry')</div>
                <div class="card-body">
                    @livewire('inquiry-table', ['trashBox' => $trashBox])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
