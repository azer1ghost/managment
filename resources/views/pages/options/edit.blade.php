@extends('layouts.main')

@section('title', __('translates.navbar.option'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('options.index')">
            @lang('translates.navbar.option')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('text')}}
            @else
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        @bind($data)
        <input type="hidden" name="id" value="{{optional($data)->getAttribute('id')}}">
        <div class=" form-row mt-4" >
            <x-translate>
                @foreach(config('app.locales') as $key => $locale)
                    <div class="tab-pane fade show @if($loop->first) active @endif" id="data-{{$key}}" role="tabpanel">
                        <x-form-group class="col-12 col-md-6">
                            <x-form-input name="text" :language="$key" label="Name {{$key}}" placeholder="Ad daxil edin"/>
                        </x-form-group>
                    </div>
                @endforeach
            </x-translate>
        </div>
        @endbind
        @if($action)
            <x-input::submit />
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