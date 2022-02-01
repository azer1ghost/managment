@extends('layouts.main')

@section('title', __('translates.navbar.organization'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.organization')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('organizations.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('organizations.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Organization::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('organizations.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.columns.detail')</th>
                        <th scope="col">@lang('translates.columns.is_certificate')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($organizations as $organization)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$organization->getAttribute('name')}}</td>
                            <td>{{$organization->getAttribute('detail')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @if ($organization->getAttribute('is_certificate') == 1)
                                    <i class="fa fa-check-circle text-success" style="font-size: 20px"></i>
                                    @else
                                        <i class="fa fa-times-circle text-danger" style="font-size: 20px"></i>

                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $organization)
                                        <a href="{{route('organizations.show', $organization)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $organization)
                                        <a href="{{route('organizations.edit', $organization)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $organization)
                                        <a href="{{route('organizations.destroy', $organization)}}" delete data-name="{{$organization->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$organizations->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection