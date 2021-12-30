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
            @if (!is_null($data))
                {{optional($data)->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf

        <div class="tab-content row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-input::text  name="translate[name][{{$key}}]"  :value="$data->getTranslation('name', $key)"  width="6" class="pr-3" label="{{trans('translates.columns.name')}}"/>
                                    <x-input::textarea  name="translate[description][{{$key}}]"  :value="$data->getTranslation('description', $key)"  width="6" class="pr-3" label="{{trans('translates.columns.detail')}}"/>
                                </div>
                            </div>
                        @endforeach
                    </x-translate>
                    <div class="col-12">

                        @foreach($hard_columns as $hard_column)
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="checkbox"
                                       @if(in_array($hard_column, explode("," , $data->getAttribute('hard_columns')))) checked @endif
                                       name="hard_columns[]" value="{{$hard_column}}"
                                       id="data-hard_columns-{{$loop->index}}"
                                >
                                <label class="form-check-label" for="data-hard_columns-{{$loop->index}}">
                                    {{$hard_column}}
                                </label>
                            </div>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
