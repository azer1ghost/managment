@extends('layouts.main')

@section('title', trans('translates.navbar.intern_relation'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('internal-relations.index')">
            @lang('translates.navbar.intern_relation')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('case')}}
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
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="case" label="Əlaqə Saxlanılacaq Hal" placeholder="Əlaqə Saxlanılacaq Hal daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="applicant" label="Müraciət Edən Şəxs" placeholder="Müraciət Edən Şəxs daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="reciever" label="Əlaqə Saxlanılacaq Şəxs" placeholder="Əlaqə Saxlanılacaq Şəxs daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="tool" label="Əlaqə Vasitəsi" placeholder="Əlaqə Vasitəsi daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="contact_time" label="Əlaqə Zamanı" placeholder="Əlaqə Zamanı daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="ordering" label="Sıra" placeholder="Hansı sırada olacağını yazın"/>
                    </x-form-group>

                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" name="is_foreign" class="custom-control-input" id="foreign" @if($data->getAttribute('is_foreign')) checked @endif>
                    <label class="custom-control-label" for="foreign">@lang('translates.navbar.foreign_relation')</label>
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
