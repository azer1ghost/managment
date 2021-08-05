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

                    @if($data->backups()->exists())
                    <select class="form-control col-3 float-right" name="" id="">
                        <option value="null" selected disabled>Old versions</option>
                        @foreach($data->backups()->latest()->get() as $backup)
                        <option value="{{$backup->id}}">Backup {{$backup->created_at->diffForHumans(null, false, true)}}</option>
                        @endforeach
                    </select>
                    @endif

                </div>
                <div class="card-body">
                    @livewire('inquiry-form', ['action' => $action, 'method' => $method, 'data' => $data])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

