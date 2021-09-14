@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
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
        </div>
    </div>
</div>
@endsection


