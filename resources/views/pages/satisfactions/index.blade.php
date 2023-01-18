@extends('layouts.main')

@section('title', __('translates.navbar.services'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.satisfactions')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('satisfactions.index')}}">
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
            @can('create', App\Models\Satisfaction::class)
                <div class="col-3">
                    <a class="btn btn-outline-success float-right" href="{{route('satisfactions.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.url')</th>
                        <th scope="col">Status</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($satisfactions as $satisfaction)
                        <tr>
                            <th scope="row">{{$loop->iteration}}.</th>
                            <td>{{$satisfaction->getRelationValue('company')->getAttribute('name')}}</td>
                            <td><a class="text-black" href="customer-satisfactions/create?url={{$satisfaction->getAttribute('url')}}">{{$satisfaction->getAttribute('url')}}</a></td>
                            <td>@if($satisfaction->getAttribute('is_active') == 1) <span style="color: green" >@lang('translates.users.statuses.active')</span> @else <span style="color: red">@lang('translates.users.statuses.deactivate') @endif</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $satisfaction)
                                        <a href="{{route('satisfactions.show', $satisfaction)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $satisfaction)
                                        <a href="{{route('satisfactions.edit', $satisfaction)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $satisfaction)
                                        <a href="{{route('satisfactions.destroy', $satisfaction)}}" delete data-name="{{$satisfaction->getAttribute('url')}}" class="btn btn-sm btn-outline-danger" >
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