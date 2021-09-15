@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="{{route('inquiry.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                <i class="fa fa-arrow-left"></i>
                @lang('translates.buttons.back')
            </a>
        </div>
        <div class="card-body">
            <pre>{!! print_r($inquiry->logs->toArray(), true) !!}</pre>
        </div>
    </div>
@endsection


