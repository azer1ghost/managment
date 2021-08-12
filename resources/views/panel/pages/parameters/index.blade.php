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
                    @lang('parameters')
                </div>
                <div class="card-body">
                    <div class="float-right mb-2">
                        @can('create', App\Models\Parameter::class)
                            <a class="btn btn-outline-success" href="{{route('parameters.create')}}">@lang('btn.create')</a>
                        @endcan
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Parent Parameter</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($parameters as $parameter)
                        <tr>
                            <th scope="row">{{$parameter->id}}</th>
                            <td>{{$parameter->name}}</td>
                            <td>{{$parameter->type}}</td>
                            <td>{{ optional($parameter->parameter)->name}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $parameter)
                                        <a href="{{route('parameters.show', $parameter)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $parameter)
                                        <a href="{{route('parameters.edit', $parameter)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $parameter)
                                        <a href="{{route('parameters.destroy', $parameter)}}" delete data-name="{{$parameter->name}}" class="btn btn-sm btn-outline-danger" >
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
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now. Yo can create new company</div>
                                </div>
                            </th>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="float-right">
                        {{$parameters->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

