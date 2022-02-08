@extends('layouts.main')

@section('title', trans('translates.navbar.customer_engagement'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('customer-engagement.index')">
            @lang('translates.navbar.customer_engagement')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{$data->getRelationValue('user')->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <div class="tab-content row mt-4">

            <div class="form-group col-6">
                <label for="data-client-type">{{trans('translates.fields.clientName')}}</label><br/>
                <select name="client_id" id="data-client-type" style="width: 100% !important;" class="custom-select2" data-url="{{route('clients.search')}}">
                    @if(is_numeric($data->getAttribute('client_id')))
                        <option value="{{$data->getAttribute('client_id')}}">{{$data->getRelationValue('client')->getAttribute('fullname_with_voen')}}</option>
                    @endif
                </select>
            </div>

            <div class="form-group col-6 user">
                <label for="user_id">@lang('translates.columns.user')</label><br/>
                <select class="select2 form-control" name="user_id" id="user_id">
                    <option value="">@lang('translates.general.user_select')</option>
                    @foreach($users as $user)
                        <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6 partner">
                <label for="partner_id">@lang('translates.columns.partner')</label><br/>
                <select class="select2 form-control" name="partner_id" id="partner_id">
                    <option value="">@lang('translates.general.partner_select')</option>
                    @foreach($partners as $partner)
                        <option @if($data->getAttribute('partner_id') == $partner->id) selected @endif value="{{$partner->id}}">{{$partner->getAttribute('name')}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
@endsection

@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif

    <script>
        const partnerId = $('#partner_id');
        const userId = $('#user_id');

        if (userId.val() === '') $('.partner').show();
        else $('.partner').hide();

        if (partnerId.val() === '') $('.user').show();
        else $('.user').hide();

        userId.change(function () {
            if ($(this).val() === '') $('.partner').show();
            else $('.partner').hide();
        });

        partnerId.change(function () {
            if ($(this).val() === '') $('.user').show();
            else $('.user').hide();
        });
    </script>
@endsection
