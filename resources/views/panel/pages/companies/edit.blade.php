@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Edit company</div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" >
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($attribute)->id}}">
                        <div class="tab-content form-row mt-4" >
                            <x-input::image name="logo"  :value="optional($attribute)?->logo ?? old('logo')" label="Company logo" width="4" class="pr-3"/>
                            <x-input::text name="name" :value="optional($attribute)?->name ?? old('name')" label="Company name" width="4" class="pr-3" />
                            <x-input::text name="address" :value="optional($attribute)?->address ?? old('address')" label="Company address" width="4" class="pr-3" />
                            <x-input::text name="website" :value="optional($attribute)?->website ?? old('website')" label="Company website" width="4" class="pr-3" />
                            <x-input::text name="mail" :value="optional($attribute)?->mail ?? old('mail')" label="Company email" width="4" class="pr-3" />
                            <x-input::text name="phone" :value="optional($attribute)?->phone ?? old('phone')" label="Company phone" width="4" class="pr-3" />
                            <x-input::text name="mobile" :value="optional($attribute)?->mobile ?? old('mobile')" label="Company mobile" width="4" class="pr-3" />
                        </div>
                        <x-input::submit/>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
