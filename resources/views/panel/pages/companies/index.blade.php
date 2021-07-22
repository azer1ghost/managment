@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-2">
            <x-sidebar></x-sidebar>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    @lang('companies')
                </div>
                <div class="card-body">
                    <div class="float-right mb-2">
                        <a class="btn btn-outline-success" href="{{route('companies.create')}}" >@lang('btn.create')</a>
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
                                <td><img width="150px" src="{{asset("images/$company->logo")}}"></td>
                                <td>{{$company->name}}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{route('companies.edit', $company)}}" class="btn btn-outline-primary">
                                            <i class="fas fa-pen"></i>
                                        </a>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
