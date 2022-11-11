@extends('layouts.main')

@section('title', __('translates.navbar.intern_number'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('internal-numbers.index')">
            @lang('translates.navbar.intern_number')
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
        @bind($data)
        <div class="row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <div class="form-group col-6 user">
                        <label for="user_id">@lang('translates.columns.user')</label><br/>
                        <select class="select2 form-control" name="user_id" id="user_id">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="name" label="Ad" placeholder="Ad daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="phone" label="Daxili Nömrə" placeholder="Daxili nömrə daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="detail" label="Detal daxil edin" placeholder="Detal daxil edin"/>
                    </x-form-group>
                </div>
            </div>
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
