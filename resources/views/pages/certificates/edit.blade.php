@extends('layouts.main')

@section('title', trans('translates.navbar.certificate'))

@section('content')
    <x-bread-crumb xmlns:x-input="http://www.w3.org/1999/html">
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('certificates.index')">
            @lang('translates.navbar.certificate')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method !== 'POST')
                {{$data->getAttribute('name')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
        <input type="hidden" name="id" value="{{$data->getAttribute('id')}}">
        <div class=" row mt-4" >
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
                                        <x-form-textarea name="detail" :language="$key" label="Detail {{$key}}" placeholder="Açıqlama daxil edin"/>
                                    </x-form-group>
                                </div>
                            </div>
                        @endforeach
                    </x-translate>

                    <x-input::select  name="organization_id" :value="$data->getAttribute('organization_id')" :label="trans('translates.columns.organization')"  width="6" class="pr-3" :options="$organizations"/>
                </div>
            </div>
        </div>
        @endbind
    @if($action)
            <x-input::submit  :value="trans('translates.buttons.save')" />
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
