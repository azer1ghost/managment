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
                </div>
            </div>
        </div>
        @if($method != 'POST')
            <div class="my-5">
                <x-documents :documents="$data->documents" :title="trans('translates.navbar.job_instruction')" />
                <x-document-upload :id="$data->id" model="JobInstruction"/>
            </div>
        @endif
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
