@extends('layouts.main')

@section('title', __('translates.navbar.questionnaire'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('questionnaires.index')">
            @lang('translates.navbar.questionnaire')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{optional($data)->getRelationValue('client')->getAttribute('fullname')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
        <div class="row mt-4">
            <div class="col-12 form-group">
                <div class="row">
                    <div class="col-12 col-md-12 mb-2">
                        <label for="client_id">@lang('translates.general.select_client')</label>
                        <select name="client_id" id="client_id" class="form-control custom-select2" style="width: 100% !important;"
                                data-url="{{route('clients.search')}}">
                            <option value="" selected disabled>@lang('translates.general.select_client')</option>
                            @foreach($clients as $client)
                                <option @if(optional($data)->getAttribute('client_id') === $client->getAttribute('id')) selected @endif value="{{$client->getAttribute('id')}}">{{$client->getAttribute('fullname')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-bread.input.textarea name="novelty_us" :value="$data->getAttribute('novelty_us')" class="mt-3" :label="trans('translates.questionnaire.novelty_us')"></x-bread.input.textarea>
                    <x-bread.input.textarea name="novelty_customs" :value="$data->getAttribute('novelty_customs')" class="mt-2" :label="trans('translates.questionnaire.novelty_customs')"></x-bread.input.textarea>
                    <div class="form-group col-12 col-md-12 mb-3 mb-md-0">
                        <label for="data-datetime">@lang('translates.fields.created_at')</label>
                        <input type="date" placeholder="@lang('translates.fields.created_at')" name="datetime"
                               value="{{optional($data)->getAttribute('datetime')}}" id="data-datetime" class="form-control">
                        @error('datetime')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="form-check col-md-6 p-4">
                        <h3 class="mb-2">@lang('translates.questionnaire.what_customs')</h3>
                        @foreach($customses as $custom)
                            <input class="form-check-input ml-1" value="{{$custom}}" @if(in_array($custom,  explode("," , $data->getAttribute('customs')))) checked @endif type="checkbox" name="customs[]" id="customs-{{$custom}}">
                            <label class="form-check-label pl-4" for="customs-{{$custom}}">
                                @lang('translates.questionnaire.customs.'.$custom)</label>
                        @endforeach
                    </div>
                    <div class="form-check col-md-6 p-4">
                        <h3 class="mb-2">@lang('translates.questionnaire.what_source')</h3>
                        @foreach($sources as $source)
                            <input class="form-check-input ml-1" value="{{$source}}" type="checkbox" @if(in_array($source, explode("," , $data->getAttribute('source')))) checked @endif name="source[]" id="sources-{{$source}}">
                            <label class="form-check-label pl-4" for="sources-{{$source}}">
                                @lang('translates.questionnaire.sources.'.$source)</label>
                        @endforeach
                    </div>

                    <div class="custom-control custom-switch ml-3">
                        <input type="checkbox" name="send_email" class="custom-control-input" id="send_email" @if($data->getAttribute('send_email')) checked @endif>
                        <label class="custom-control-label" for="send_email">@lang('translates.buttons.send_email')</label>
                        @error('send_email') {{$errors}} @enderror
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
