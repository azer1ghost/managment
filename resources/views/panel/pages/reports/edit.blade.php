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
                {{request()->get('day') ?? trans('translates.buttons.create')}}
            @endif
        </x-bread-crumb-link>
    </x-bread-crumb>
    @if($method == null)
        @can('updateSubReport', $data)
            <div class="col-12 my-3 pl-0">
                <a class="btn btn-outline-success" href="{{route('reports.sub.edit', $data)}}">@lang('translates.tasks.edit')</a>
            </div>
        @endcan
    @endif
    <form action="{{$action}}" method="POST" enctype="multipart/form-data">
        @method($method) @csrf
        <input type="hidden" name="id" value="{{$data->getAttribute('id')}}">
        <input type="hidden" name="date" value="{{$data->getAttribute('date') ?? request()->get('day')}}">
        @error('date')
            <p class="text-danger">{{$message}}</p>
        @enderror
        <textarea name="detail" id="summernote" class="form-control">{{$data->getAttribute('detail')}}</textarea>
        @error('detail')
        <p class="text-danger">{{$message}}</p>
        @enderror
        @if($action)
            <x-input::submit  :value="__('translates.buttons.save')" />
        @endif
    </form>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

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
                ],
                callbacks: {
                    onInit: function (content) {
                        if(summernote.summernote('isEmpty') || content.editable.html() === '<p><br></p>'){
                            summernote.html('');
                        }
                    },
                    onChange: function (content, $editable) {
                        if (summernote.summernote('isEmpty') || $editable.html() === '<p><br></p>') {
                            summernote.html('');
                        }
                    }
                }
            });
            summernote.summernote('{{is_null($action) ? 'disable' : 'enable'}}');
        });
    </script>

@endsection
