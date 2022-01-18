@extends('layouts.main')

@section('title', __('translates.navbar.sales_client'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('sales-clients.index')">
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
        <input type="hidden" name="close" value="{{request()->has('close')}}">
        <div class="tab-content row mt-4">
            <x-input::text  name="name"    :label="trans('translates.fields.fullname')"   :value="$data->getAttribute('name')"    width="6"/>
            <x-input::text  name="phone"    :label="trans('translates.fields.phone')"   :value="$data->getAttribute('phone')"    width="6"/>
            <x-input::text  name="voen"   label="VOEN"    :value="$data->getAttribute('voen')"    width="6"/>
            <x-input::textarea  name="detail"   :label="trans('translates.columns.detail')"    :value="$data->getAttribute('detail')"    width="6"/>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
    </form>
@endsection

@section('scripts')

    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif

@endsection
