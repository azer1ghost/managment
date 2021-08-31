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
                    <div class="row">
                        <div class="col-12 col-md-3">
                            <div class="card">
                                <div class="card-header"><h4 class="mb-0">Inquiries</h4></div>
                                <div class="card-body text-center">
                                    <i class="fa fa-phone fa-3x text-primary"></i>
                                    <p class="font-weight-bold mb-0 mt-1" style="font-size: 18px">Today: {{$inquiries}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
