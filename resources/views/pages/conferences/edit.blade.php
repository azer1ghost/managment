@extends('layouts.main')

@section('title', __('translates.navbar.conference'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('conferences.index')">
            @lang('translates.navbar.conference')
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

                    <x-form-group  class="pr-3 col-12 col-lg-6">
                        <x-form-input  name="name" label="Conference name"/>
                    </x-form-group>

                    <x-form-group  :label="__('translates.fields.date')"  class="pr-3 col-12 col-lg-6">
                        <x-form-input   name="datetime" />
                    </x-form-group>

                    <div class="col-12 col-md-6 pr-3">
                        <label for="data-status">Update Status</label>
                        <select name="status" id="data-status" class="form-control">
                            <option value="" selected disabled>Select status</option>
                            @foreach($statuses as $index => $status)
                                <option @if(optional($data)->getAttribute('status') === $index) selected @endif value="{{$index}}">{{$status}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit :value="__('translates.buttons.save')"/>
        @endif

        @endbind
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
