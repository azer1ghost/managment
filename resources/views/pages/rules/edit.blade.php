@extends('layouts.main')

@section('title', trans('translates.navbar.rule'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('rules.index')">
            @lang('translates.navbar.rule')
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
        <div class="row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="name" label="Rule Adı" placeholder="Rule adı daxil edin"/>
                    </x-form-group>
                </div>
            </div>
            <div class="form-group col-12">
                <textarea id="summernote" name="content"> {{$data->getAttribute('content')}}</textarea>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif
        @endbind
    </form>
@endsection
@section('scripts')
    <script>
        $('#summernote').summernote({
            height: 500,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    </script>

    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
