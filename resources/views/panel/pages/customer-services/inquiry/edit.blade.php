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
                    <h4 class="text-muted mt-2">Edit Request</h4>
                </div>
                <div class="card-body">
                    @livewire('inquiry-form', ['action' => $action, 'method' => $method])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

