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
        <div class="tab-content row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text    name="key"      :value="optional($data)->getAttribute('key')"      label="Referral key"   width="6" class="pr-3" />
                    <div class="form-group col-12 col-md-6">
                        <label for="data-user_id">Referral user</label>
                        <select class="form-control" id="data-user_id" name="user_id">
                            @foreach ($users as $user)
                                <option @if (optional($data)->getAttribute('user_id') == $user->getAttribute('id')) selected @endif value="{{$user->getAttribute('id')}}">
                                    {{$user->getAttribute('fullname')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
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
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
