@extends('layouts.main')

@section('title', trans('translates.navbar.salary'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('salaries.index')">
            @lang('translates.navbar.salary')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getRelationValue('user')->getFullNameAttribute()}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <div class=" row mt-4">
            <div class="form-group col-6">
                <label for="user_id">@lang('translates.columns.user')</label><br/>
                <select class="select2 form-control" name="user_id" id="user_id">
                    <option value="">@lang('translates.general.user_select')</option>
                    @foreach($users as $user)
                        <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6">
                <label for="company_id">@lang('translates.columns.company')</label><br/>
                <select class="select2 form-control" name="company_id" id="company_id">
                    <option value="">@lang('translates.clients.selectCompany')</option>
                    @foreach($companies as $company)
                        <option @if($data->getAttribute('company_id') == $company->id) selected @endif value="{{$company->id}}">{{$company->getAttribute('name')}}</option>
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
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
