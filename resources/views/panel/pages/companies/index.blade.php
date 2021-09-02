@extends('layouts.main')

@section('title', __('translates.navbar.company'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar/>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    @lang('companies')
                </div>
                <div class="card-body">
                    <div class="float-right mb-2">
                        @can('create', App\Models\Company::class)
                            <a class="btn btn-outline-success" href="{{route('companies.create')}}">@lang('translates.buttons.create')</a>
                        @endcan
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Company</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td><img width="150px" src="{{asset("assets/images/{$company->getAttribute('logo')}")}}"></td>
                            <td>{{$company->getAttribute('name')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $company)
                                        <a href="{{route('companies.show', $company)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $company)
                                        <a href="{{route('companies.edit', $company)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $company)
                                        <a href="{{route('companies.destroy', $company)}}" delete data-name="{{$company->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <th colspan="4">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">Empty for now. Yo can create new company</div>
                                </div>
                            </th>
                        </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div class="float-right">
                        {{$companies->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

