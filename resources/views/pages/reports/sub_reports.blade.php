@extends('layouts.main')

@section('title', __('translates.navbar.report'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('reports.index')">
            @lang('translates.navbar.report')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            {{$parent->getRelationValue('chief')->getAttribute('fullname_with_position')}}
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="row m-0">
        <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover text-capitalize">
            <thead>
            <tr>
                <th scope="col" colspan="100">Cari il</th>
            </tr>
            </thead>
            <tbody>
            <tr class="d-flex flex-wrap text-center">
                @foreach($currentMonth as $day)
                        <td class="col-2 d-flex flex-column align-items-center">
                            @php
                                $subReport = \App\Models\DailyReport::where('report_id', $parent->getAttribute('id'))->whereDate('date', $day)->first();
                                $route = is_null($subReport) ? route('reports.sub.create', $parent) . "?day={$day->format('Y-m-d')}" : route('reports.sub.show', $subReport);
                            @endphp
                            <a href="{{$route}}" class="btn mr-1
                                 @if($day->format('Y-m-d') > now()->format('Y-m-d') ||
                                     (is_null($subReport) && $parent->getAttribute('chief_id') != auth()->id())
                                 ) disabled
                                @endif
                            @if(is_null($subReport) && $day->format('Y-m-d') == now()->format('Y-m-d') && \Carbon\Carbon::now()->format('H') >= \App\Models\DailyReport::TIME_LIMIT) btn-warning
                            @elseif(is_null($subReport) && $day->format('Y-m-d') == now()->format('Y-m-d') && \Carbon\Carbon::now()->format('H') < \App\Models\DailyReport::TIME_LIMIT) btn-primary
                            @elseif(is_null($subReport) && $day > now()->format('Y-m-d')) btn-dark
                            @elseif(is_null($subReport)) btn-danger
                            @else btn-success @endif">
                                {{$day->format('M d')}}
                            </a>
                            {{$day->translatedFormat('l')}}
                        </td>
                @endforeach
            </tr>
            </tbody>
        </table>
                </div>
    </div>
@endsection