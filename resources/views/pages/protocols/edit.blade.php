@extends('layouts.main')

@section('title', trans('translates.navbar.protocols'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('protocols.index')">
            MB-P-023/05 @lang('translates.navbar.protocols')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{optional($data)->getAttribute('protocol_no')}}
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
                        <label for="performer">İcraçı</label><br/>
                        <select class="select2 form-control" name="performer" id="performer">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('performer') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 user">
                        <label for="signature">Kim İmzalamışdır</label><br/>
                        <select class="select2 form-control" name="signature" id="signature">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('signature') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-group class="pr-3 my-2 col-12 col-lg-12">
                        <x-form-textarea  name="content" label="Sənədin qısa məzmunu" placeholder="Sənədin məzmununu daxil edin"/>
                    </x-form-group>

                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="protocol_no" label="Protokol nömrəsi" placeholder="Protokol nömrəsi daxil edin"/>
                    </x-form-group>
                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_start_at">Tarixi</label>
                        <input type="text" name="date"
                               value="{{optional($data->getAttribute('date'))->format('Y-m-d')}}" id="data-date" class="form-control">
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
