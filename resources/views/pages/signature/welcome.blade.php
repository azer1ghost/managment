@extends('layouts.signature')

@section('content')
    @php(header("Refresh: 5; URL=".route('signature.register')))
    <div class="container">
        <div class="row">
            <div class="col-md-12 d-flex align-items-center justify-content-center vh-100">
                <div class="text-center">
                    <x-logo class="animate__animated animate__flip " width="100px"></x-logo>
                    <h1 style="color: #050E3A" class="animate__animated animate__fadeInDown display-1 font-weight-bolder">Welcome</h1>
                    <h2 style="color: #9CCB48" class="animate__animated animate__fadeInUp animate__delay-1s" >
                        Mobil group business manage center
                        <i class="text-primary fal fa-spinner fa-spin "></i>
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endsection