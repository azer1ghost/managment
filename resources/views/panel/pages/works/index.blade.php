@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

                    <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">
                        <label for="codeFilter">{{__('translates.filters.code')}}</label>
                        <input type="search" id="codeFilter" name="code" value="{{$filters['code']}}"
                               placeholder="{{__('translates.placeholders.code')}}" class="form-control">
                    </div>

                    <div class="form-group col-12 col-md-3 my-3 pl-0">
                        <label class="d-block" for="departmentFilter">{{__('translates.general.department_select')}}</label>
                        <select id="departmentFilter" class="select2"
                                name="department_id"
                                data-width="fit" title="{{__('translates.filters.select')}}"
                                @if(\App\Models\Work::userCannotViewAll()) disabled @endif
                        >
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($departments as $department)
                                <option
                                        @if($department->getAttribute('id') == $filters['department_id']) selected @endif
                                value="{{$department->getAttribute('id')}}"
                                >
                                    {{ucfirst($department->getAttribute('name'))}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                        <label class="d-block" for="userFilter">{{__('translates.general.user_select')}}</label>
                        <select id="userFilter" class="select2"
                                name="user_id"
                                data-width="fit" title="{{__('translates.filters.select')}}">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($users as $user)
                                <option
                                        @if($user->getAttribute('id') == $filters['user_id']) selected @endif
                                value="{{$user->getAttribute('id')}}"
                                >
                                    {{$user->getAttribute('fullname_with_position')}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0 pr-0">
                        <label class="d-block" for="serviceFilter">{{__('translates.general.select_service')}}</label>
                        <select id="serviceFilter" class="select2"
                                name="service_id"
                                data-width="fit" title="{{__('translates.filters.select')}}">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($services as $service)
                                <option
                                        @if($service->getAttribute('id') == $filters['service_id']) selected @endif
                                value="{{$service->getAttribute('id')}}"
                                >
                                    {{$service->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                        <label class="d-block" for="clientFilter">{{trans('translates.general.select_client')}}</label>
                        <select name="client_id" id="clientFilter" class="client-filter" style="width: 100% !important;">
                            @if(is_numeric($filters['client_id']))
                                <option value="{{$filters['client_id']}}">{{\App\Models\Client::find($filters['client_id'])->getAttribute('fullname_with_voen')}}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                        <label class="d-block" for="asanUserFilter">Select Asan Imza</label>
                        <select name="asan_imza_id" id="asanUserFilter" class="asanUser-filter" style="width: 100% !important;">
                            @if(is_numeric($filters['asan_imza_id']))
                                @php
                                    $asanUser = \App\Models\AsanImza::find($filters['asan_imza_id']);
                                @endphp
                                <option value="{{$filters['asan_imza_id']}}">
                                    {{$asanUser->getRelationValue('user')->getAttribute('fullname')}}
                                    ({{$asanUser->getRelationValue('company')->getAttribute('name')}})
                                </option>
                            @endif
                        </select>
                    </div>


{{--                    <div class="form-group col-12 col-md-3 mt-3 mb-3">--}}
{{--                        <label class="d-block" for="startedAtFilter">{{trans('translates.general.started_at')}}</label>--}}
{{--                        <input class="form-control daterange mb-1" id="startedAtFilter" type="text" name="started_at" value="{{$filters['started_at']}}">--}}
{{--                        <input type="checkbox" name="check-started_at" id="check-started_at" @if(request()->has('check-started_at')) checked @endif> <label for="check-started_at">Filter by</label>--}}
{{--                    </div>--}}

                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                        <label class="d-block" for="doneAtFilter">{{trans('translates.general.done_at')}}</label>
                        <input class="form-control daterange mb-1" id="doneAtFilter" type="text" name="done_at" value="{{$filters['done_at']}}">
                        <input type="checkbox" name="check-done_at" id="check-done_at" @if(request()->has('check-done_at')) checked @endif> <label for="check-done_at">Filter by</label>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0 pr-0">
                        <label class="d-block" for="statusFilter">{{trans('translates.general.status_choose')}}</label>
                        <select name="status" id="statusFilter" class="form-control" style="width: 100% !important;">
                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($statuses as $status)
                                <option
                                        value="{{$status}}"
                                        @if($status == $filters['status']) selected @endif
                                >
                                    @lang('translates.work_status.' . $status)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 pl-0">
                        <label class="d-block" for="verifiedFilter">Verified</label>
                        <select name="verified" id="verifiedFilter" class="form-control" style="width: 100% !important;">
                            <option value="">Not selected</option>
                            @foreach($verifies as $key => $verify)
                                <option
                                        value="{{$key}}"
                                        @if($key == $filters['verified']) selected @endif
                                >
                                    {{$verify}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>
            <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-outline-primary"><i
                                class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                    <a href="{{route('works.index')}}" class="btn btn-outline-danger"><i
                                class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                </div>
            </div>
            @can('create', App\Models\Work::class)
                <div class="col-12 py-3">
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
                        <th scope="col">Status</th>
                        <th scope="col">@lang('translates.general.earning')</th>
{{--                        <th scope="col">@lang('translates.general.started_at')</th>--}}
                        <th scope="col">@lang('translates.columns.created_at')</th>
                        <th scope="col">@lang('translates.general.done_at')</th>
{{--                        <th scope="col">@lang('translates.general.verified_at')</th>--}}
                        <th scope="col">Verified</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($works as $work)
                        <tr @if(is_null($work->getAttribute('user_id'))) style="background: #eed58f" @endif>
                            <th scope="row">{{$work->getAttribute('code')}}</th>
                            <td>{{$work->getRelationValue('department')->getAttribute('short')}}</td>
                            <td>
                                @if(is_numeric($work->getAttribute('user_id')))
                                    {{$work->getRelationValue('user')->getAttribute('fullname_with_position')}}
                                @else
                                    @lang('translates.navbar.general')
                                @endif
                            </td>
                            <td><i class="{{$work->getRelationValue('service')->getAttribute('icon')}} pr-2" style="font-size: 20px"></i> {{$work->getRelationValue('service')->getAttribute('name')}}</td>
                            <td>{{$work->getRelationValue('client')->getAttribute('fullname')}}</td>
                            <td>{{$work->getAttribute('hard_level') ? trans('translates.hard_level.' . $work->getAttribute('hard_level')) : '' }}</td>
                            <td>
                                @if(is_numeric($work->getAttribute('status')))
                                    @php
                                        switch($work->getAttribute('status')){
                                            case(1):
                                                $color = 'info';
                                                break;
                                            case(2):
                                                $color = 'primary';
                                                break;
                                            case(3):
                                                $color = 'success';
                                                break;
                                            case(4):
                                                $color = 'danger';
                                                break;
                                        }
                                    @endphp
                                @endif
                                <span class="badge badge-{{$color}}" style="font-size: 12px">
                                    {{trans('translates.work_status.' . $work->getAttribute('status'))}}
                                </span>
                            </td>
                            <td>{{$work->getAttribute('earning') * $work->getAttribute('currency_rate')}} AZN</td>
                            <td title="{{$work->getAttribute('created_at')}}" data-toggle="tooltip" data-placement="top">{{$work->getAttribute('created_at')->diffForHumans()}}</td>
                            <td>{{optional($work->getAttribute('done_at'))->format('Y-m-d H:i')}}</td>
{{--                            <td>{{$work->getAttribute('verified_at')}}</td>--}}
                            <td>
                                @php
                                    $status = '';
                                    if(is_null($work->getAttribute('verified_at')) && $work->status == \App\Models\Work::DONE){
                                        $status = "<i title='Pending' class='fas fa-clock text-info mr-2' style='font-size: 22px'></i>";
                                    }
                                    if(!is_null($work->getAttribute('verified_at'))){
                                        $status = "<i title='". trans('translates.columns.verified') ."' class='fas fa-badge-check text-primary mr-2' style='font-size: 22px'></i>";
                                    }
                                    if($work->getAttribute('status') == $work::REJECTED){
                                        $status = "<i title='". trans('translates.columns.rejected') ."' class='fas fa-times text-danger' style='font-size: 22px'></i>";
                                    }
                                @endphp
                                {!! $status !!}
                            </td>
                            <td>
                                <div class="btn-sm-group">
                                    @if($work->getAttribute('creator_id') != auth()->id() && is_null($work->getAttribute('user_id')))
                                        @can('update', $work)
                                            <a title="Icra et" data-toggle="tooltip" data-placement="top" href="{{route('works.edit', $work)}}"
                                               class="btn btn-sm btn-outline-success mr-2">
                                                <i class="fal fa-arrow-right"></i>
                                            </a>
                                        @endcan
                                    @endif
                                    <div class="dropdown">
                                        <button class="btn" type="button" id="inquiry_actions-{{$loop->iteration}}"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fal fa-ellipsis-v-alt"></i>
                                        </button>
                                        <div class="dropdown-menu custom-dropdown">
                                            @can('view', $work)
                                                <a href="{{route('works.show', $work)}}" class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-eye pr-2 text-primary"></i>Show
                                                </a>
                                            @endcan
                                            @if($work->getAttribute('creator_id') == auth()->id() || $work->getAttribute('user_id') == auth()->id())
                                                @can('update', $work)
                                                    <a href="{{route('works.edit', $work)}}" class="dropdown-item-text text-decoration-none">
                                                        @if($work->getAttribute('creator_id') == auth()->id())
                                                            <i class="fal fa-pen pr-2 text-success"></i>@lang('translates.tasks.edit')
                                                        @elseif($work->getAttribute('user_id') == auth()->id())
                                                            <i class="fal fa-arrow-right pr-2 text-success"></i>Icra et
                                                        @endif
                                                    </a>
                                                @endcan
                                            @endif
                                            @can('delete', $work)
                                                <a href="{{route('works.destroy', $work)}}" delete class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-trash pr-2 text-danger"></i>Delete
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="12">
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        const select2 = $('.select2');
        const clientFilter = $('.client-filter');
        const asanUserFilter = $('.asanUser-filter');

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

        asanUserFilter.select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: "{{route('asanImza.search')}}",
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

        asanUserFilter.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        select2.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

        $('.daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: "YYYY-MM-DD",
                },
                maxDate: new Date(),
            }, function(start, end, label) {}
        );

    </script>
@endsection