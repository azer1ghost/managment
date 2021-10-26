@extends('layouts.main')

@section('title', __('translates.navbar.update'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.update')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('updates.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="hidden" name="type" value="table">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('updates.index', ['type' => 'table'])}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Update::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('updates.create')}}">@lang('translates.buttons.create')</a>
                    <a class="btn btn-outline-primary float-right mr-3" href="{{route('updates.index')}}">Map view</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">User</th>
                        <th scope="col">Content</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($updates as $update)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$update->getAttribute('name')}}</td>
                            <td>{{$update->getRelationValue('user')->getAttribute('fullname')}}</td>
                            <td>{{ strlen($update->getAttribute('content')) >= 50 ? substr($update->getAttribute('content'), 50) . '...' : $update->getAttribute('content') }}</td>
                            <td>{{__('translates.updates')[$update->getAttribute('status')]}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $update)
                                        <a href="{{route('updates.show', $update)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $update)
                                        <a href="{{route('updates.edit', $update)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $update)
                                        <a href="{{route('updates.destroy', $update)}}" delete data-name="{{$update->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="6">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$updates->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection