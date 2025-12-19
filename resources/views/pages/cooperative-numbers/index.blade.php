@extends('layouts.main')

@section('title', trans('translates.fields.cooperative_numbers'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.fields.cooperative_numbers')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('cooperative-numbers')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.buttons.search')" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('cooperative-numbers')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.fields.phone_coop')</th>
                        <th scope="col">@lang('translates.fields.email_coop')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($cooperativeNumbers as $cooperativeNumber)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$cooperativeNumber->getAttribute('fullname_with_position')}}</td>
                            <td>{{$cooperativeNumber->getAttribute('phone_coop')}}</td>
                            <td>{{$cooperativeNumber->getAttribute('email_coop')}}</td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="3">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </form>
@endsection