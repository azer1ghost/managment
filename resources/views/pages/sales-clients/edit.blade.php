@extends('layouts.main')

@section('title', __('translates.navbar.sales_client'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('sales-client.index')">
            @lang('translates.navbar.sales_client')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{$data->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST">
        @method($method) @csrf
        @bind($data)
        <input type="hidden" name="close" value="{{request()->has('close')}}">
        <div class=" row mt-4">
            <x-form-group  :label="trans('translates.fields.fullname')"  class="pr-3 col-12 col-lg-6">
                <x-form-input  name="name"/>
            </x-form-group>
            <x-form-group  :label="trans('translates.fields.phone')"  class="pr-3 col-12 col-lg-6">
                <x-form-input  name="phone"/>
            </x-form-group>
            <x-form-group   class="pr-3 col-12 col-lg-6">
                <x-form-input  name="voen"  label="VOEN"/>
            </x-form-group>
            <x-form-group :label="trans('translates.columns.detail')"   class="pr-3 col-12 col-lg-6">
                <x-form-textarea  name="detail"/>
            </x-form-group>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
        @endbind
    </form>
@endsection

@section('scripts')

    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif

@endsection
