@extends('layouts.main')

@section('title', __('translates.navbar.update'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('updates.index')">
            @lang('translates.navbar.update')
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
                    <x-input::text  name="name"  :value="optional($data)->getAttribute('name')"  label="Update name"  width="6" class="pr-3" />
                    <div class="col-12 col-md-6">
                        <label for="data-user_id">Update Status</label>
                        <select name="status" id="data-user_id" class="form-control">
                            <option value="" selected disabled>Select status</option>
                            @foreach($statuses as $index => $status)
                                <option @if(optional($data)->getAttribute('status') === $index) selected @endif value="{{$index}}">{{$status}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('status')
                        <span class="invalid-feedback" role="alert">
                            <strong>{!! $message !!}</strong>
                        </span>
                    @enderror
                    <x-input::textarea name="content" :value="optional($data)->getAttribute('content')" label="Update content"  width="6" class="pr-3" />
                </div>
            </div>
        </div>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
    @if($method != "POST")
        <livewire:commentable :commentable="$data" :url="str_replace('/edit', '', url()->current())"/>
    @endif
@endsection
@section('scripts')
    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif
@endsection
