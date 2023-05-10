@extends('layouts.main')

@section('title', trans('translates.navbar.commands'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('commands.index')">
            MB-P-023/04  @lang('translates.navbar.commands')
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

                    <div class="form-group col-6 user">
                        <label for="executor">İcraçı</label><br/>
                        <select class="select2 form-control" name="executor" id="executor">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if(auth()->user()->id == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 user">
                        <label for="user">İstifadəçi</label><br/>
                        <select class="select2 js-example-theme-multiple" multiple name="users[]" id="user"
                                data-width="fit">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option
                            @foreach($data->users as $dataUser)
                                @if(20 ==  $dataUser->id)
                                    selected
                                @endif
                            @endforeach
                            value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-6 user">
                        <label for="confirming">İmzalayan Şəxs</label><br/>
                        <select class="select2 form-control" name="confirming" id="confirming">
                            <option value="">@lang('translates.general.user_select')</option>
                            @foreach($users as $user)
                                <option @if($data->getAttribute('confirming') == $user->id) selected @endif value="{{$user->id}}">{{$user->getFullnameWithPositionAttribute()}}</option>
                            @endforeach
                        </select>
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
                    <x-form-group class="pr-3 my-2 col-12 col-lg-12">
                        <x-form-textarea  name="content" label="Əmrin məzmunu" placeholder="Əmrin məzmununu daxil edin"/>
                    </x-form-group>


                    <x-form-group class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="number" label="Əmrin nömrəsi" placeholder="Əmrin nömrəsini daxil edin"/>
                    </x-form-group>
                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_start_at">Əmr Tarixi</label>
                        <input type="date" name="command_date"
                               value="{{optional($data)->getAttribute('command_date')}}" id="data-command_date" class="form-control">
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
