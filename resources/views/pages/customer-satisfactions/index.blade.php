@extends('layouts.main')

@section('title', __('translates.navbar.services'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.services')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('services.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('services.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Service::class)
                <div class="col-3">
                    <a class="btn btn-outline-success float-right" href="{{route('customer-satisfactions.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>

                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">Status</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($customerSatisfactions as $service)
                        <tr>
                            <th scope="row">{{$loop->iteration}}.</th>
                            <td>{{$service->getRelationValue('company')->getAttribute('name')}}</td>
                            <td>@if($service->getAttribute('is_active') == 1) <span style="color: green" >@lang('translates.users.statuses.active')</span> @else <span style="color: red">@lang('translates.users.statuses.deactivate') @endif</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $service)
                                        <a href="{{route('services.show', $service)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $service)
                                        <a href="{{route('services.edit', $service)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $service)
                                        <a href="{{route('services.destroy', $service)}}" delete data-name="{{$service->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
        </div>
    </form>
@endsection