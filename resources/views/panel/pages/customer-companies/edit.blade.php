@extends('layouts.main')

@section('title', __('translates.navbar.company'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('customer-companies.index')">
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
        <div class="tab-content row mt-4" >
            <x-input::text  name="name"      :value="optional($data)->getAttribute('name')"      :label="__('translates.fields.name')"    width="6" class="pr-3" />
            <x-input::text  name="voen"      :value="optional($data)->getAttribute('voen')"      label="VOEN/GOOEN"    width="6" class="pr-3" />
            <x-input::text  name="address"   :value="optional($data)->getAttribute('address')"   :label="__('translates.fields.address')" width="6" class="pr-3" />
            <x-input::text  name="email"      :value="optional($data)->getAttribute('email')"      :label="__('translates.fields.mail')"   width="6" class="pr-3" />
            <x-input::text  name="number"     :value="optional($data)->getAttribute('number')"     :label="__('translates.fields.phone')"   width="6" class="pr-3" />
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
