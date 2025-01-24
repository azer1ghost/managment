@extends('layouts.main')

@section('title', __('translates.navbar.rule'))
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
            @lang('translates.navbar.folder')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('rules.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sənəd Adı</th>
                        <th scope="col">Sənəd</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($rules as $rule)
                            <tr id="item-{{$rule->getAttribute('id')}}">
                            <th>{{$loop->iteration}}</th>
                            <th>{{$rule->getAttribute('name')}}</th>
                                <td> @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                                    @foreach($rule->documents as $document)
                                        @php($type = $supportedTypes[$document->type])
                                        @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                        <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style=" word-break: break-word">
                                            <i class="fa fa-file-{{$type['icon']}} fa-2x m-1 text-{{$type['color']}}"></i>
                                            <span>{{substr($document->name, 0, 10) . '...'}} </span>
                                        </a>
                                    @endforeach</td>
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('rules.show', $rule)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('rules.edit', $rule)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('rules.destroy', $rule)}}" delete data-name="{{$rule->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
                                                <i class="fal fa-trash"></i>
                                            </a>
                                    </div>
                                </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
@endsection
