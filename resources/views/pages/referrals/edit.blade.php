@extends('layouts.main')

@section('title', __('translates.navbar.referral'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('referrals.index')">
            @lang('translates.navbar.referral')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('key')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class=" row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text readonly  :value="optional($data)->getAttribute('key')"  label="Referral key"   width="6" class="pr-3" />
                    <x-input::text readonly  :value="optional($data)->getRelationValue('user')->getAttribute('fullname')"  label="Referral user"  width="6" class="pr-3" />
                    <x-input::number step="0.01" name="referral_bonus_percentage"   :value="optional($data)->getAttribute('referral_bonus_percentage')"  label="Referral bonus percentage"   width="6" class="pr-3" />
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
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
