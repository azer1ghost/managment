@extends('layouts.main')

@section('title', trans('translates.navbar.changes'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('changes.index')">
            @lang('translates.navbar.changes')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('id')}}
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
                        <label for="department_id">Aid olduğu proses/şöbə</label><br/>
                        <select class="select2 form-control" name="department_id" id="department_id">
                            <option value="">@lang('translates.general.all_departments')</option>
                            @foreach($departments as $department)
                                <option @if($data->getAttribute('department_id') == $department->id) selected @endif value="{{$department->id}}">{{$department->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 user">
                        <label for="user_id">Dəyişikliyin sahibi</label><br/>
                        <select class="select2 form-control" name="user_id" id="user_id">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('user_id') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 user">
                        <label for="responsible">Dəyişikliyin təhlilinə və tətbiq edilməsinə məsul bölmə/şəxs</label><br/>
                        <select class="select2 form-control" name="responsible" id="responsible">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('responsible') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 col-md-6 ">
                        <label for="user_id">Tarix Seçin</label><br/>
                        <input type="text" aria-label="datetime" class="form-control" readonly placeholder="@lang('translates.placeholders.range')" name="datetime"
                               value="{{optional($data->getAttribute('datetime'))->format('Y-m-d')}}" >
                    </div>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="description" label="Dəyişikliyin təsviri" placeholder="Dəyişikliyin təsvirini daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="reason" label="Dəyişikliyin səbəbi" placeholder="Dəyişikliyin səbəbini daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="result" label="Təsiri" placeholder="Təsirini daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="note" label="Note" placeholder="Note daxil edin"/>
                    </x-form-group>
                    <div class="form-group col-6 user">
                        <label for="effectivity">@lang('translates.employee_satisfactions.effectivity')</label><br/>
                        <select class="form-control" name="effectivity" id="effectivity">
                            <option value="">@lang('translates.employee_satisfactions.effectivity') @lang('translates.placeholders.choose')</option>
                            @foreach($effectivity as $effect)
                                <option @if($data->getAttribute('effectivity') == $effect) selected @endif value="{{$effect}}">@lang('translates.effectivity.'.$effect)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
        @endbind
    </form>
    @if($method != 'POST')
        <div class="my-5">
            <x-documents :documents="$data->documents" :title="trans('translates.navbar.document')" />
            <x-document-upload :id="$data->id" model="Change"/>
        </div>
    @endif
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif

@endsection
