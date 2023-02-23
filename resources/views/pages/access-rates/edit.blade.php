@extends('layouts.main')

@section('title', trans('translates.navbar.access_rate'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('access-rates.index')">
            @lang('translates.navbar.access_rate')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('folder_id')}}
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
                        <label for="position_id">@lang('translates.fields.position')</label><br/>
                        <select class="select2 form-control" name="position_id" id="position_id">
                            <option value="">@lang('translates.general.position_select')</option>
                            @foreach($positions as $position)
                                <option @if($data->getAttribute('position_id') == $position->id) selected @endif value="{{$position->id}}">{{$position->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-6 user">
                        <label for="folder_id">@lang('translates.columns.folder')</label><br/>
                        <select class="select2 form-control" name="folder_id" id="folder_id">
                            <option value="">@lang('translates.general.folder_select')</option>
                            @foreach($folders as $folder)
                                <option @if($data->getAttribute('folder_id') == $folder->id) selected @endif value="{{$folder->id}}">{{$folder->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="composition" label="Tərkibi" placeholder="Tərkibini daxil edin"/>
                    </x-form-group>

                    <div class="custom-control custom-switch m-4">
                        <input type="checkbox" name="is_readonly" class="custom-control-input" id="is_readonly" @if($data->getAttribute('is_readonly')) checked @endif>
                        <label class="custom-control-label" for="is_readonly">Oxuya Bilər</label>
                    </div>
                    <div class="custom-control custom-switch m-4">
                        <input type="checkbox" name="is_change" class="custom-control-input" id="is_change" @if($data->getAttribute('is_change')) checked @endif>
                        <label class="custom-control-label" for="is_change">Dəyişiklik Edə Bilər</label>
                    </div>
                    <div class="custom-control custom-switch m-4">
                        <input type="checkbox" name="is_print" class="custom-control-input" id="is_print" @if($data->getAttribute('is_print')) checked @endif>
                        <label class="custom-control-label" for="is_print">Çap Edə Bilər</label>
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
