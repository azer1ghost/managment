@extends('layouts.main')

@section('title', __('translates.navbar.conference'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.conference')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('conferences.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('conferences.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Conference::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('conferences.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">datetime</th>
                        <th scope="col">status</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($conferences as $conference)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$conference->getAttribute('name')}}</td>
                            <td>{{$conference->getAttribute('datetime')}}</td>
                            <td>{{__('translates.conferences')[$conference->getAttribute('status')]}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $conference)
                                        <a href="{{route('conferences.show', $conference)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $conference)
                                        <a href="{{route('conferences.edit', $conference)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $conference)
                                        <a href="{{route('conferences.destroy', $conference)}}" delete data-name="{{$conference->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$conferences->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection