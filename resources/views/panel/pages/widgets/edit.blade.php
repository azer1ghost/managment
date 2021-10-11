@extends('layouts.main')

@section('title', 'Widgets')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('widgets.index')">
            Widgets
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if (!is_null($data))
                {{optional($data)->getAttribute('key')}}
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
                    <x-input::text     name="key"  :value="optional($data)->getAttribute('key')"  label="Widget key"  width="6" class="pr-3" />
                    <x-input::text     name="class_attribute"  :value="optional($data)->getAttribute('class_attribute')"  label="Widget class attribute"  width="6" class="pr-3" />
                    <x-input::text     name="style_attribute"  :value="optional($data)->getAttribute('style_attribute')"  label="Widget style attribute"  width="6" class="pr-3" />
                    <x-input::text     name="icon"  :value="optional($data)->getAttribute('icon')"  label="Widget icon"  width="6" class="pr-3" />
                    <x-input::text     name="details"  :value="optional($data)->getAttribute('details')"  label="Widget details"  width="6" class="pr-3" />
                    <x-input::number   name="order"  :value="optional($data)->getAttribute('order')"  label="Widget order"  width="6" class="pr-3" />
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
