@extends('layouts.main')

@section('title', __('translates.navbar.transaction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.transaction')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('transactions.index')}}" class="row col-12">
        <div class="col-md-3">
            <div class="input-group mb-3">

                <input type="search" name="search" value="{{$filters['search']}}" class="form-control"
                       placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.note')])"
                       aria-label="Transaction Note">

                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                    <a class="btn btn-outline-danger d-flex align-items-center"
                       href="{{route('transactions.index')}}"><i
                                class="fal fa-times"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select id="data-company" name="company" class="form-control"
                        data-selected-text-format="count"
                        data-width="fit" title="@lang('translates.clients.selectCompany')">
                    <option value=""> @lang('translates.filters.company') </option>
                    @foreach($companies as $company)
                        <option
                                @if($filters['company'] == $company->getAttribute('id')) selected @endif
                        value="{{$company->getAttribute('id')}}">
                            {{$company->getAttribute('name')}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select id="user" name="user" class="form-control"
                        data-selected-text-format="count"
                        data-width="fit" title="@lang('translates.general.user_select')">
                    <option value=""> @lang('translates.general.user_select') </option>
                    @foreach($users as $user)
                        <option
                                @if($filters['user'] == $user->getAttribute('id')) selected @endif
                        value="{{$user->getAttribute('id')}}">
                            {{$user->getAttribute('fullname')}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group col-12 col-md-3" wire:ignore>
            <select name="status" id="data-status" class="form-control">
                <option value="">@lang('translates.general.status_choose')</option>
                @foreach($statuses as $key => $status)
                    <option
                            @if($filters['status'] == $status ) selected
                            @endif value="{{$status}}"
                    >
                        @lang('translates.transactions.statuses.' . $status)
                    </option>
                @endforeach
            </select>
        </div>
{{--        <div class="form-group col-12 col-md-3" wire:ignore>--}}
{{--            <select name="source" id="data-source" class="form-control">--}}
{{--                <option value="">@lang('translates.filters.source')</option>--}}
{{--                @foreach($sources as $key => $source)--}}
{{--                    <option--}}
{{--                            @if($filters['source'] == $source ) selected--}}
{{--                            @endif value="{{$source}}"--}}
{{--                    >--}}
{{--                        @lang('translates.transactions.statuses.' . $key)--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}
        <div class="form-group col-12 col-md-3" wire:ignore>
            <select name="method" id="data-method" class="form-control">
                <option value="">@lang('translates.general.payment_method')</option>
                @foreach($methods as $key => $method)
                    <option
                            @if($filters['method'] == $method ) selected
                            @endif value="{{$method}}"
                    >
                        @lang('translates.transactions.methods.' . $method)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12 col-md-3" wire:ignore>
            <select name="account" id="data-account" class="form-control">
                <option value="">@lang('translates.navbar.accounts')</option>
                @foreach($accounts as $key => $account)
                    <option @if($filters['account'] == $account ) selected @endif value="{{$account}}">
                        {{$account->getAttribute('name')}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12 col-md-3" wire:ignore>
            <select name="type" id="data-type" class="form-control">
                <option value="">@lang('translates.filters.type')</option>
                @foreach($types as $key => $type)
                    <option
                            @if($filters['type'] == $type ) selected
                            @endif value="{{$type}}"
                    >
                        @lang('translates.transactions.types.' . $type)
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12 col-md-3">
            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
        </div>
        <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-outline-primary"><i
                            class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                <a href="{{route('transactions.index')}}" class="btn btn-outline-danger"><i
                            class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <select name="limit" class="custom-select">
                @foreach([25, 50, 100, 250] as $size)
                    <option @if(request()->get('limit') == $size) selected
                            @endif value="{{$size}}">{{$size}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 p-0 pr-3 pb-3 mt-4">
{{--            <a class="btn btn-outline-primary float-right" href="{{route('creditors.export' , [ 'filters' => json_encode($filters),])}}">@lang('translates.buttons.export')</a>--}}
        </div>
    </form>
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.user')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.navbar.account')</th>
                        <th scope="col">@lang('translates.parameters.types.source')</th>
                        <th scope="col">@lang('translates.general.earning')</th>
                        <th scope="col">@lang('translates.columns.type')</th>
                        <th scope="col">@lang('translates.general.payment_method')</th>
                        <th scope="col">@lang('translates.columns.status')</th>
                        <th scope="col">@lang('translates.fields.note')</th>
                        <th scope="col">@lang('translates.columns.created_at')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $totalAmount = 0;
                    @endphp
                    @forelse($transactions as $transaction)
                        <tr>
                            @php
                                $typeColor = ($transaction->getAttribute('type') == 1) ? 'red' : 'green';
                            @endphp
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$transaction->getRelationValue('user')->getAttribute('fullname')}}</td>
                            <td>{{$transaction->getRelationValue('company')->getAttribute('name')}}</td>
                            <td>{{$transaction->getRelationValue('account')->getAttribute('name')}}</td>
                            <td>{{$transaction->getAttribute('source')}}</td>
                            <td class="font-weight-bold" style="color: {{$typeColor}}">{{$transaction->getAttribute('amount')}}</td>
                            <td class="font-weight-bold" style="color: {{$typeColor}}">{{trans('translates.transactions.types.'.$transaction->getAttribute('type'))}}</td>
                            <td>{{$transaction->getAttribute('method')}}</td>
                            <td>{{trans('translates.transactions.statuses.'.$transaction->getAttribute('status'))}}</td>
                            <td>{{$transaction->getAttribute('note')}}</td>
                            <td>{{$transaction->getAttribute('created_at')}}</td>
                            <td>
                                <div class="btn-sm-group">
{{--                                    @can('view', $transaction)--}}
{{--                                        <a href="{{route('transactions.show', $transaction)}}" class="btn btn-sm btn-outline-primary">--}}
{{--                                            <i class="fal fa-eye"></i>--}}
{{--                                        </a>--}}
{{--                                    @endcan--}}
{{--                                    @can('update', $transaction)--}}
{{--                                        <a href="{{route('transactions.edit', $transaction)}}" class="btn btn-sm btn-outline-success">--}}
{{--                                            <i class="fal fa-pen"></i>--}}
{{--                                        </a>--}}
{{--                                    @endcan--}}
                                    @if(auth()->user()->isDeveloper())
                                        <a href="{{route('transactions.destroy', $transaction)}}" delete data-name="{{$transaction->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @php
                            $totalAmount += $transaction->getAttribute('amount');
                        @endphp
                    @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    <div class="row">
                        <div class="col-12">
                            <p class="font-weight-bold">Toplam Ödənilən: {{$totalAmount}}</p>
                        </div>
                    </div>
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$transactions->appends(request()->input())->links()}}
                </div>
            </div>
@endsection