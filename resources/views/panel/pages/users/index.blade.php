@extends('layouts.main')

@section('title', __('translates.navbar.user'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.user')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('users.index')}}">
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
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Full Name</th>
                        <th scope="col">FIN</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Company</th>
                        <th scope="col">Department</th>
                        <th scope="col">Role</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <th scope="row"><img src="{{image($user->getAttribute('avatar'))}}" alt="user" class="profile" /></th>
                            <td>{{$user->getAttribute('fullname')}} @if($user->getAttribute('id') === auth()->id()) <h5 class="d-inline"><span class="badge badge-info text-white">Me</span></h5> @endif</td>
                            <td>{{$user->getAttribute('fin')}}</td>
                            <td>{{$user->getAttribute('email')}}</td>
                            <td>{{$user->getAttribute('phone')}}</td>
                            <td>{{$user->getRelationValue('company')->getAttribute('name')}}</td>
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
                                                <a href="{{route('users.destroy', $user)}}" delete data-name="{{$user->getAttribute('fullname')}}" class="btn btn-sm btn-outline-danger" >
                                                    <i class="fal fa-trash"></i>
                                                </a>
                                            @endcan
                                        @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="8">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Not found!</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection