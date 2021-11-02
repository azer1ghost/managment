@extends('layouts.main')

@section('title', __('translates.navbar.position'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.position')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form method="GET" action="{{route('positions.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('positions.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Position::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('positions.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <div class="col-4 col-md-2 pl-0 mb-3">
                    <select name="limit" class="custom-select">
                        @foreach([25, 50, 100] as $size)
                            <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.columns.role')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($positions as $position)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$position->getAttribute('name')}}</td>
                            <td>{{$position->getRelationValue('role')->getAttribute('name')}}</td>
                            <td>{{optional($position->getRelationValue('department'))->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $position)
                                        <a href="{{route('positions.show', $position)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $position)
                                        <a href="{{route('positions.edit', $position)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $position)
                                        <a href="{{route('positions.destroy', $position)}}" delete data-name="{{$position->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12 col-md-6 row position-filters">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <select name="role" class="form-control">
                            <option value="">Roles</option>
                            @foreach ($roles as $role)
                                <option @if($role->getAttribute('id') == request()->get('role')) selected @endif value="{{$role->getAttribute('id')}}">{{$role->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <select name="department" class="form-control">
                            <option value="">Departments</option>
                            @foreach ($departments as $dep)
                                <option @if($dep->getAttribute('id') == request()->get('department')) selected @endif value="{{$dep->getAttribute('id')}}">{{$dep->getAttribute('name')}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="float-right">
                    {{$positions->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function (){
            this.form.submit();
        });
    </script>
@endsection