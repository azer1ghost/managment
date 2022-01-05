@extends('layouts.main')

@section('title', __('translates.navbar.sales_activities_type'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.sales_activities_type')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('sales-activities-types.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-5">
                </div>
            </div>

            @can('create', App\Models\SalesActivityType::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('sales-activities-types.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan

            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">Description</th>
                        <th scope="col">Hard Columns</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sale_activities_types as $sale_activities_type)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$sale_activities_type->getAttribute('name')}}</td>
                            <td>{{$sale_activities_type->getAttribute('description')}}</td>
                            <td>{{$sale_activities_type->getAttribute('hard_columns')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $sale_activities_type)
                                        <a href="{{route('sales-activities-types.show', $sale_activities_type)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $sale_activities_type)
                                        <a href="{{route('sales-activities-types.edit', $sale_activities_type)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $sale_activities_type)
                                        <a href="{{route('sales-activities-types.destroy', $sale_activities_type)}}" delete data-name="{{$sale_activities_type->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$sale_activities_types->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection