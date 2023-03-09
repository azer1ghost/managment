@extends('layouts.main')

@section('title', trans('translates.navbar.registration_logs'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('registration-logs.index')">
            @lang('translates.navbar.registration_logs')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
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
                        <label for="performer">Dərkənar</label><br/>
                        <select class="select2 form-control" name="performer" id="performer">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('performer') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 user">
                        <label for="receiver">İcraçı</label><br/>
                        <select class="select2 form-control" name="receiver" id="receiver">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('receiver') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-group class="pr-3 my-2 col-12 col-lg-12">
                        <x-form-textarea  name="description" label="Sənədin qısa məzmunu" placeholder="Sənədin məzmununu daxil edin"/>
                    </x-form-group>

                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="sender" label="Sənədi göndərən" placeholder="Göndərən şəxsi daxil edin"/>
                    </x-form-group>

                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="number" label="Sənədin nömrəsi" placeholder="Sənəd nömrəsi daxil edin"/>
                    </x-form-group>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_start_at">Qəbul tarixi</label>
                        <input type="datetime-local" name="arrived_at"
                               value="{{optional($data)->getAttribute('arrived_at')}}" id="data-arrived_at" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="data-companies">@lang('translates.clients.selectCompany')</label><br/>
                        <select id="data-companies" name="company_id"  required class="form-control" title="@lang('translates.filters.select')">
                            <option value="">@lang('translates.clients.selectCompany')</option>
                            @foreach($companies as $company)
                                <option
                                value="{{$company->getAttribute('id')}}" @if($data->getAttribute('company_id') == $company->id) selected @endif>{{$company->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($method !== 'POST')
                        <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                            <label for="data-will_start_at">Alınma Tarixi</label>
                            <input type="datetime-local" name="received_at"
                                   value="{{optional($data)->getAttribute('received_at')}}" id="data-received_at" class="form-control">
                        </div>
                    @endif
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
            <x-document-upload :id="$data->id" model="RegistrationLog"/>
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
