@extends('layouts.main')

@section('title', trans('translates.navbar.partners'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('partners.index')">
            @lang('translates.navbar.partners')
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
        <div class="tab-content row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text name="name" :label="trans('translates.columns.name')" :value="$data->getAttribute('name')" width="6" class="pr-3"/>
                    <x-input::text name="phone" :label="trans('translates.columns.phone')" :value="$data->getAttribute('phone')" width="6" class="pr-2" />
                    <x-input::textarea name="note" :label="trans('translates.fields.note')" :value="$data->getAttribute('note')" width="6" class="pr-2" />
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="trans('translates.buttons.save')"/>
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
