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
{{--        @can('generateSubReport', App\Models\Report::class)--}}
{{--            <div class="col-12 my-3">--}}
{{--                <a class="btn btn-outline-success float-right" href="{{route('reports.sub.create', $parent)}}">@lang('translates.buttons.create')</a>--}}
{{--            </div>--}}
{{--        @endcan--}}
        <form class="col-12" action="{{route('reports.index')}}">
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.date')</th>
                    <th scope="col">@lang('translates.columns.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($subReports as $report)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>
                            {{$report->getAttribute('date')}}
                        </td>
                        <td>
                            <div class="btn-sm-group">
                                @can('showSubReport', $report)
                                    <a href="{{route('reports.sub.show', $report)}}" class="btn btn-sm btn-outline-success">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                @endcan
                                @can('updateSubReport', $report)
                                    <a href="{{route('reports.sub.edit', $report)}}" class="btn btn-sm btn-outline-primary">
                                        <i class="fal fa-pen"></i>
                                    </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
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
            <div class="col-12">
                <div class="float-right">
                    {{$subReports->appends(request()->input())->links()}}
                </div>
            </div>
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