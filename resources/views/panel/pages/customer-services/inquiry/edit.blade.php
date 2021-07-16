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
                <div id="app" class="card-body">
                    <form action="{{$action}}" id="createForm" method="POST" >
                        @csrf
                        @method($method)

                        @livewire('company-selector')

                        <a href="{{route('inquiry.index')}}" class="btn btn-outline-danger">Back</a>

                        <button class="btn btn-outline-primary float-right">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

