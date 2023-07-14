@extends('layouts.main')

@section('title', __('translates.navbar.customer_engagement'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.customer_engagement')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('customer-engagement.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('customer-engagement.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
                <select name="user" id="userFilter" class="form-control" style="width: 100% !important;">
                    <option value="">@lang('translates.general.user_select')</option>
                    @foreach($users as $user)
                        <option value="{{$user->getAttribute('id')}}"
                                @if($user->getAttribute('id') == request()->get('user')) selected @endif>
                            {{$user->getAttribute('fullname')}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <select name="partner" id="userFilter" class="form-control" style="width: 100% !important;">
                    <option value="">@lang('translates.general.partner_select')</option>
                    @foreach($partners as $partner)
                        <option value="{{$partner->getAttribute('id')}}"
                                @if($partner->getAttribute('id') == request()->get('partner')) selected @endif>
                            {{$partner->getAttribute('name')}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <select name="executant" id="userFilter" class="form-control" style="width: 100% !important;">
                    <option value="">@lang('translates.columns.executant')</option>
                    @foreach($users as $user)
                        <option value="{{$user->getAttribute('id')}}"
                                @if($user->getAttribute('id') == request()->get('executant')) selected @endif>
                            {{$user->getAttribute('fullname')}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-4 col-md-4 mr-md-auto mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100, 250] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto mb-3">
                @can('create', App\Models\CustomerEngagement::class)
                    <a class="btn btn-outline-success" href="{{route('customer-engagement.create')}}">@lang('translates.buttons.create')</a>
                @endcan
            </div>
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.user')</th>
                    <th scope="col">@lang('translates.columns.partner')</th>
                    <th scope="col">@lang('translates.columns.executant')</th>
                    <th scope="col">@lang('translates.fields.client')</th>
                    <th scope="col">@lang('translates.fields.created_at')</th>
                    <th scope="col">@lang('translates.general.work_earning')</th>
                    <th scope="col">@lang('translates.referrals.earnings')</th>
                    <th scope="col">@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($customer_engagements as $customer_engagement)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$customer_engagement->getRelationValue('user')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$customer_engagement->getRelationValue('partner')->getAttribute('name')}}</td>
                        <td>{{$customer_engagement->getRelationValue('executants')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$customer_engagement->getRelationValue('client')->getAttribute('fullname')}}</td>
                        <td>{{$customer_engagement->getAttribute('created_at')}}</td>
                        <td>{{$customer_engagement->getAttribute('amount')}}</td>
                        <td>
                            @if(in_array($customer_engagement->getRelationValue('user')->getAttribute('id'), [20, 86, 22, 154, 41]) && $customer_engagement->getRelationValue('client')->created_at > '2023-06-01 00:00:00')
                                {{$customer_engagement->getAttribute('amount')*0.15}}
                            @else
                                {{$customer_engagement->getAttribute('amount')*0.10}}
                            @endif

                        </td>
                        <td>
                            <div class="btn-sm-group">

                                @can('view', $customer_engagement)
                                    <a href="{{route('customer-engagement.show', $customer_engagement)}}" class="btn btn-sm btn-outline-primary"> <i class="fal fa-eye"></i></a>
                                @endcan

                                @can('update', $customer_engagement)
                                    <a href="{{route('customer-engagement.edit', $customer_engagement)}}" class="btn btn-sm btn-outline-success"> <i class="fal fa-pen"></i></a>
                                @endcan

                                @can('delete', $customer_engagement)
                                    <a href="{{route('customer-engagement.destroy', $customer_engagement)}}" delete data-name="{{$customer_engagement->getAttribute('name')}}" class="btn btn-sm btn-outline-danger"> <i class="fal fa-trash"></i> </a>
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
                <tfoot>
                <tr>
                    <th colspan="7"></th>
                    <th>Toplam Bonus:</th>
                    <th>
                        @if(in_array($customer_engagement->getRelationValue('user')->getAttribute('id'), [20, 86, 22, 154]) && $customer_engagement->getRelationValue('client')->created_at > '2023-06-01 00:00:00')
                            {{ $customer_engagements->sum('amount') * 0.15 }}
                        @else
                            {{ $customer_engagements->sum('amount') * 0.10 }}
                        @endif

                    </th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
            <div class="float-right">
                {{$customer_engagements->appends(request()->input())->links()}}
            </div>
        </div>
    </form>
    <form action="{{ route('calculate-amounts') }}" method="POST">
        @csrf
        @foreach($customer_engagements as $customer_engagement)
            <input type="hidden" name="customer_engagements[]" value="{{ $customer_engagement->id }}">
        @endforeach
        <button type="submit" class="btn btn-sm btn-outline-secondary">Calculate Amounts</button>
    </form>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>
@endsection
