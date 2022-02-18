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
        <div class=" row mt-4" >
            @if (is_null($data))
                <x-input::text  name="logo"  :value="optional($data)->getAttribute('logo')"  width="6" class="pr-3" />
            @else
                <div class="col-12 mb-4">
                    <img src="{{asset("assets/images/".optional($data)->getAttribute('logo'))}}" alt="company" width="300px" height="100px" }}>
                </div>
            @endif
            @bind($data)
                <x-form-group :label="__('translates.fields.name')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="name"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.address')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="address"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.website')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="website"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.mobile')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="mobile"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.mail')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="mail"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.intercity_phone')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="intercity_phone"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.call_center')" class="pr-3 col-12 col-lg-6">
                    <x-form-input name="call_center"/>
                </x-form-group>
                <x-form-group :label="__('translates.fields.keywords')" class="pr-3 col-12 col-lg-6">
                    <x-form-textarea name="keywords"/>
                </x-form-group>
                <x-form-group  class="pr-3 col-12 mb-2  pl-5 m-0">
                    <x-form-checkbox name="is_inquirable" :label="trans('translates.general.inquirable')"  />
                </x-form-group>
            @endbind

            <div class="col-12 col-md-12">
                <p class="text-muted mb-2">@lang('translates.general.sosials')</p>
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
        $('form :input').attr('disabled', true)
    </script>
@endsection
@endif
