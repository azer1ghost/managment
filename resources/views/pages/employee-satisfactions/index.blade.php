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
        <div class="row d-flex justify-content-between">
            <div class="form-group col-md-4">
                <label for="type">@lang('translates.employee_satisfactions.satisfaction_types')</label>
                <select class="form-control" id="type" name="type" style="width: 100% !important;">
                    <option value="">@lang('translates.filters.select')</option>
                    @foreach($types as $type)
                        <option value="{{$type}}" @if(request()->get('type') == $type) selected @endif>@lang('translates.employee_satisfactions.types.' . $type)</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="status">@lang('translates.columns.status')</label>
                <select class="form-control" id="status" name="status" style="width: 100% !important;">
                    <option value="">@lang('translates.filters.select')</option>
                    @foreach($statuses as $status)
                        <option value="{{$status}}" @if(request()->get('status') == $status) selected @endif>@lang('translates.employee_satisfactions.statuses.' . $status)</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="limit">@lang('translates.fields.count')</label>
                <select name="limit" id="limit" class="custom-select">
                    @foreach([25, 50, 100] as $size)
                        <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mt-4 mr-3">
                @can('create', App\Models\EmployeeSatisfaction::class)
                    <a class="btn btn-outline-success" data-toggle="modal" data-target="#create-employee-satisfaction" href="{{route('employee-satisfaction.create')}}">@lang('translates.buttons.create')</a>
                @endcan
            </div>
        </div>
            <table class="table table-responsive-sm table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">@lang('translates.fields.user')</th>
                    <th scope="col">@lang('translates.columns.type')</th>
                    <th scope="col">@lang('translates.columns.status')</th>
                    <th scope="col">@lang('translates.employee_satisfactions.effectivity')</th>
                    <th scope="col">@lang('translates.parameters.types.operation')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($employee_satisfactions as $employee_satisfaction)
                    <tr>
                        <th scope="row">{{$loop->iteration}}</th>
                        <td>@if($employee_satisfaction->getAttribute('type') !== $employee_satisfaction::COMPLAINT)
                                {{$employee_satisfaction->getRelationValue('users')->getAttribute('fullname_with_position')}}
                            @else
                                Anonim
                            @endif
                        </td>
                        <td> @lang('translates.employee_satisfactions.types.' . $employee_satisfaction->getAttribute('type'))</td>
                        @php($status = $employee_satisfaction->getAttribute('status') ?? 1)
                        <td>  @lang('translates.employee_satisfactions.statuses.' . $status)</td>
                        <td>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: {{$employee_satisfaction->getAttribute('effectivity')}}%" aria-valuenow="{{$employee_satisfaction->getAttribute('effectivity')}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-sm-group">
                                @can('view', $employee_satisfaction)
                                    <a href="{{route('employee-satisfaction.show', $employee_satisfaction)}}" class="btn btn-sm btn-outline-primary"> <i class="fal fa-eye"></i></a>
                                @endcan

                                @if(auth()->user()->hasPermission('measure-employeeSatisfaction'))
                                    <a href="{{route('employee-satisfaction.edit', $employee_satisfaction)}}" class="btn btn-sm btn-outline-success"> <i class="fal fa-pen"></i></a>
                                @endif

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
                        <div class="alert alert-success">"Qarşılaşdığınız Çətinlik" anonim şəkildə olur. Adınız heç bir yerdə qeyd olunmur!</div>
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
