@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar/>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <a href="{{route('companies.index')}}" class="btn btn-sm btn-outline-primary mr-4">
                        <i class="fa fa-arrow-left"></i>
                        @lang('translates.buttons.back')
                    </a>
                    Companies
                </div>
                <div class="card-body">
                    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
                        @method($method) @csrf
                        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
                        <img src="{{asset("assets/images/".optional($data)->getAttribute('logo'))}}" width="300px" height="100px">
                        <div class="tab-content row mt-4" >
{{--                        <x-input::image  name="logo"  :value="optional($data)->getAttribute('logo')"  label="Company logo"  width="4"  class="pr-3" />--}}
                            <x-input::text  name="name"      :value="optional($data)->getAttribute('name')"      label="Company name"    width="6" class="pr-3" />
                            <x-input::text  name="address"   :value="optional($data)->getAttribute('address')"   label="Company address" width="6" class="pr-3" />
                            <x-input::text  name="website"   :value="optional($data)->getAttribute('website')"   label="Company website" width="6" class="pr-3" />
                            <x-input::text  name="mobile"    :value="optional($data)->getAttribute('mobile')"    label="Company mobile"  width="6" class="pr-3" />
                            <x-input::text  name="mail"      :value="optional($data)->getAttribute('mail')"      label="Company email"   width="6" class="pr-3" />
                            <x-input::text  name="phone"     :value="optional($data)->getAttribute('phone')"     label="Company phone"   width="6" class="pr-3" />
                            <x-input::text  name="call_center"  :value="optional($data)->getAttribute('call_center')"     label="Company call center"   width="6" class="pr-3" />
                            <x-input::text  name="keywords"     :value="optional($data)->getAttribute('keywords')"        label="Company keywords"      width="6" class="pr-3" />
                            <x-input::textarea name="about"  :value="optional($data)->getAttribute('about')"  label="Company about"   width="12" class="pr-3" rows="6"/>
                            <div class="col-12 mb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="is_inquirable" @if(optional($data)->getAttribute('is_inquirable') === true) checked @endif class="form-check-input" id="id-is_inquirable">
                                    <label class="form-check-label" for="id-is_inquirable">Is Inquirable</label>
                                </div>
                            </div>
                            <div class="col-12 col-md-12">
                                <p class="text-muted mb-2">SOCIALS</p>
                                @livewire('show-socials',['company' => $data,'action' => $action])
                            </div>
                        </div>
                        @if($action)
                            <x-input::submit  :value="__('translates.buttons.save')" />
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
        $('input[type="file"]').attr('disabled', true)
        $('textarea').attr('readonly', true)
    </script>
@endsection
@endif
