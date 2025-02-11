@extends('layouts.main')

@section('title', __('translates.navbar.report'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('reports.index')">
            @lang('translates.navbar.report')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('reports.subs.show', $parent)">
            {{$parent->getRelationValue('chief')->getAttribute('fullname_with_position')}}
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @if ($method != 'POST')
                {{$data->getAttribute('date')}}
            @else
                {{request()->get('day') ?? trans('translates.buttons.create')}}
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    @if($method == null)
{{--        @can('updateSubReport', $data)--}}
            <div class="col-12 my-3 pl-0">
                <a class="btn btn-outline-success" href="{{route('reports.sub.edit', $data)}}">@lang('translates.tasks.edit')</a>
            </div>
{{--        @endcan--}}
    @endif
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{$data->getAttribute('id')}}">
        <input type="hidden" name="date" value="{{$data->getAttribute('date') ?? request()->get('day')}}">
        @error('date')
            <p class="text-danger">{{$message}}</p>
        @enderror
        <textarea aria-label="detail" name="detail" id="summernote" class="form-control">{{$data->getAttribute('detail')}}</textarea>
        @error('detail')
        <p class="text-danger">{{$message}}</p>
        @enderror
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
    @if($method != 'POST')
        <div class="col-12">
            <x-documents :documents="$data->documents ?? collect([])" />
            <x-document-upload :id="$data->id" model="DailyReport"/>
        </div>
    @endif
@endsection
@section('scripts')
    <script>
        $('#summernote').summernote({
            height: 400,
            minHeight: null,
            maxHeight: null,
            focus: true
        });
    </script>
@endsection