@extends('layouts.main')

@section('title', __('translates.navbar.necessary'))
@section('style')
    <style>
        table {
            table-layout:fixed;
            width:100%;
        }
        td, th {
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="row col-12">
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('necessaries.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @foreach($necessaries as $necessary)
                <div class="card text-center col-md-3 m-3">
                    <div class="card-header">
                        {{$necessary->getAttribute('name')}}
                    </div>

                    <div class="card-body">
                        <h5 class="card-title"></h5>
{{--                        <p class="card-text">--}}
                            @foreach(explode("\n", $necessary->getAttribute('detail')) as $item)
                                @if(!empty($item))
                                <ol>
                                    <li>{{ $item }}</li>
                                </ol>
                                @endif
                            @endforeach
{{--                                </p>--}}
                    </div>
                    <div class="btn-sm-group">
                        <a href="{{route('necessaries.show', $necessary)}}" class="btn btn-sm btn-outline-primary">
                            <i class="fal fa-eye"></i>
                        </a>
                        <a href="{{route('necessaries.edit', $necessary)}}" class="btn btn-sm btn-outline-success">
                            <i class="fal fa-pen"></i>
                        </a>
                        <a href="{{route('necessaries.destroy', $necessary)}}" delete data-name="{{$necessary->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
                            <i class="fal fa-trash"></i>
                        </a>
                    </div>
                    <div style="min-width: 150px">
                        @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                        @foreach($necessary->documents as $document)
                            @php($type = $supportedTypes[$document->type])
                            @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                            <a href="{{$route}}" data-toggle="tooltip" title="{{$document->name}}" target="_blank" class="text-dark" style="word-break: break-word">
                                <i class="fa fa-file-{{$type['icon']}} fa-2x text-{{$type['color']}}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
    @endforeach
@endsection
