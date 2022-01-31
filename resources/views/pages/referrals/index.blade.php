@extends('layouts.main')

@section('title', __('translates.navbar.referral'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.referral')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('referrals.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-12">
                <div class="input-group col-6 mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('referrals.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <select name="limit" class="custom-select">
                        @foreach([25, 50, 100] as $size)
                            <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Key</th>
                        <th scope="col">User</th>
                        <th scope="col">Used count</th>
                        <th scope="col">Bonus (AZN)</th>
                        <th scope="col">Efficiency (%)</th>
                        <th scope="col">Total packages</th>
                        <th scope="col">Total earnings (AZN)</th>
                        <th scope="col">Bonus percentage (%)</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$referral->getAttribute('key')}}</td>
                            <td>{{optional($referral->getRelationValue('user'))->getAttribute("fullname")}}</td>
                            <td>{{$referral->getAttribute('total_users')}}</td>
                            <td>{{$referral->getAttribute('bonus')}}</td>
                            <td>{{$referral->getAttribute('efficiency')}}</td>
                            <td>{{$referral->getAttribute('total_packages')}}</td>
                            <td>{{$referral->getAttribute('total_earnings')}}</td>
                            <td>{{$referral->getAttribute('referral_bonus_percentage')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $referral)
                                        <a href="{{route('referrals.show', $referral)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $referral)
                                        <a href="{{route('referrals.edit', $referral)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @if(auth()->user()->hasPermission('manageReferral-user'))
                                        <a href="#" class="btn btn-sm btn-outline-success" onclick="event.preventDefault(); document.getElementById('referral-key').value = '{{$referral->id}}';document.getElementById('bonus-form').submit();">
                                            <i class="fal fa-gift"></i>
                                        </a>
                                    @endif
                                    @can('delete', $referral)
                                        <a href="{{route('referrals.destroy', $referral)}}" delete data-name="{{$referral->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="10">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                <div class="float-right">
                    {{$referrals->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
    <!-- Referral bonus -->
    <form id="bonus-form" action="{{route('bonuses.referral')}}" method="POST" class="d-none">
        <input type="hidden" id="referral-key" name="key">
        @csrf
    </form>
@endsection