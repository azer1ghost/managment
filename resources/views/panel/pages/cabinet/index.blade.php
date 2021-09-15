@extends('layouts.main')

@section('title', __('translates.navbar.cabinet'))

@section('content')
    <div class="card">
        <div class="card-header">@lang('translates.navbar.cabinet')</div>
        <div class="card-body row">
            <x-modules.tasks/>
            <x-modules.inquiries/>
        </div>
    </div>
@endsection
