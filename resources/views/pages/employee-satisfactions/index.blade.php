@extends('layouts.main')

@section('title', __('translates.navbar.employee_satisfaction'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.employee_satisfaction')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('employee-satisfaction.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.name')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('employee-satisfaction.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-4">
{{--                <select name="user" id="userFilter" class="form-control" style="width: 100% !important;">--}}
{{--                    <option value="">@lang('translates.general.user_select')</option>--}}
{{--                    @foreach($users as $user)--}}
{{--                        <option value="{{$user->getAttribute('id')}}"--}}
{{--                                @if($user->getAttribute('id') == request()->get('user')) selected @endif>--}}
{{--                            {{$user->getAttribute('fullname')}}--}}
{{--                        </option>--}}
{{--                    @endforeach--}}
{{--                </select>--}}
            </div>
            <div class="col-4 col-md-4 mr-md-auto mb-3">
                <select name="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto mb-3">
                @can('create', App\Models\EmployeeSatisfaction::class)
                    <a class="btn btn-outline-success" data-toggle="modal" data-target="#create-employee-satisfaction" href="{{route('employee-satisfaction.create')}}">@lang('translates.buttons.create')</a>
                @endcan
            </div>
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.user')</th>
                    <th scope="col">@lang('translates.columns.partner')</th>
                    <th scope="col">@lang('translates.fields.client')</th>
                    <th scope="col">@lang('translates.general.work_earning')</th>
                    <th scope="col">@lang('translates.referrals.earnings')</th>
                    <th scope="col">@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($employee_satisfactions as $employee_satisfaction)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>{{$employee_satisfaction->getRelationValue('user')->getFullnameWithPositionAttribute()}}</td>
                        <td>{{$employee_satisfaction->getRelationValue('partner')->getAttribute('name')}}</td>
                        <td>{{$employee_satisfaction->getRelationValue('client')->getAttribute('fullname')}}</td>
                        <td>{{$employee_satisfaction->getAttribute('amount')}}</td>
                        <td>{{$employee_satisfaction->getAttribute('amount')*0.10}}</td>
                        <td>
                            <div class="btn-sm-group">
                                @can('view', $employee_satisfaction)
                                    <a href="{{route('employee-satisfaction.show', $employee_satisfaction)}}" class="btn btn-sm btn-outline-primary"> <i class="fal fa-eye"></i></a>
                                @endcan

                                @can('update', $employee_satisfaction)
                                    <a href="{{route('employee-satisfaction.edit', $employee_satisfaction)}}" class="btn btn-sm btn-outline-success"> <i class="fal fa-pen"></i></a>
                                @endcan

                                @can('delete', $employee_satisfaction)
                                    <a href="{{route('employee-satisfaction.destroy', $employee_satisfaction)}}" delete data-name="{{$employee_satisfaction->getAttribute('name')}}" class="btn btn-sm btn-outline-danger"> <i class="fal fa-trash"></i> </a>
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
                {{$employee_satisfactions->appends(request()->input())->links()}}
            </div>
        </div>
    </form>
    <div class="modal fade" id="create-employee-satisfaction">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('employee-satisfaction.create')}}">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('translates.columns.class') @lang('translates.placeholders.choose')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="type">@lang('translates.employee_satisfactions.satisfaction_types')</label>
                            <select class="form-control" id="type" name="type" required style="width: 100% !important;">
                                @foreach($types as $type)
                                    <option value="{{$type}}">@lang('translates.employee_satisfactions.types.' . $type)</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('translates.buttons.create')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $('select').change(function () {
            this.form.submit();
        });
    </script>
@endsection
