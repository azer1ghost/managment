@extends('layouts.main')

@section('title', __('translates.navbar.customer_company'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.customer_company')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <div class="float-right mb-2">
        @can('create', App\Models\CustomerCompany::class)
            <a class="btn btn-outline-success" href="{{route('customer-companies.create')}}">@lang('translates.buttons.create')</a>
        @endcan
    </div>
    <table class="table table-responsive-sm table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">@lang('translates.fields.company')</th>
                <th scope="col">@lang('translates.fields.mail')</th>
                <th scope="col">VOEN/GOOEN</th>
                <th scope="col">@lang('translates.fields.actions')</th>
            </tr>
        </thead>
        <tbody>
        @forelse($customerCompanies as $customerCompany)
        <tr>
            <th scope="row">{{$loop->iteration}}</th>
            <td>{{$customerCompany->getAttribute('name')}}</td>
            <td>{{$customerCompany->getAttribute('email')}}</td>
            <td>{{$customerCompany->getAttribute('voen')}}</td>
            <td>
                <div class="btn-sm-group">
                    @can('view', $customerCompany)
                        <a href="{{route('customer-companies.show', $customerCompany)}}" class="btn btn-sm btn-outline-primary">
                            <i class="fal fa-eye"></i>
                        </a>
                    @endcan
                    @can('update', $customerCompany)
                        <a href="{{route('customer-companies.edit', $customerCompany)}}" class="btn btn-sm btn-outline-success">
                            <i class="fal fa-pen"></i>
                        </a>
                    @endcan
                    @can('delete', $customerCompany)
                        <a href="{{route('customer-companies.destroy', $customerCompany)}}" delete data-name="{{$customerCompany->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                            <i class="fal fa-trash"></i>
                        </a>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <th colspan="5">
                <div class="row justify-content-center m-3">
                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now. Yo can create new company</div>
                </div>
            </th>
        </tr>
        @endforelse
        </tbody>
    </table>
    <div class="float-right">
        {{$customerCompanies->appends(request()->input())->links()}}
    </div>
@endsection

