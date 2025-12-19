@extends('layouts.main')

@section('title', trans('translates.navbar.announcement'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.announcement')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('statements.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('statements.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Statement::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('statements.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.title')</th>
                        <th scope="col">@lang('translates.columns.detail')</th>
                        <th scope="col">@lang('translates.columns.attribute')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($statements as $statement)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$statement->getAttribute('title')}}</td>
                            <td>{{$statement->getAttribute('body')}}</td>
                            <td>{{$statement->getAttribute('attribute')}}</td>

                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $statement)
                                        <a href="{{route('statements.show', $statement)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $statement)
                                        <a href="{{route('statements.edit', $statement)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $statement)
                                        <a href="{{route('statements.destroy', $statement)}}" delete data-name="{{$statement->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
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
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$statements->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection