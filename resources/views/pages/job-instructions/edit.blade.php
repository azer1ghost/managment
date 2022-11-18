@extends('layouts.main')

@section('title', trans('translates.navbar.job_instruction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('job-instructions.index')">
            @lang('translates.navbar.job_instruction')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data->getRelationValue('users'))->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <div class="form-group col-6">
            <label for="department_id">@lang('translates.columns.department')</label><br/>
            <select class="select2 form-control" name="department_id" id="department_id">
                <option value="">@lang('translates.general.department_select')</option>
                @foreach($departments as $department)
                    <option @if($data->getAttribute('department_id') == $department->id) selected @endif value="{{$department->id}}">{{$department->getAttribute('name')}}</option>
                @endforeach
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
            <div class="form-group col-12">
                <textarea id="summernote" name="instruction"> {{$data->getAttribute('instruction')}}</textarea>
            </div>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')
    <script>
        $('#summernote').summernote({
            height: 500,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    </script>

    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
