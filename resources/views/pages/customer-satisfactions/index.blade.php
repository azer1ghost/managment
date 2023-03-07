@extends('layouts.main')

@section('title', __('translates.navbar.customer-satisfaction'))
@section('style')
    <style>
        .table td, .table th {
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

        #table {
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
            @lang('translates.navbar.customer-satisfaction')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <button class="btn btn-outline-success showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>

    <div id="filterContainer" @if(request()->has('created_at')) style="display:block;" @else style="display:none;" @endif>
        <form class="row" id="inquiryForm">

            <div class="col-12">
                <div class="row m-0">

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="daterange">@lang('translates.filters.date')</label>
                        <input type="text" readonly placeholder="@lang('translates.placeholders.range')" name="created_at"
                               value="{{$created_at}}" id="daterange" class="form-control">
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="company_id">@lang('translates.columns.price_rate')</label>
                        <select name="company_id" class="custom-select" id="company_id">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($companies as $company)
                                <option @if(request()->get('company_id') == $company->id) selected
                                        @endif value="{{$company->id}}">{{$company->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-md-0">
                        <label for="clientNamePhoneFilter">@lang('translates.filters.or', ['first' => __('translates.fields.client'), 'second' => __('translates.fields.phone'), 'third' => __('translates.fields.mail')])</label>
                        <input type="search" id="clientNamePhoneFilter" name="search_client"  value="{{request()->get('search_client')}}"
                               placeholder="@lang('translates.placeholders.or', ['first' => __('translates.fields.client'), 'second' => __('translates.fields.phone'), 'third' =>  __('translates.fields.mail')])" class="form-control">
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="noteFilter">@lang('translates.fields.note')</label>
                        <input id="noteFilter" name="note" value="{{request()->get('note')}}" placeholder="@lang('translates.placeholders.note')" class="form-control"/>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
                        <label for="rate">@lang('translates.columns.rate')</label>
                        <select name="rate" class="custom-select" id="rate">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach([1, 2, 3, 4, 5] as $rate)
                                <option @if(request()->get('rate') == $rate) selected
                                        @endif value="{{$rate}}">@lang('translates.customer_satisfaction.rates.'.$rate)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
                        <label for="price_rate">@lang('translates.columns.price_rate')</label>
                        <select name="price_rate" class="custom-select" id="price_rate">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach([1, 2, 3, 4, 5] as $rate)
                                <option @if(request()->get('price_rate') == $rate) selected
                                        @endif value="{{$rate}}">@lang('translates.customer_satisfaction.rates.'.$rate)</option>
                            @endforeach
                        </select>
                    </div>



                </div>
                <div class=" col-offset-9 mt-3 float-right">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                        <a href="{{route('customer-satisfactions.index')}}" class="btn btn-outline-danger"><i class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                    </div>
                </div>
            </div>

            <div class="input-group col-4 col-md-2 mt-3">
                <select name="limit" class="custom-select" id="size">
                    @foreach([25, 50, 100, 250, trans('translates.general.all')] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>

        </form>
    </div>

    <div class="col-12">
        <a class="btn btn-outline-primary float-right m-4"
           href="{{route('satisfactions.index')}}">@lang('translates.navbar.satisfaction')</a>
    </div>

    <table class="table table-condensed-table-responsive" style="border-collapse:collapse;" id="table">
        <thead>
        <tr class="text-center">
            <th scope="col">@lang('translates.navbar.company')</th>
            <th scope="col">@lang('translates.columns.full_name')</th>
            <th scope="col">@lang('translates.columns.rate')</th>
            <th scope="col">@lang('translates.columns.price_rate')</th>
            <th scope="col">@lang('translates.fields.note')</th>
            <th scope="col">@lang('translates.fields.created_at')</th>
            <th scope="col">@lang('translates.parameters.types.operation')</th>
        </tr>
        </thead>
        <tbody>
        @forelse($customerSatisfactions as $customerSatisfaction)
            <tr data-toggle="collapse" data-target="#demo{{$customerSatisfaction->getAttribute('id')}}"
                class="accordion-toggle text-center">
                <td>{{$customerSatisfaction->getRelationValue('satisfaction')->getRelationValue('company')->getAttribute('name')}}</td>
                <td>{{$customerSatisfaction->getParameter($customerSatisfaction::FULLNAME)}}</td>
                <td>@lang('translates.customer_satisfaction.rates.'.$customerSatisfaction->getAttribute('rate'))</td>
                <td>@lang('translates.customer_satisfaction.rates.'.$customerSatisfaction->getAttribute('price_rate'))</td>
                <td>{{$customerSatisfaction->getAttribute('note')}}</td>
                <td>{{$customerSatisfaction->getAttribute('created_at')}}</td>
                <td>
                    <div class="btn-sm-group">
                        <button class="btn btn-sm btn-outline-primary mr-2 client-edit-btn" type="button"  data-toggle="modal" data-target="#inquiry-client">
                            <i class="fas fa-comment-alt-lines"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="99" class="hiddenRow">
                    <div class="accordian-body collapse" id="demo{{$customerSatisfaction->getAttribute('id')}}">
                        <table class="table">
                            <thead>
                                @php
                                    $parameters = optional(\App\Models\Satisfaction::where('url', $customerSatisfaction->getRelationValue('satisfaction')->getAttribute('url'))->first())->parameters;
                                @endphp
                                <tr>
                                    @foreach($parameters as $parameter)
                                        <td>{{$parameter->label}}</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($customerSatisfaction->parameters as $param)
                                        <td>{{$param->pivot->value}}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
            </tr>

            <div class="modal fade" id="inquiry-client">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="{{route("customer-satisfactions.update", $customerSatisfaction)}}" method="POST" id="client-form">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('translates.general.client_data')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group mb-0">
                                    <label for="data-detail">@lang('translates.fields.note')</label>
                                    <textarea class="form-control" type="text" name="detail" cols="30" rows="10">{{$customerSatisfaction->getAttribute('detail')}}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                                <button type="submit" form="client-form" class="btn btn-primary submit">@lang('translates.buttons.save')</button>
                            </div>
                        </form>
                    </div>
                </div>
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
    <div class="float-right">
        {{$customerSatisfactions->appends(request()->input())->links()}}
    </div>
@endsection



