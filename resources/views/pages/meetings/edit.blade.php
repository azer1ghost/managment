@extends('layouts.main')

@section('title', __('translates.navbar.meeting'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('meetings.index')">
            @lang('translates.navbar.meeting')
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

                    <x-input::text name="name" :value="optional($data)->getAttribute('name')" label="Meeting name" width="6" class="pr-3"/>

                    <x-input::text name="datetime" :label="__('translates.fields.date')" value="{{optional($data)->getAttribute('datetime') ?? now()->format('Y-m-d')}}" type="text" width="6" class="pr-2" />

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
    </form>
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('form :input').attr('disabled', true)
        </script>
    @endif
@endsection
