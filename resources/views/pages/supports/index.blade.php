@extends('layouts.main')

@section('title', __('translates.navbar.supports'))
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
            @lang('translates.navbar.supports')
        </x-bread-crumb-link>
    </x-bread-crumb>
            <div class="col-12">
                @can('create', App\Models\Support::class)
                    <div class="col-12">
                        <a class="btn btn-outline-success float-right" href="{{route('supports.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                            <th scope="col">Adı</th>
                            <th scope="col">Detal</th>
                            <th scope="col">Nömrəsi</th>
                    </tr>
                    </thead>
                    <tbody id="sortable">
                    @forelse($supports as $support)
                            <tr id="item-{{$support->getAttribute('id')}}">
                            <th>{{$loop->iteration}}</th>
                            <th>{{$support->getAttribute('name')}}</th>
                            <th>{{$support->getAttribute('detail')}}</th>
                            <th>{{$support->getAttribute('phone')}}</th>
                            @can('update', App\Models\Support::class)
                                <td>
                                    <div class="btn-sm-group">
                                            <a href="{{route('supports.show', $support)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                            <a href="{{route('supports.edit', $support)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                            <a href="{{route('supports.destroy', $support)}}" delete data-name="{{$support->getAttribute('id')}}" class="btn btn-sm btn-outline-danger" >
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
            </div>
@endsection
