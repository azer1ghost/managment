@extends('layouts.main')

@section('title', __('translates.navbar.meeting'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.meeting')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('meetings.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('meetings.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Meeting::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('meetings.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.columns.will_start_at')</th>
                        <th scope="col">@lang('translates.columns.will_end_at')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($meetings as $meeting)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$meeting->getAttribute('name')}}</td>
                            <td>{{$meeting->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>{{$meeting->getAttribute('will_start_at')}}</td>
                            <td>{{$meeting->getAttribute('will_end_at')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $meeting)
                                        <a href="{{route('meetings.show', $meeting)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $meeting)
                                        <a href="{{route('meetings.edit', $meeting)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $meeting)
                                        <a href="{{route('meetings.destroy', $meeting)}}" delete data-name="{{$meeting->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$meetings->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection