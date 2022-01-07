@extends('layouts.main')

@section('title', 'Widgets')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.certificate')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('certificates.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('certificates.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Certificate::class)
                <div class="col-4">
                    <a class="btn btn-outline-success float-right" href="{{route('certificates.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.fields.name')</th>
                        <th scope="col">@lang('translates.fields.detail')</th>
                        <th scope="col">@lang('translates.columns.organization')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($certificates as $certificate)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$certificate->getAttribute('name')}}</td>
                            <td>{{$certificate->getAttribute('detail')}}</td>
                            <td>{{ $certificate->getRelationValue('organization')->getAttribute('name') }}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $certificate)
                                        <a href="{{route('certificates.show', $certificate)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $certificate)
                                        <a href="{{route('certificates.edit', $certificate)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $certificate)
                                        <a href="{{route('certificates.destroy', $certificate)}}" delete data-name="{{$certificate->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$certificates->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection