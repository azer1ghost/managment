@extends('layouts.main')

@section('title', 'Gadgets')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Gadgets
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('gadgets.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('gadgets.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Gadget::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('gadgets.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Key</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($gadgets as $gadget)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$gadget->getAttribute('key')}}</td>
                            <td>{{$gadget->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $gadget)
                                        <a href="{{route('gadgets.show', $gadget)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $gadget)
                                        <a href="{{route('gadgets.edit', $gadget)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $gadget)
                                        <a href="{{route('gadgets.destroy', $gadget)}}" delete data-name="{{$gadget->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$gadgets->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection