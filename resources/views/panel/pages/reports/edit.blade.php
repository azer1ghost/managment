@extends('layouts.main')

@section('title', __('translates.navbar.report'))

@section('style')
    <!-- include summernote css -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

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
                @lang('translates.buttons.create')
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{$data->getAttribute('id')}}">
        <input type="hidden" name="date" value="{{$data->getAttribute('date') ?? request()->get('day') ?? now()}}">
        <textarea name="detail" id="summernote" class="form-control">{{$data->getAttribute('detail')}}</textarea>
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    @if(is_null($action))
        <script>
            $('input').attr('readonly', true)
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const summernote = $('#summernote');
            summernote.summernote({
                placeholder: '{{trans('translates.fields.detail')}}',
                height: 400,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear', 'italic']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            summernote.summernote('{{is_null($action) ? 'disable' : 'enable'}}');
        });
    </script>

@endsection
