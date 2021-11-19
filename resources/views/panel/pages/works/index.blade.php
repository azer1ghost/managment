@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.work')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('works.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-12">
                <div class="row m-0">
                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                        <label class="d-block" for="departmentFilter">{{__('translates.general.department_select')}}</label>
                        <select id="departmentFilter" class="select2"
                                name="department_id"
                                data-width="fit" title="{{__('translates.filters.select')}}">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($departments as $department)
                                <option
                                        @if($department->getAttribute('id') == request()->get('department_id')) selected @endif
                                value="{{$department->getAttribute('id')}}"
                                >
                                    {{ucfirst($department->getAttribute('name'))}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3 mt-3 mb-3">
                        <label class="d-block" for="userFilter">{{__('translates.general.user_select')}}</label>
                        <select id="userFilter" class="select2"
                                name="user_id"
                                data-width="fit" title="{{__('translates.filters.select')}}">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($users as $user)
                                <option
                                        @if($user->getAttribute('id') == request()->get('user_id')) selected @endif
                                value="{{$user->getAttribute('id')}}"
                                >
                                    {{$user->getAttribute('fullname_with_position')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3 mt-3 mb-3">
                        <label class="d-block" for="serviceFilter">{{__('translates.general.select_service')}}</label>
                        <select id="serviceFilter" class="select2"
                                name="service_id"
                                data-width="fit" title="{{__('translates.filters.select')}}">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($services as $service)
                                <option
                                        @if($service->getAttribute('id') == request()->get('service_id')) selected @endif
                                value="{{$service->getAttribute('id')}}"
                                >
                                    {{$service->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pr-0">
                        <label class="d-block" for="clientFilter">{{trans('translates.general.select_client')}}</label>
                        <select name="client_id" id="clientFilter" class="client-filter" style="width: 100% !important;">
                            @if(is_numeric(request()->get('client_id')))
                                <option value="{{request()->get('client_id')}}">{{\App\Models\Client::find(request()->get('client_id'))->getAttribute('fullname_with_voen')}}</option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3 mb-3 d-flex align-items-center justify-content-end">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-outline-primary"><i
                                class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                    <a href="{{route('works.index')}}" class="btn btn-outline-danger"><i
                                class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                </div>
            </div>
            @can('create', App\Models\Work::class)
                <div class="col-12">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-work">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.fields.user')</th>
                        <th scope="col">@lang('translates.navbar.service')</th>
                        <th scope="col">@lang('translates.fields.clientName')</th>
                        <th scope="col">@lang('translates.general.hard_level')</th>
                        <th scope="col">@lang('translates.general.earning')</th>
                        <th scope="col">Status</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($works as $work)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$work->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>
                                @if(is_numeric($work->getAttribute('user_id')))
                                    {{$work->getRelationValue('user')->getAttribute('fullname_with_position')}}
                                @else
                                    @lang('translates.navbar.general')
                                @endif
                            </td>
                            <td><i class="{{$work->getRelationValue('service')->getAttribute('icon')}} pr-2" style="font-size: 20px"></i> {{$work->getRelationValue('service')->getAttribute('name')}}</td>
                            <td>{{$work->getRelationValue('client')->getAttribute('fullname')}}</td>
                            <td>@lang('translates.hard_level.' . $work->getAttribute('hard_level'))</td>
                            <td>{{$work->getAttribute('earning') * $work->getAttribute('currency_rate')}} AZN</td>
                            <td>
                                @php
                                    $status = '';
                                    if(!is_null($work->getAttribute('verified_at'))){
                                        $status .= "<i title='". trans('translates.columns.verified') ."' class='fas fa-badge-check text-primary mr-2' style='font-size: 22px'></i>";
                                    }
                                    if(!is_null($work->getAttribute('done_at'))){
                                        $status .= "<i title='". trans('translates.fields.status.options.done') ."' class='fas fa-check-circle text-success mr-2' style='font-size: 22px'></i>";
                                    }
                                    if($work->getAttribute('status') == $work::REJECTED){
                                        $status .= "<i title='". trans('translates.columns.rejected') ."' class='fas fa-times text-danger' style='font-size: 22px'></i>";
                                    }
                                @endphp
                                {!! $status !!}
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $work)
                                        <a href="{{route('works.show', $work)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $work)
                                        <a href="{{route('works.edit', $work)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $work)
                                        <a href="{{route('works.destroy', $work)}}" delete data-name="{{$work->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="9">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$works->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" id="create-work">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('works.create')}}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('translates.general.select_service')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="data-service">@lang('translates.navbar.service')</label>
                            <select class="select2" id="data-service" name="service_id" required style="width: 100% !important;">
                                <option value="">@lang('translates.general.select_service')</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}} ({{$service->detail}})</option>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>
        const select2 = $('.select2');
        const clientFilter = $('.client-filter');

        select2.select2({
            theme: 'bootstrap4',
        });

        $('.filterSelector').selectpicker()

        clientFilter.select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: "{{route('clients.search')}}",
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })

        clientFilter.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        select2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

    </script>
@endsection