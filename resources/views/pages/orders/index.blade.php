@extends('layouts.main')

@section('title', __('translates.navbar.order'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.order')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('orders.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('orders.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Order::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('orders.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.code')</th>
                        <th scope="col">@lang('translates.fields.client')</th>
                        <th scope="col">@lang('translates.navbar.service')</th>
                        <th scope="col">@lang('translates.columns.amount')</th>
                        <th scope="col">@lang('translates.columns.payment')</th>
                        <th scope="col">@lang('translates.columns.status')</th>
                        <th scope="col">@lang('translates.fields.note')</th>
                        <th scope="col">@lang('translates.columns.result')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($orders as $order)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$order->getAttribute('code')}}</td>
                            <td>{{$order->getAttribute('clients')}}</td>
                            <td>{{$order->getAttribute('service')}}</td>
                            <td>{{$order->getAttribute('amount')}} AZN</td>
                            <td>@lang('translates.orders.payment.'.$order->getAttribute('is_paid'))</td>
                            <td>@lang('translates.orders.statuses.' . $order->getAttribute('status'))</td>
                            <td>{{$order->getAttribute('note')}}</td>
                            <td>
                                @if($order->getAttribute('result') !== null)
                                    <a class="text-black" href="{{route('order-result.download', $order)}}">
                                        <i style="font-size: 35px" class="fas fa-file"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    <a href="{{route('orders.show', $order)}}" class="btn btn-sm btn-outline-primary">
                                        <i class="fal fa-eye"></i>
                                    </a>
                                    <a href="{{route('orders.edit', $order)}}" class="btn btn-sm btn-outline-success">
                                        <i class="fal fa-pen"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$orders->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection