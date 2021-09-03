@extends('layouts.main')

@section('title', __('translates.navbar.cabinet'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">@lang('translates.navbar.cabinet')</div>
                <div class="card-body row">

                    <x-modules.tasks/>
                    <x-modules.inquiries/>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
