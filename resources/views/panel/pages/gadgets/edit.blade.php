@extends('layouts.main')

@section('title', 'Gadgets')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('departments.index')">
            Gadgets
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
        <div class="tab-content row mt-4" >
            <div class="form-group col-12">
                <div class="row">
                    <x-input::text     name="key"  :value="optional($data)->getAttribute('key')"  label="Gadget key"  width="6" class="pr-3" />
                    <x-input::text     name="type"  :value="optional($data)->getAttribute('type')"  label="Gadget type"  width="6" class="pr-3" />
                    <x-input::text     name="name"  :value="optional($data)->getAttribute('name')"  label="Gadget name"  width="6" class="pr-3" />
                    <x-input::text     name="labels"  :value="optional($data)->getAttribute('labels')"  label="Gadget labels"  width="6" class="pr-3" />
                    <x-input::text     name="colors"  :value="optional($data)->getAttribute('colors')"  label="Gadget colors"  width="6" class="pr-3" />
                    <x-input::text     name="icon"  :value="optional($data)->getAttribute('icon')"  label="Gadget icon"  width="6" class="pr-3" />
                    <x-input::text     name="color"  :value="optional($data)->getAttribute('color')"  label="Gadget color"  width="6" class="pr-3" />
                    <x-input::text     name="bg_color"  :value="optional($data)->getAttribute('bg_color')"  label="Gadget bg color"  width="6" class="pr-3" />
                    <x-input::text     name="details"  :value="optional($data)->getAttribute('details')"  label="Gadget details"  width="6" class="pr-3" />
                    <x-input::number   name="order"  :value="optional($data)->getAttribute('order')"  label="Gadget order"  width="6" class="pr-3" />
                    <x-input::textarea  name="query"  :value="optional($data)->getAttribute('query')"  label="Gadget query"  width="12" class="pr-3" />
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" @if(optional($data)->getAttribute('status') === true) checked @endif name="status" id="data-status">
                    <label class="form-check-label" for="data-status">
                        Is Active
                    </label>
                </div>
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
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
