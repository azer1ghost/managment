@extends('layouts.main')

@section('title', __('translates.navbar.welcome'))

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center justify-content-center vh-100">
                <div class="text-center">

                    <x-logo/>

                    <h1 style="color: #050E3A; font-size: 4rem" class="animate__animated animate__fadeInDown font-weight-bolder">@lang('translates.navbar.welcome')</h1>
                    <h2 style="color: #9CCB48" class="animate__animated animate__fadeInUp animate__delay-1s" >Mobil group business manage center</h2>
                    <h1 class="mt-5"><i class="text-primary fal fa-spinner fa-spin "></i></h1>
                </div>
            </div>
        </div>
    </div>
@endsection