@extends('layouts.main')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit company</div>
                <div class="card-body">


                    <form action="{{$action}}" method="POST" >
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($attribute)->id}}">

                        <div class="tab-content form-row mt-4" >

                            <x-input::text name="name" :value="optional($attribute)?->key ?? old('name')" label="Company name" width="3" class="pr-5" />
                            <x-input::text name="address" :value="optional($attribute)?->key ?? old('address')" label="Company address" width="3" class="pr-5" />
                            <x-input::text name="website" :value="optional($attribute)?->key ?? old('website')" label="Company website" width="3" class="pr-5" />
                            <x-input::text name="mail" :value="optional($attribute)?->key ?? old('mail')" label="Company email" width="3" class="pr-5" />
                            <x-input::text name="phone" :value="optional($attribute)?->key ?? old('phone')" label="Company phone" width="3" class="pr-5" />
                            <x-input::text name="mobile" :value="optional($attribute)?->key ?? old('mobile')" label="Company mobile" width="3" class="pr-5" />

                        </div>

                        <x-input::submit/>
                    </form>




{{--                    <form id="create" class="form-row" method="post" enctype="multipart/form-data" action="{{route('companies.store')}}">--}}
{{--                        @csrf--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="logo">Company logo</label>--}}
{{--                            <input type="file" class="form-control" id="logo" name="logo" placeholder="Enter company logo">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="name">Company name</label>--}}
{{--                            <input type="text" required class="form-control" id="name" name="name" placeholder="Enter company name">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="address">Company address</label>--}}
{{--                            <input type="text" required class="form-control" id="address" name="address" placeholder="Enter company address">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="website">Company website</label>--}}
{{--                            <input type="url" class="form-control" id="website" name="website" placeholder="Enter company website">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="email">Company e-mail</label>--}}
{{--                            <input type="email" required class="form-control" id="email" name="email" placeholder="Enter company e-mail">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="phone">Company phone</label>--}}
{{--                            <input type="text" required class="form-control" id="phone" name="phone" placeholder="Enter company phone">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-md-6">--}}
{{--                            <label for="mobile">Company mobile</label>--}}
{{--                            <input type="text" required class="form-control" id="mobile" name="mobile" placeholder="Enter company mobile">--}}
{{--                        </div>--}}
{{--                        <div class="col-md-12">--}}
{{--                            <button form="create" type="submit" class="btn btn-primary float-right">Create</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
