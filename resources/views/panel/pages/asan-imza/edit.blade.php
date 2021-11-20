@extends('layouts.main')

@section('title', __('translates.navbar.company'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('asan-imza.index')">
            @lang('translates.navbar.asan_imza')
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

            <div class="form-group col-6 py-2">
                <label for="company_id">@lang('translates.fields.company')</label><br/>
                <select class="form-control" name="company_id" id="company_id" data-width="fit">
                    @foreach($companies as $company)
                        <option @if($data->getAttribute('company_id') == $company->id) selected @endif value="{{$company->id}}">{{$company->name}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-6 py-2">
                <label for="user_id">@lang('translates.columns.user')</label><br/>
                <select class="form-control" name="user_id" id="user_id">
                    @foreach($users as $user)
                        <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                    @endforeach
                </select>
            </div>

            @if($action)
                <x-input::submit :value="__('translates.buttons.save')"/>
             @endif
    </form>
@endsection

@if(is_null($action))
@section('scripts')
    <script>
        $('select').attr('disabled', true)
    </script>
@endsection
@endif