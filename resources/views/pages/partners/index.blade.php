@extends('layouts.main')

@section('title', trans('translates.navbar.partners'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.partners')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('partners.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('sales-clients.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-8 col-md-3  mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            @can('create', App\Models\Partner::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('partners.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.columns.phone')</th>
                        <th scope="col">@lang('translates.fields.note')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($partners as $partner)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$partner->getAttribute('name')}}</td>
                            <td>{{$partner->getAttribute('phone')}}</td>
                            <td>{{$partner->getAttribute('note')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $partner)
                                        <a href="{{route('partners.show', $partner)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $partner)
                                        <a href="{{route('partners.edit', $partner)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $partner)
                                        <a href="{{route('partners.destroy', $partner)}}" delete data-name="{{$partner->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$partners->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection