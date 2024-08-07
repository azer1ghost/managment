@extends('layouts.main')

@section('title', __('translates.navbar.logistics_clients'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.logistics_clients')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('logistic-clients.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.clientName')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('logistic-clients.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-8 col-md-3  mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 mb-3">
                @can('create', App\Models\LogisticsClient::class)
                    <a class="btn btn-outline-success float-right " href="{{route('logistic-clients.create')}}">@lang('translates.buttons.create')</a>
                @endcan
            </div>
{{--            <div class="col-8 col-md-3 mb-3 ">--}}
{{--                <select name="user" class="custom-select" id="type">--}}
{{--                    <option selected value="">@lang('translates.navbar.user')</option>--}}
{{--                    @foreach($users as $user)--}}
{{--                        <option @if(request()->get('user') == $user->getAttribute('id')) selected @endif value="{{$user->getAttribute('id')}}">{{$user->getAttribute('fullname')}}</option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
{{--            </div>--}}

            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.clientName')</th>
                    <th scope="col">@lang('translates.fields.phone')</th>
                    <th scope="col">@lang('translates.columns.email')</th>
                    <th scope="col">VOEN</th>
                    <th scope="col">@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($logisticsClients as $logisticClient)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$logisticClient->getAttribute('name')}}</td>
                        <td>{{$logisticClient->getAttribute('phone')}}</td>
                        <td>{{$logisticClient->getAttribute('email') ? $logisticClient->getAttribute('email') : trans('translates.clients.email_empty')}} </td>
                        <td>{{$logisticClient->getAttribute('voen')}}</td>

                        <td>
                            <div class="btn-sm-group">
                                @can('view', $logisticClient)
                                    <a href="{{route('logistic-clients.show', $logisticClient)}}" class="btn btn-sm btn-outline-primary"> <i class="fal fa-eye"></i></a>
                                @endcan

                                @can('update', $logisticClient)
                                    <a href="{{route('logistic-clients.edit', $logisticClient)}}" class="btn btn-sm btn-outline-success"> <i class="fal fa-pen"></i></a>
                                @endcan

                                @can('delete', $logisticClient)
                                    <a href="{{route('logistic-clients.destroy', $logisticClient)}}" delete data-name="{{$logisticClient->getAttribute('name')}}" class="btn btn-sm btn-outline-danger"> <i class="fal fa-trash"></i> </a>
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
            <div class="float-right">
                {{$logisticsClients->appends(request()->input())->links()}}
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>
@endsection
