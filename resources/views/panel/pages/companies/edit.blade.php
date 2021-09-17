@extends('layouts.main')

@section('title', __('translates.navbar.company'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('companies.index')">
            @lang('translates.navbar.company')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class="tab-content row mt-4" >
            @if (is_null($data))
                <x-input::text  name="logo"  :value="optional($data)->getAttribute('logo')"  width="6" class="pr-3" />
            @else
                <div class="col-12 mb-4">
                    <img src="{{asset("assets/images/".optional($data)->getAttribute('logo'))}}" alt="company" width="300px" height="100px" }}>
                </div>
            @endif
            <x-input::text  name="name"      :value="optional($data)->getAttribute('name')"      :label="__('translates.fields.name')"    width="6" class="pr-3" />
            <x-input::text  name="address"   :value="optional($data)->getAttribute('address')"   :label="__('translates.fields.address')" width="6" class="pr-3" />
            <x-input::text  name="website"   :value="optional($data)->getAttribute('website')"   :label="__('translates.fields.website')" width="6" class="pr-3" />
            <x-input::text  name="mobile"    :value="optional($data)->getAttribute('mobile')"    :label="__('translates.fields.mobile')"  width="6" class="pr-3" />
            <x-input::text  name="mail"      :value="optional($data)->getAttribute('mail')"      :label="__('translates.fields.mail')"   width="6" class="pr-3" />
            <x-input::text  name="phone"     :value="optional($data)->getAttribute('phone')"     :label="__('translates.fields.phone')"   width="6" class="pr-3" />
            <x-input::text  name="call_center"  :value="optional($data)->getAttribute('call_center')"     :label="__('translates.fields.call_center')"   width="6" class="pr-3" />
            <x-input::text  name="keywords"     :value="optional($data)->getAttribute('keywords')"        :label="__('translates.fields.keywords')"      width="6" class="pr-3" />
            <x-input::textarea name="about"  :value="optional($data)->getAttribute('about')"      :label="__('translates.fields.about')"   width="12" class="pr-3" rows="6"/>
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
