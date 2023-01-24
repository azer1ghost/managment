@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <style>
        .table td, .table th{
            vertical-align: middle !important;
        }
        .table tr {
            cursor: pointer;
        }
        .hiddenRow {
            padding: 0 4px !important;
            background-color: #eeeeee;
            font-size: 13px;
        }
        .table{
            overflow-x: scroll;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.work')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <button class="btn btn-outline-success showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>

    <table class="table table-condensed-table-responsive" style="border-collapse:collapse;" id="table">
        <thead>
        <tr class="text-center">
            <th scope="col">@lang('translates.navbar.company')</th>
            <th scope="col">@lang('translates.columns.rate')</th>
            <th scope="col">@lang('translates.columns.price_rate')</th>
            <th scope="col">@lang('translates.fields.note')</th>

        </tr>
        </thead>
        <tbody>
        @forelse($customerSatisfactions as $customerSatisfaction)
            <tr data-toggle="collapse" data-target="#demo{{$customerSatisfaction->getAttribute('id')}}" class="accordion-toggle text-center">
                <td>{{$customerSatisfaction->getRelationValue('satisfaction')->getRelationValue('company')->getAttribute('name')}}</td>
                <td>@lang('translates.customer_satisfaction.rates.'.$customerSatisfaction->getAttribute('rate'))</td>
                <td>@lang('translates.customer_satisfaction.rates.'.$customerSatisfaction->getAttribute('price_rate'))</td>
                <td>{{$customerSatisfaction->getAttribute('note')}}</td>
            </tr>
            <tr>
                <td colspan="99" class="hiddenRow">
                    <div class="accordian-body collapse" id="demo{{$customerSatisfaction->getAttribute('id')}}">
                        <table class="table">
                            <thead>
                            @php($something = optional(\App\Models\Satisfaction::where('url', $customerSatisfaction->getRelationValue('url'))->first())->parameters)

                            <tr>
                                @foreach(\App\Models\Satisfaction::satisfactionParameters() as $param)
                                    <td>{{$param['data']->getAttribute('label')}}</td>
                                @endforeach
                            </tr>

                            </thead>
                            <tbody>
                            <tr>
                                @foreach(\App\Models\Satisfaction::satisfactionParameters() as $param)
                                    <td >{{$customerSatisfaction->getParameter($param['data']->getAttribute('id'))}}</td>
                                @endforeach
                            </tr>

                            </tbody>
                        </table>
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

@endsection

