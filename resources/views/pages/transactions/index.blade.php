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
    <form action="{{route('transactions.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <div class="alert-info alert"> Filter yaxın zamanda düzələcək</div>
{{--                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">--}}
{{--                    <div class="input-group-append">--}}
{{--                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>--}}
{{--                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('transactions.index')}}"><i class="fal fa-times"></i></a>--}}
{{--                    </div>--}}
                </div>
            </div>

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
                    @forelse($transactions as $transaction)
                        <tr>
                            @php
                                $typeColor = ($transaction->getAttribute('type') == 1) ? 'green' : 'red';
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
                                    @can('delete', $transaction)
                                        <a href="{{route('transactions.destroy', $transaction)}}" delete data-name="{{$transaction->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
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
                    {{$transactions->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection