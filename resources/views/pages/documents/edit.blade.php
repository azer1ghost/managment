@extends('layouts.main')

@section('title', __('translates.navbar.document'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('documents.index')">
            @lang('translates.navbar.document')
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
                    <x-input::text name="name" :value="optional($data)->getAttribute('name')" label="Document name" width="6" class="pr-3"/>
                    <x-input::text readonly :value="optional($data)->getAttribute('file')" label="Document file" width="6" class="pr-3"/>
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
