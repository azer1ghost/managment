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
                    <a href="{{route('companies.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
{{--                    {{ucfirst(last(request()->segments())) . optional($data)->getShortName()}}--}}
                </div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($data)->id}}">
                        <div class="tab-content form-row mt-4" >
                            <x-input::image name="logo" :value="optional($data)->logo" label="Company logo" width="4" class="pr-3"/>
                            <x-input::text name="name" :value="optional($data)->name" label="Company name" width="4" class="pr-3" />
                            <x-input::text name="address" :value="optional($data)->address" label="Company address" width="4" class="pr-3" />
                            <x-input::text name="website" :value="optional($data)->website" label="Company website" width="4" class="pr-3" />
                            <x-input::text name="mail" :value="optional($data)->mail" label="Company email" width="4" class="pr-3" />
                            <x-input::text name="phone" :value="optional($data)->phone" label="Company phone" width="4" class="pr-3" />
                            <x-input::text name="mobile" :value="optional($data)->mobile" label="Company mobile" width="4" class="pr-3" />
                            <x-input::textarea rows="6" name="about" :value="optional($data)->about" label="Company about" width="8" class="pr-3" />
                        </div>
                        @if($action)
                            <x-input::submit/>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
