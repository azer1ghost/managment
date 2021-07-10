@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    <a class="btn btn-lg btn-outline-primary mr-2" href="{{route('inquiry.index')}}">Call center</a>
{{--                    <a class="btn btn-lg btn-outline-success" href="{{route('call-center.index')}}">Mobex Call center</a>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
