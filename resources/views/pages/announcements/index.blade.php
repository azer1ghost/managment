@extends('layouts.main')

@section('title', __('translates.navbar.announcement'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.announcement')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('announcements.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            @can('create', App\Models\Announcement::class)
                <div class="col-12 py-3">
                    <a class="btn btn-outline-success float-right" href="{{route('announcements.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">class</th>
                        <th scope="col">title</th>
                        <th scope="col">repeat_rate</th>
                        <th scope="col">status</th>
                        <th scope="col">will_notify_at</th>
                        <th scope="col">will_end_at</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($announcements as $announcement)
                        <tr>
                            <th scope="row">{{$announcement->getAttribute('key')}}</th>
                            <td>{{$announcement->getAttribute('class')}}</td>
                            <td>{{$announcement->getAttribute('title')}}</td>
                            <td>{{$announcement->getAttribute('repeat_rate')}}</td>
                            <td>
                                {!! $announcement->getAttribute('status') ?
                                    '<i class="fas fa-check-circle text-success" style="font-size:18px"></i>':
                                    '<i class="fas fa-times-circle text-danger"  style="font-size:18px"></i>'
                                !!}
                            </td>
                            <td>{{$announcement->getAttribute('will_notify_at')}}</td>
                            <td>{{$announcement->getAttribute('will_end_at')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $announcement)
                                        <a href="{{route('announcements.show', $announcement)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $announcement)
                                        <a href="{{route('announcements.edit', $announcement)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $announcement)
                                        <a href="{{route('announcements.destroy', $announcement)}}" delete data-name="{{$announcement->getAttribute('key')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$announcements->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection