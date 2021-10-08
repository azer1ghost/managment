@extends('layouts.main')

@section('title', 'Widgets')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Widgets
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('widgets.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('widgets.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Widget::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('widgets.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Key</th>
                        <th scope="col">Details</th>
                        <th scope="col">Icon</th>
                        <th scope="col">Order</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($widgets as $widget)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$widget->getAttribute('key')}}</td>
                            <td>{{$widget->getAttribute('details')}}</td>
                            <td>{!! $widget->getAttribute('icon') !!}</td>
                            <td>{{$widget->getAttribute('order')}}</td>
                            <td>
                                {!! $widget->getAttribute('status') ?
                                    '<i class="fas fa-check-circle text-success" style="font-size:18px"></i>':
                                    '<i class="fas fa-times-circle text-danger"  style="font-size:18px"></i>'
                                !!}
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $widget)
                                        <a href="{{route('widgets.show', $widget)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $widget)
                                        <a href="{{route('widgets.edit', $widget)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $widget)
                                        <a href="{{route('widgets.destroy', $widget)}}" delete data-name="{{$widget->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$widgets->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection