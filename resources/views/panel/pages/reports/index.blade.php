@extends('layouts.main')

@section('title', __('translates.navbar.report'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.report')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row">
        <form class="col-12" action="{{route('reports.index')}}">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">@lang('translates.columns.user')</th>
                        <th scope="col">@lang('translates.columns.reports_by_the_week')</th>
                        <th scope="col">@lang('translates.fields.count')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <th scope="row"><img src="{{image($report->getRelationValue('chief')->getAttribute('avatar'))}}" alt="user" class="profile" /></th>
                            <td>{{$report->getRelationValue('chief')->getAttribute('fullname_with_position')}}</td>
                            <td class="d-flex">
                                @foreach(\App\Models\DailyReport::currentWeek() as $day)
                                    @php
                                        $subReport = \App\Models\DailyReport::where('report_id', $report->getAttribute('id'))->whereDate('date', $day)->first();
                                        $route = is_null($subReport) ? route('reports.sub.create', $report) . "?day={$day->format('Y-m-d')}" : route('reports.sub.show', $subReport);
                                    @endphp
                                    <a href="{{$route}}" class="btn mr-1
{{--                                        @if($day->format('Y-m-d') > now()->format('Y-m-d') ||--}}
{{--                                            (is_null($subReport) && $report->getAttribute('chief_id') != auth()->id())--}}
{{--                                        )--}}
{{--                                            disabled--}}
{{--                                        @endif--}}
                                        @if(is_null($subReport) && $day->format('Y-m-d') == now()->format('Y-m-d') && \Carbon\Carbon::now()->format('H') >= \App\Models\DailyReport::TIME_LIMIT) btn-warning
                                        @elseif(is_null($subReport) && $day->format('Y-m-d') == now()->format('Y-m-d') && \Carbon\Carbon::now()->format('H') < \App\Models\DailyReport::TIME_LIMIT) btn-primary
                                        @elseif(is_null($subReport) && $day > now()->format('Y-m-d')) btn-dark
                                        @elseif(is_null($subReport)) btn-danger
                                        @else btn-success @endif">
                                        {{$day->format('d')}}
                                    </a>
                                @endforeach
                            </td>
                            <td>{{$report->getAttribute('reports_count')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('showSubReports', $report)
                                        <a href="{{route('reports.subs.show', $report)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-file"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $report)
                                        <a href="{{route('reports.destroy', $report)}}" delete data-name="{{$report->getRelationValue('chief')->getAttribute('fullname_with_position')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="5">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
        </form>
        @can('generateReports', App\Models\Report::class)
            <div class="col-12 my-3">
                <form action="{{route('reports.generate')}}" method="POST">
                    @csrf
                    <button class="btn btn-outline-success" type="submit">@lang('translates.reports.check_new_chiefs')</button>
                </form>
            </div>
        @endcan
    </div>
@endsection