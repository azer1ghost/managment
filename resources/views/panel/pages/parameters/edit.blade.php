@extends('layouts.main')

@section('content')
<div class="container" xmlns:x-input="http://www.w3.org/1999/html">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('parameters.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                    Companies
                </div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($data)->id}}">
                        <div class="tab-content form-row mt-4" >
                            <x-input::select  name="parameter_id"  :value="optional($data)->parameter_id"  width="4" class="pr-3" :options="$parameters" />
                            <x-input::select  name="type"  :value="optional($data)->type"   width="4" class="pr-3" :options="$types" />
                            <x-input::text    name="name"  :value="optional($data)->name"   width="4" class="pr-3" />

                        </div>
                        @if($action)
                            <x-input::submit />
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if(is_null($action))
@section('scripts')
    <script>
        $('input').attr('readonly', true)
        $('select').attr('disabled', true)
        $('textarea').attr('readonly', true)
    </script>
@endsection
@endif
