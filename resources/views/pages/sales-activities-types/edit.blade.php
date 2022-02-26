@extends('layouts.main')

@section('title', __('translates.navbar.sales_activities_type'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('sales-activities-types.index')">
            @lang('translates.navbar.sales_activities_type')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
        <div class=" row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-form-group class="col-12 col-md-6">
                                        <x-form-input name="name" :language="$key" label="Name {{$key}}" placeholder="Ad daxil edin"/>
                                    </x-form-group>
                                    <x-form-group class="col-12 col-md-6">
                                        <x-form-textarea name="description" :language="$key" label="Description {{$key}}" placeholder="Açıqlama daxil edin"/>
                                    </x-form-group>
                                </div>
                            </div>
                        @endforeach
                    </x-translate>
                    <div class="col-12">

                        @foreach($hard_columns as $key => $hard_column)
                            <div>
                                <input type="checkbox"
                                       @if(in_array($key, explode("," , $data->getAttribute('hard_columns')))) checked @endif
                                       name="hard_columns[]" value="{{$key}}"
                                       id="data-hard_columns-{{$loop->index}}">

                                <label class="form-check-label" for="data-hard_columns-{{$loop->index}}">
                                    {{$hard_column}}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endbind
        @if($method)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')
    @if(is_null($method))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
