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
        @bind($data)
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
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
                                        <x-form-textarea name="short_name" :language="$key" label="Detail {{$key}}" placeholder="Qısa ad daxil edin"/>
                                    </x-form-group>
                                </div>
                            </div>
                        @endforeach
                    </x-translate>
                </div>
                <div>
                    <input type="checkbox" @if(optional($data)->getAttribute('status') === true) checked @endif name="status" id="data-status">
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
        @endbind
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
