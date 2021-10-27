@extends('layouts.main')

@section('title', __('translates.navbar.work'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.work')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('works.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('works.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Work::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('works.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.fields.detail')</th>
                        <th scope="col">@lang('translates.fields.user')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($works as $work)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$work->getAttribute('name')}}</td>
                            <td>{{$work->getAttribute('detail')}}</td>
                            <td>{{$work->getRelationValue('user')->getAttribute('name')}}</td>
                            <td>{{$work->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>{{$work->getRelationValue('company')->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $work)
                                        <a href="{{route('works.show', $work)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $work)
                                        <a href="{{route('works.edit', $work)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $work)
                                        <a href="{{route('works.destroy', $work)}}" delete data-name="{{$work->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="7">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$works->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection