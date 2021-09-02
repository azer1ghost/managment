@extends('layouts.main')

@section('title', __('translates.navbar.user'))

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <x-sidebar/>
            </div>
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        @lang('users')
                    </div>
                    <form action="{{route('users.index')}}">
                        <div class="card-body">
                            <div class="row d-flex justify-content-between mb-2">
                                <div class="col-6">
                                    <div class="input-group mb-3">
                                        <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                                            <a class="btn btn-outline-danger" href="{{route('users.index')}}"><i class="fal fa-times"></i></a>
                                        </div>
                                    </div>
                                </div>
                                @can('create', App\Models\User::class)
                                    <div class="col-2">
                                        <a class="btn btn-outline-success float-right" href="{{route('users.create')}}">@lang('translates.buttons.create')</a>
                                    </div>
                                @endcan
                                <div class="col-12">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">FIN</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Phone</th>
                                            <th scope="col">Department</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <th scope="row">{{$loop->iteration}}</th>
                                                <td>{{$user->getAttribute('fullname')}} @if($user->getAttribute('id') === auth()->id()) <h5 class="d-inline"><span class="badge badge-info text-white">Me</span></h5> @endif</td>
                                                <td>{{$user->getAttribute('fin')}}</td>
                                                <td>{{$user->getAttribute('email')}}</td>
                                                <td>{{$user->getAttribute('phone')}}</td>
                                                <td>{{$user->getRelationValue('department')->getAttribute('name')}}</td>
                                                <td>{{$user->getRelationValue('role')->getAttribute('name')}}</td>
                                                <td>
                                                    <div class="btn-sm-group">
                                                        @can('view', $user)
                                                            <a href="{{ $user->getAttribute('id') === auth()->id() ? route('account') : route('users.show', $user)}}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fal fa-eye"></i>
                                                            </a>
                                                        @endcan
                                                            @unless ($user->getAttribute('id') === auth()->id())
                                                                @can('update', $user)
                                                                    <a href="{{ $user->getAttribute('id') === auth()->id() ? route('account') : route('users.edit', $user)}}" class="btn btn-sm btn-outline-success">
                                                                        <i class="fal fa-pen"></i>
                                                                    </a>
                                                                @endcan
                                                                @can('delete', $user)
                                                                    <a href="{{route('users.destroy', $user)}}" delete data-name="{{$user->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                                                        <i class="fal fa-trash"></i>
                                                                    </a>
                                                                @endcan
                                                            @endif
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
                                        {{$users->links()}}
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