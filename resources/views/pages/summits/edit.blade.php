@extends('layouts.main')

@section('title', trans('translates.navbar.summits'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('summits.index')">
            MB-P-023/04  @lang('translates.navbar.summits')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{optional($data)->getAttribute('executor')}}
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

                    <div class="form-group col-lg-3 user">
                        <label for="user">İştirakçılar</label><br/>
                        <select class="select2 js-example-theme-multiple" multiple name="users[]" id="user"
                                data-width="fit">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option
                            @foreach($data->users as $dataUser)
                                @if($user->getAttribute('id') == $dataUser->id)
                                    selected
                                @endif
                            @endforeach
                            value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input  name="club" label="Klub Adı" placeholder="Klub Adını daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input  name="place" label="Yer" placeholder="Yer Adını daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input  name="format" label="Format" placeholder="Klub Adını daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input  name="event" label="Tədbir Adı" placeholder="Tədbir Adını daxil edin"/>
                    </x-form-group>
                    <x-form-group class="pr-3 col-12 col-lg-3">
                        <x-form-input  name="dresscode" label="Dress Code" placeholder="Dress Code daxil edin"/>
                    </x-form-group>
                    <div class="form-group col-12 col-md-3" wire:ignore>
                        <label for="data-status">@lang('translates.general.status_choose')</label>
                        <select name="status" id="data-status" class="form-control">
                            <option disabled >@lang('translates.general.status_choose')</option>
                            @foreach($statuses as $key => $status)
                                <option
                                        @if(optional($data)->getAttribute('status') === $status ) selected
                                        @endif value="{{$status}}"
                                >
                                    @lang('translates.summit_status.' . $key)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-date">Tarixi</label>
                        <input type="datetime-local" name="date"
                               value="{{$data->getAttribute('date')}}" id="data-date" class="form-control">
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
    <script>
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    </script>
@endsection
