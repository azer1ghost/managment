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
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('referrals.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Referral::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" href="{{route('referrals.create')}}">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Key</th>
                        <th scope="col">User</th>
                        <th scope="col">Used count</th>
                        <th scope="col">Bonus</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$referral->getAttribute('key')}}</td>
                            <td>{{$referral->getRelationValue('user')->getAttribute("fullname")}}</td>
                            <td>{{$referral->getAttribute('total')}}</td>
                            <td>{{$referral->getAttribute('bonus')}}</td>
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
                            <th colspan="6">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$referrals->links()}}
                </div>
            </div>
        </div>
    </form>
@endsection