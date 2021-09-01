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
                    @lang('gadgets')
                </div>
                <div class="card-body">
                    <div class="float-right mb-2">
                        @can('create', App\Models\Gadget::class)
                            <a class="btn btn-outline-success" href="{{route('gadgets.create')}}">@lang('translates.buttons.create')</a>
                        @endcan
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">key</th>
                                <th scope="col">Name</th>
                                <th scope="col">Icon</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($gadgets as $gadget)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$gadget->getAttribute('key')}}</td>
                            <td>{{$gadget->getAttribute('name')}}</td>
                            <td>{{$gadget->getAttribute('icon')}}</td>
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
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now. Yo can create new gadget</div>
                                </div>
                            </th>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="float-right">
                        {{$gadgets->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

