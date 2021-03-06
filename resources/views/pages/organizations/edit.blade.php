@extends('layouts.main')

@section('title', __('translates.navbar.organization'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('organizations.index')">
            @lang('translates.navbar.organization')
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
        @bind($data)
        <div class=" row mt-4">
            <div class="form-group col-12">
                <div class="row">

                    <x-translate>
                        @foreach(config('app.locales') as $key => $locale)
                            <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                                <div class="row">
                                    <x-form-group class="pr-3 col-12 col-lg-6">
                                        <x-form-input name="name" :language="$key" label="Name {{$key}}" placeholder="Qurum daxil edin"/>
                                    </x-form-group>
                                    <x-form-group class="pr-3 col-12 col-lg-6">
                                        <x-form-input name="detail" :language="$key" label="Detail {{$key}}" placeholder="Detail daxil edin"/>
                                    </x-form-group>
                                </div>
                            </div>
                        @endforeach
                    </x-translate>

                    <div class="col-12 col-md-6 pr-3">
                        <div>
                            <input type="checkbox" @if(optional($data)->getAttribute('is_certificate') == true) checked @endif name="is_certificate" id="data-certificate">
                            <label class="form-check-label" for="data-certificate">
                                Is Certificate
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endbind
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
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
