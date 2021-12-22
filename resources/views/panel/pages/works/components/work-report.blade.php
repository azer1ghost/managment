@extends('layouts.main')

@section('title', trans('translates.navbar.work'))
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('works.index')">
            @lang('translates.navbar.work')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <div class="container mt-5">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card p-3 py-4">
                    <div class="text-center mt-3">
                        <h4 class="my-2">@lang('translates.users.titles.employee')</h4>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('translates.columns.full_name')</th>
                                    <th scope="col">@lang('translates.columns.department')</th>
                                    <th scope="col">@lang('translates.fields.position')</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$user->getFullnameAttribute()}}</td>
                                    <td>{{$user->getRelationValue('compartment')->getAttribute('name')}}</td>
                                    <td>{{$user->getRelationValue('position')->getAttribute('name')}}</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4 class="my-2">@lang('translates.columns.user_works')</h4>

                        <table class="table table-lg-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">@lang('translates.navbar.services')</th>
                                    <th scope="col">@lang('translates.columns.verified')</th>
                                    <th scope="col">@lang('translates.columns.rejected')</th>
                                    <th scope="col">@lang('translates.columns.total')</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($services as $service)
                                <tr>
                                    <th scope="row">{{$loop->iteration}}</th>
                                    <td>{{$service->getAttribute('name')}}</td>
                                    <td>{{$service->works_verified}}</td>
                                    <td>{{$service->works_rejected}}</td>
                                    <td class="font-weight-bold">{{$service->works_count}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
