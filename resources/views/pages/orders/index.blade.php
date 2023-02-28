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
                        <th scope="col">@lang('translates.columns.result')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($orders as $order)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$order->getAttribute('code')}}</td>
                            <td>{{$order->getRelationValue('users')->getAttribute('name')}}</td>
                            <td>{{$order->getAttribute('service')}}</td>
                            <td>{{$order->getAttribute('amount')}}</td>
                            <td>@lang('translates.orders.' . $order->getAttribute('is_paid') == 1 ? 'paid' : 'unpaid')</td>
                            <td>@lang('translates.orders.statuses.' . $order->getAttribute('status'))</td>
                            <td>
                                <form action="{{route('orders.update', $order)}}" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="row mb-3">
                                        <div class="col-md-8 col-lg-9">
                                            @if($order->getAttribute('result') !== null)
                                             <a src="{{$order->getAttribute('result')}}">netice</a>
                                            @endif
                                            <div class="pt-2">
                                                <label for="avatar"><i class="fas fa-upload btn btn-success btn-sm"></i></label>
                                                <input type="file" name="result" class="d-none" id="avatar">
                                                <button type="submit">ok</button>
                                                @if($order->getAttribute('result') !== null)
                                                    <a href="{{route('orders.update', $order)}}" class="btn btn-danger btn-sm" title="Remove my profile image"><i class="fas fa-trash"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                        <a href="{{route('orders.show', $order)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                        <a href="{{route('orders.edit', $order)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                        <a href="{{route('orders.destroy', $order)}}" delete data-name="{{$order->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
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