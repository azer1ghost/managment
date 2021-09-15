@extends('layouts.main')

@section('title', __('translates.navbar.dashboard'))

@section('content')
    <div class="card">
        <div class="card-header">@lang('translates.navbar.dashboard')</div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="card text-center">
                        <div class="card-header">
                            <b>@lang('translates.navbar.inquiry')</b>
                        </div>
                        <div class="card-body">
                            <i class="far fa-comments-alt fa-3x text-primary"></i>
                            <p class="font-weight-bold mb-0 mt-1" style="font-size: 18px">
                                @lang('translates.date.today'): {{$inquiriesToday}}<br>
                                @lang('translates.date.month'): {{$inquiriesMonth}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
