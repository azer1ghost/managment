@extends('layouts.main')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        @lang('departments')
                    </div>
                    <form action="{{route('departments.index')}}">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between mb-2">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                                            <a class="btn btn-outline-danger" href="{{route('departments.index')}}"><i class="fal fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @can('create', App\Models\Role::class)
                                    <div class="col-2">
                                        <a class="btn btn-outline-success float-right" href="{{route('departments.create')}}">@lang('translates.buttons.create')</a>
                                    </div>
                                @endcan
                                <div class="col-12">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($departments as $department)
                                            <tr>
                                                <th scope="row">{{$loop->iteration}}</th>
                                                <td>{{$department->getAttribute('name')}}</td>
                                                <td>
                                                    <div class="btn-sm-group">
                                                        @can('view', $department)
                                                            <a href="{{route('departments.show', $department)}}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fal fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                        @can('update', $department)
                                                            <a href="{{route('departments.edit', $department)}}" class="btn btn-sm btn-outline-success">
                                                                <i class="fal fa-pen"></i>
                                                            </a>
                                                        @endcan
                                                        @can('delete', $department)
                                                            <a href="{{route('departments.destroy', $department)}}" delete data-name="{{$department->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                                                <i class="fal fa-trash"></i>
                                                            </a>
                                                        @endcan
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <th colspan="4">
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
                                        {{$departments->links()}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection