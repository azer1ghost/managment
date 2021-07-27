@extends('layouts.main')

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
                        Back
                    </a>
                    Edit Request
                </div>
                <div class="card-body">
                    @livewire('inquiry-form', ['action' => $action, 'method' => $method])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

