@extends('layouts.main')

@section('title', __('translates.navbar.intern_number'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.intern_number')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('internal-numbers.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('internal-numbers.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\InternalNumber::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('internal-numbers.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.fields.phone_coop')</th>
                        <th scope="col">@lang('translates.columns.internal_number')</th>
                        <th scope="col">@lang('translates.columns.detail')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($internalNumbers as $internalNumber)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$internalNumber->getAttribute('user_id') == null ? $internalNumber->getAttribute('name') : $internalNumber->getRelationValue('users')->getFullnameWithPositionAttribute()}}</td>
                            <td>{{$internalNumber->getRelationValue('users')->getAttribute('phone_coop')}}</td>
                            <td>{{$internalNumber->getAttribute('phone')}}</td>
                            <td>{{$internalNumber->getAttribute('detail')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $internalNumber)
                                        <a href="{{route('internal-numbers.show', $internalNumber)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $internalNumber)
                                        <a href="{{route('internal-numbers.edit', $internalNumber)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $internalNumber)
                                        <a href="{{route('internal-numbers.destroy', $internalNumber)}}" delete data-name="{{$internalNumber->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
            <div class="col-6">
                <div class="float-right">
                    {{$internalNumbers->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection