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
        @bind($data)
        <div class="row mt-4">
            <div class="form-group col-12">
                <div class="row">
                    <x-form-group  class="pr-3 col-12 col-lg-6"  >
                        <x-form-input  name="name" label="Meeting name" placeholder="Meeting name daxil edin"/>
                    </x-form-group>
                    <div class="col-12 col-md-6 pr-3">
                        <label for="department_id">Update department</label>
                        <select name="department_id" id="department_id" class="form-control">
                            <option value="" selected disabled>Select department</option>
                            @foreach($departments as $department)
                                <option @if(optional($data)->getAttribute('department_id') === $department->getAttribute('id')) selected @endif value="{{$department->getAttribute('id')}}">{{$department->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_start_at">Will Start At</label>
                        <input type="text" placeholder="Will Start At" name="will_start_at"
                               value="{{optional($data)->getAttribute('will_start_at')}}" id="data-will_start_at" class="form-control custom-single-daterange">
                        @error('will_start_at')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="data-will_end_at">Will  End At</label>
                        <input type="text" placeholder="Will Notify At" name="will_end_at"
                               value="{{optional($data)->getAttribute('will_end_at')}}" id="data-will_end_at" class="form-control custom-single-daterange" >
                        @error('will_end_at')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
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
