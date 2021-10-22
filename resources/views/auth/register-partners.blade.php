@extends('layouts.main')

@section('title', __('translates.register.register'))

@section('style')
    <style>
        .custom-wrapper main {
            width: 100% !important;
            margin-left: 0 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row justify-content-center" id="register-container">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-5 text-center p-0 mt-3 mb-2">
            <div class="px-0 pt-4 pb-0 mt-3 mb-3">
                <h2 id="heading">@lang('translates.register.title', ['type' => __('translates.users.titles.partner')])</h2>
                <p>@lang('translates.register.fill')</p>
                <form id="register-form" method="POST" action="{{route('register.partners')}}" enctype="multipart/form-data">
                @csrf
                <x-fieldsets :is-outsource="true"/>
                </form>
            </div>
        </div>
    </div>
@endsection