@extends('layouts.main')

@section('title', __('translates.navbar.client'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link is-current="1">
            @lang('translates.navbar.client')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('clients.index')}}">
            <div class="row d-flex justify-content-between mb-2">
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                            <a class="btn btn-outline-danger" href="{{route('clients.index')}}"><i class="fal fa-times"></i></a>
                        </div>
                    </div>
                </div>
                @can('create', App\Models\Client::class)
                    <div class="col-2">
                        <a class="btn btn-outline-success float-right" href="{{route('clients.create')}}">@lang('translates.buttons.create')</a>
                    </div>
                @endcan
                <div class="col-12">
                    <table class="table table-responsive-sm table-hover">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Company</th>
                            <th scope="col">VOEN</th>
                            <th scope="col">Address</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($clients as $client)
                            <tr>
                                <th scope="row">{{$client->getAttribute('id')}}</th>
                                <td>{{$client->getAttribute('fullname')}}</td>
                                <td>{{$client->getAttribute('email')}}</td>
                                <td>{{$client->getAttribute('phone')}}</td>
                                <td>{{$client->getAttribute('company')}}</td>
                                <td>{{$client->getAttribute('voen')}}</td>
                                <td>{{$client->getAttribute('address')}}</td>
                                <td>
                                    <div class="btn-sm-group">
                                        @can('view', $client)
                                            <a href="{{route('clients.show', $client)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                        @endcan
                                            @unless ($client->getAttribute('id') === auth()->id())
                                                @can('update', $client)
                                                    <a href="{{route('clients.edit', $client)}}" class="btn btn-sm btn-outline-success">
                                                        <i class="fal fa-pen"></i>
                                                    </a>
                                                @endcan
                                                @can('delete', $client)
                                                    <a href="{{route('clients.destroy', $client)}}" delete data-name="{{$client->getAttribute('fullname')}}" class="btn btn-sm btn-outline-danger" >
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
                <div class="col-6">
                    <div class="float-right">
                        {{$clients->links()}}
                    </div>
                </div>
            </div>
    </form>
@endsection