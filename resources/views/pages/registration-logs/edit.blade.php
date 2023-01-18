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
            @if ($method !== 'POST' )
                {{optional($data)->getAttribute('sender')}}
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
                        <label for="user_id">Dərkənar</label><br/>
                        <select class="select2 form-control" name="receiver" id="receiver">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('user') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 user">
                        <label for="responsible">İcraçı</label><br/>
                        <select class="select2 form-control" name="performer" id="performer">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('user') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_start_at">Qəbul tarixi</label>
                        <input type="datetime-local"  name="arrived_at"
                               value="{{optional($data)->getAttribute('arrived_at')}}" id="data-arrived_at" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_start_at">Alınma Tarixi</label>
                        <input type="datetime-local"  name="received_at"
                               value="{{optional($data)->getAttribute('received_at')}}" id="data-received_at" class="form-control">
                    </div>

                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-textarea  name="description" label="Sənədin qısa məzmunu" placeholder="Dəyişikliyin təsvirini daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="sender" label="Sənədi göndərən" placeholder="Dəyişikliyin səbəbini daxil edin"/>
                    </x-form-group>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="number" label="Sənədin nömrəsi" placeholder="Təsirini daxil edin"/>
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
