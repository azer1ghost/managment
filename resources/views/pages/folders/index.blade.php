@extends('layouts.main')

@section('title', __('translates.navbar.folder'))
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
                @can('create', App\Models\Folder::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('folders.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                @can('create', App\Models\Folder::class)
                    <div class="col-12">
                        <a class="btn btn-outline-primary float-left" href="{{route('access-rates.index')}}">@lang('translates.navbar.access_rate')</a>
                    </div>
                @endcan
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Qovluq Adı</th>
                        
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($folders as $folder)
                            <tr id="item-{{$folder->getAttribute('id')}}">
                            <th>{{$loop->iteration}}</th>
                            <th>{{$folder->getAttribute('name')}}</th>
                            @can('update', App\Models\Folder::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('folders.show', $folder)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('folders.edit', $folder)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('folders.destroy', $folder)}}" delete data-name="{{$folder->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
                                                <i class="fal fa-trash"></i>
                                            </a>
                                    </div>
                                </td>
                            @endcan
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
