@extends('layouts.main')

@section('title', __('translates.navbar.department'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('departments.index')">
            @lang('translates.navbar.department')
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
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class=" row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-input::text  name="translate[name][{{$key}}]"  :value="optional($data)->getTranslation('name', $key)"     :label="trans('translates.columns.department')"     width="6" class="pr-3" />
                                    <x-input::text  name="translate[short_name][{{$key}}]"  :value="optional($data)->getTranslation('short_name', $key)"     :label="trans('translates.columns.short_name')"     width="6" class="pr-3" />
                                </div>
                            </div>
                        @endforeach
                    </x-translate>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" @if(optional($data)->getAttribute('status') === true) checked @endif name="status" id="data-status">
                    <label class="form-check-label" for="data-status">
                        Is Active
                    </label>
                </div>
                @if(auth()->user()->isDeveloper())
                    <div class="my-3">
                        <x-permissions :model="$data" :action="$action" />
                    </div>
                @endif
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
