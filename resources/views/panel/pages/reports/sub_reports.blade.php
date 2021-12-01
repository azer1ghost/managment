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
    <div class="row">
        <form class="col-12" action="{{route('reports.index')}}">
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">@lang('translates.fields.date')</th>
                    <th></th>
                    <th scope="col">Report</th>
                </tr>
                </thead>
                <tbody>
                @forelse($data as $date => $subReports)
                    @php($date = \Carbon\Carbon::parse($date))
                    <tr>
                        <td colspan="2"><p class="mb-0"><strong>{{$date->year}} {{$date->monthName}}</strong></p></td>
                    </tr>
                    @foreach($subReports as $report)
                        <tr>
                            <th scope="row"></th>
                            <th scope="row"></th>
                            <td>
                                {{$report->getAttribute('date')}}
                                @can('showSubReport', $report)
                                    <a href="{{route('reports.sub.show', $report)}}" class="btn btn-sm btn-outline-success ml-2">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                @endcan
                                @can('updateSubReport', $report)
                                    <a href="{{route('reports.sub.edit', $report)}}" class="btn btn-sm btn-outline-primary">
                                        <i class="fal fa-pen"></i>
                                    </a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <th colspan="3">
                            <div class="row justify-content-center m-3">
                                <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                            </div>
                        </th>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('select[name="limit"]').change(function (){
            this.form.submit();
        });
    </script>
@endsection