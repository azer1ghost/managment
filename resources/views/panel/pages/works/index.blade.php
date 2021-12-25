@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .table td, .table th{
            vertical-align: middle !important;
        }
    </style>
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

        <button class="btn btn-outline-success" onclick="showFilter()">
            <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
        </button>

    <form action="{{route('works.index')}}">
        <div class="row d-flex justify-content-between mb-2">

            <div id="showenFilter" class="mb-3" @if(request()->has('datetime')) style="display:block;" @else style="display:none;" @endif>

                <div class="col-12">
                    <div class="row m-0">

                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">
                            <label for="codeFilter">{{__('translates.filters.code')}}</label>
                            <input type="search" id="codeFilter" name="code" value="{{$filters['code']}}"
                                   placeholder="{{__('translates.placeholders.code')}}" class="form-control">
                        </div>

                        @if(\App\Models\Work::userCanViewAll())
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
                        @endif

                        @if(\App\Models\Work::userCanViewAll() || \App\Models\Work::userCanViewDepartmentWorks())
                            <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                                <label class="d-block" for="userFilter">{{__('translates.general.user_select')}}</label>
                                <select id="userFilter" class="select2"
                                        name="user_id"
                                        data-width="fit" title="{{__('translates.filters.select')}}"
                                >
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
                        @endif

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
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
                            <label class="d-block" for="asanCompanyFilter">Asan Imza @lang('translates.columns.company')</label>
                            <select name="asan_imza_company_id" id="asanCompanyFilter" class="select2" data-width="fit" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($companies as $company)
                                    <option
                                            @if($company->getAttribute('id') == $filters['asan_imza_company_id']) selected @endif
                                    value="{{$company->getAttribute('id')}}"
                                    >
                                        {{$company->getAttribute('name')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="asanUserFilter">Asan Imza @lang('translates.columns.user')</label>
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

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.created_at')}}</label>
                            <input class="form-control daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
                            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.date')}}</label>
                            <input class="form-control daterange mb-1" id="datetimeFilter" type="text" readonly name="datetime" value="{{$filters['datetime']}}">
                            <input type="checkbox" name="check-datetime" id="check-datetime" @if(request()->has('check-datetime')) checked @endif> <label for="check-datetime">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="statusFilter">{{trans('translates.general.status_choose')}}</label>
                            <select name="status" id="statusFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status}}"
                                            @if($status == $filters['status']) selected @endif>
                                        @lang('translates.work_status.' . $status)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="verifiedFilter">@lang('translates.columns.verified')</label>
                            <select name="verified_at" id="verifiedFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($verifies as $key => $verify)
                                    <option
                                            value="{{$key}}"
                                            @if($key == $filters['verified_at']) selected @endif
                                    >
                                        {{$verify}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="submit" class="btn btn-outline-primary"><i
                                            class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                                <a href="{{route('works.index')}}" class="btn btn-outline-danger"><i
                                            class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <div class="col-sm-3 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $works->count(), 'total' => is_numeric($filters['limit']) ? $works->total() : $works->count()])</p>
                <div class="input-group col-md-6">
                    <select name="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250, trans('translates.general.all')] as $size)
                            <option @if($filters['limit'] == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if(auth()->user()->works()->exists())
                <div class="col-sm-3 pt-2 d-flex align-items-center">
                    <div class="input-group">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-outline-success disabled mr-2" data-toggle="modal" data-target="#report-work" >@lang('translates.navbar.report')</a>
                            <small class="text-danger">Hal hazırda hesabatlar üzərində işlər aparıldığı üçün xidmət müvəqqəti olaraq işləmir</small>
                        </div>
                    </div>
                </div>
            @endif

            @can('create', App\Models\Work::class)
                <div class="col-sm-6 py-3">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-work">@lang('translates.buttons.create')</a>
                    @if(auth()->user()->hasPermission('canRedirect-work'))
                        <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('works.export', [
                            'filters' => json_encode($filters),
                            'dateFilters' => json_encode($dateFilters)
                            ])}}"
                        >
                            @lang('translates.buttons.export')
                        </a>
                    @endif
                </div>
            @endcan
            <div class="col-12">
                <table class="table @if($works->count()) table-responsive-md @else table-responsive-sm @endif " id="table">
                    <thead>
                    <tr class="text-center">
                        @if(auth()->user()->hasPermission('canVerify-work'))
                            <th><input type="checkbox" id="works-all"></th>
                        @endif
                        @if(auth()->user()->isDeveloper())
                            <th scope="col">#</th>
                        @endif
                        <th scope="col">@lang('translates.columns.created_by')</th>
                        @if(\App\Models\Work::userCanViewAll())
                            <th scope="col">@lang('translates.columns.department')</th>
                        @endif
                        <th scope="col">@lang('translates.fields.user')</th>
                        <th scope="col">Asan imza</th>
                        <th scope="col">@lang('translates.navbar.service')</th>
                        <th scope="col">@lang('translates.fields.clientName')</th>
                        <th scope="col">Status</th>
                        @foreach(\App\Models\Service::serviceParameters() as $param)
                            <th scope="col">{{$param['data']->getAttribute('label')}}</th>
                        @endforeach
                        <th scope="col">@lang('translates.fields.created_at')</th>
                        <th scope="col">@lang('translates.fields.date')</th>
                        <th scope="col">@lang('translates.columns.verified')</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $totals = []; // array of countable service parameters. Ex: Declaration count
                        $hasPending = false; // check if there's pending work
                    @endphp
                    @forelse($works as $work)

                        @if($work->isDone() && is_null($work->getAttribute('verified_at')))
                            @php
                                $hasPending = true;
                            @endphp
                        @endif
                        <tr @if(is_null($work->getAttribute('user_id'))) style="background: #eed58f" @endif data-toggle="tooltip" title="{{$work->getAttribute('code')}}">
                            @if($work->isDone() && is_null($work->getAttribute('verified_at')) && auth()->user()->hasPermission('canVerify-work'))
                                <td><input type="checkbox" name="works[]" value="{{$work->getAttribute('id')}}"></td>
                            @elseif(auth()->user()->hasPermission('canVerify-work'))
                                <td></td>
                            @endif
                            @if(auth()->user()->isDeveloper())
                                <th scope="row">{{$work->getAttribute('code')}}</th>
                            @endif
                            <td>{{$work->getRelationValue('creator')->getAttribute('fullname')}}</td>
                            @if(\App\Models\Work::userCanViewAll())
                                <td>{{$work->getRelationValue('department')->getAttribute('short')}}</td>
                            @endif
                            <td>
                                @if(is_numeric($work->getAttribute('user_id')))
                                    {{$work->getRelationValue('user')->getAttribute('fullname_with_position')}}
                                @else
                                    @lang('translates.navbar.general')
                                @endif
                            </td>
                            <td>{{$work->asanImza()->exists() ? $work->getRelationValue('asanImza')->getAttribute('user_with_company') : trans('translates.filters.select')}}</td>
                            <td><i class="{{$work->getRelationValue('service')->getAttribute('icon')}} pr-2" style="font-size: 20px"></i> {{$work->getRelationValue('service')->getAttribute('name')}}</td>
                            <td data-toggle="tooltip" data-placement="bottom" title="{{$work->getRelationValue('client')->getAttribute('fullname')}}" >
                                {{mb_strimwidth($work->getRelationValue('client')->getAttribute('fullname'), 0, 20, '...')}}
                            </td>
                            <td>
                                @if(is_numeric($work->getAttribute('status')))
                                    @php
                                        switch($work->getAttribute('status')){
                                            case(1):
                                                $color = 'warning';
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
                            @foreach(\App\Models\Service::serviceParameters() as $param)
                                <td>{{$work->getParameter($param['data']->getAttribute('id'))}}</td>
                                @php
                                    if($param['count']){ // check if parameter is countable
                                        $count = (int) $work->getParameter($param['data']->getAttribute('id'));
                                        if(isset($totals[$param['data']->getAttribute('id')])){
                                            $totals[$param['data']->getAttribute('id')] += $count;
                                        }else{
                                            $totals[$param['data']->getAttribute('id')] = $count;
                                        }
                                    }else{
                                        $totals[$param['data']->getAttribute('id')] = NULL;
                                    }
                                @endphp
                            @endforeach
                            <td title="{{$work->getAttribute('created_at')}}" data-toggle="tooltip">{{optional($work->getAttribute('created_at'))->diffForHumans()}}</td>
                            <td title="{{$work->getAttribute('datetime')}}" data-toggle="tooltip">{{optional($work->getAttribute('datetime'))->format('Y-m-d')}}</td>
                            <td>
                                @php
                                    $status = '';
                                    if(is_null($work->getAttribute('verified_at')) && $work->status == \App\Models\Work::DONE){
                                        $status = "<i data-toggle='tooltip' data-placement='top' title='". trans('translates.work_status.1') ."' class='fas fa-clock text-info mr-2' style='font-size: 22px'></i>";
                                    }
                                    if(!is_null($work->getAttribute('verified_at'))){
                                        $status = "<i data-toggle='tooltip' data-placement='top' title='". trans('translates.columns.verified') ."' class='fas fa-check text-success mr-2' style='font-size: 22px'></i>";
                                    }
                                    if($work->getAttribute('status') == $work::REJECTED){
                                        $status = "<i data-toggle='tooltip' data-placement='top' title='". trans('translates.columns.rejected') ."' class='fas fa-times text-danger' style='font-size: 22px'></i>";
                                    }
                                @endphp
                                {!! $status !!}
                            </td>
                            <td>
                                <div class="btn-sm-group d-flex align-items-center">
                                    @if($work->getAttribute('creator_id') != auth()->id() && is_null($work->getAttribute('user_id')) && !auth()->user()->isDeveloper())
                                        @can('update', $work)
                                            <a title="@lang('translates.buttons.execute')" data-toggle="tooltip" href="{{route('works.edit', $work)}}"
                                               class="btn btn-sm btn-outline-success">
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
                                                    <i class="fal fa-eye pr-2 text-primary"></i>@lang('translates.buttons.view')
                                                </a>
                                            @endcan
                                            @if($work->getAttribute('creator_id') == auth()->id() || $work->getAttribute('user_id') == auth()->id() || auth()->user()->isDeveloper())
                                                @can('update', $work)
                                                    <a href="{{route('works.edit', $work)}}" class="dropdown-item-text text-decoration-none">
                                                        @if($work->getAttribute('creator_id') == auth()->id() || auth()->user()->isDeveloper())
                                                            <i class="fal fa-pen pr-2 text-success"></i>@lang('translates.tasks.edit')
                                                        @elseif($work->getAttribute('user_id') == auth()->id())
                                                            <i class="fal fa-arrow-right pr-2 text-success"></i>@lang('translates.buttons.execute')
                                                        @endif
                                                    </a>
                                                @endcan
                                            @endif
                                            @if(auth()->user()->hasPermission('canVerify-work') && $work->getAttribute('status') == $work::DONE && is_null($work->getAttribute('verified_at')))
                                                <a href="{{route('works.verify', $work)}}" verify data-name="{{$work->getAttribute('code')}}" class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-check pr-2 text-success"></i>@lang('translates.buttons.verify')
                                                </a>
                                            @endif
                                            @can('delete', $work)
                                                <a href="{{route('works.destroy', $work)}}" delete data-name="{{$work->getAttribute('code')}}" class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-trash pr-2 text-danger"></i>@lang('translates.tasks.delete')
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
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
                    @if($works->isNotEmpty())
                        <tr style="background: #b3b7bb" id="count">
                            <td colspan="@if(auth()->user()->isDeveloper()) 9 @elseif(auth()->user()->hasPermission('viewAll-work') || auth()->user()->hasPermission('canVerify-work')) 7 @else 6 @endif">
                                <p style="font-size: 16px" class="mb-0"><strong>@lang('translates.total'):</strong></p>
                            </td>
                            <!-- loop of totals of countable parameters -->
                            @foreach($totals as $total)
                                <td><p style="font-size: 16px" class="mb-0"><strong>{{$total}}</strong></p></td>
                            @endforeach
                            <td colspan="4"></td>
                        </tr>
                       @endif
                    </tbody>
                </table>
            </div>
            @if(is_numeric($filters['limit']))
                <div class="col-12">
                    <div class="float-right">
                        {{$works->appends(request()->input())->links()}}
                    </div>
                </div>
            @endif
        </div>
    </form>

    @if($hasPending && auth()->user()->hasPermission('canVerify-work'))
        <div class="col-12 pl-0">
            <a href="{{route('works.sum.verify')}}" id="sum-verify" class="btn btn-outline-primary">@lang('translates.sum') @lang('translates.buttons.verify')</a>
        </div>
    @endif

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
                            <label for="data-department">@lang('translates.navbar.department')</label>
                            <select class="select2" id="data-department" name="department_id" required style="width: 100% !important;">
                                <option value="">@lang('translates.general.department_select')</option>
                                @foreach($allDepartments as $dep)
                                    <option
                                            value="{{$dep->id}}"
                                            @if($dep->id == auth()->user()->getAttribute('department_id')) selected @endif
                                    >
                                        {{$dep->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
    <div class="modal fade" id="report-work">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('works.report')}}" method="GET" target="_blank">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('translates.general.select_date')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="choose-date">@lang('translates.fields.date')</label>
                            <input class="form-control daterange" id="choose-date" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('translates.buttons.show')</button>
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
        @if($works->isNotEmpty())
            const count  = document.getElementById("count").cloneNode(true);
            $("#table > tbody").prepend(count);
        @endif

        function showFilter() {
            var x = document.getElementById("showenFilter");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }

        const select2 = $('.select2');
        const clientFilter = $('.client-filter');
        const asanUserFilter = $('.asanUser-filter');

        $('select[name="limit"]').change(function () {
            $(this).form().submit();
        });

        $('.filterSelector').selectpicker()

        select2.select2({
            theme: 'bootstrap4',
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

        select2RequestFilter(clientFilter, '{{route('clients.search')}}');
        select2RequestFilter(asanUserFilter, '{{route('asanImza.user.search')}}');

        confirmJs($("a[verify]"));
        confirmJs($("#sum-verify"));

        const worksCheckbox = $("input[name='works[]']");

        $('#works-all').change(function () {
            if ($(this).is(':checked')) {
                worksCheckbox.map(function () {
                    $(this).prop('checked', true)
                });
                $('#sum-verify').removeClass('disabled');
            } else {
                worksCheckbox.map(function () {
                    $(this).prop('checked', false)
                });
                $('#sum-verify').addClass('disabled');
            }
        });

        // Check if at least one inquiry selected
        worksCheckbox.change(function () {
            checkUnverifiedWorks();
        });

        checkUnverifiedWorks();

        function checkUnverifiedWorks(){
            let hasOneChecked = false;
            worksCheckbox.map(function () {
                if ($(this).is(':checked')) {
                    hasOneChecked = true;
                }
            });
            if (hasOneChecked) {
                $('#sum-verify').removeClass('disabled');
            } else {
                $('#sum-verify').addClass('disabled');
            }
        }

        function confirmJs(el){
            el.click(function(e){
                const name = $(this).data('name') ?? 'Pending records'
                const url = $(this).attr('href')
                const checkedWorks = [];

                $("input[name='works[]']:checked").each(function(){
                    checkedWorks.push($(this).val());
                });

                e.preventDefault()

                $.confirm({
                    title: 'Confirm verification',
                    content: `Are you sure to verify <b>${name}</b> ?`,
                    autoClose: 'confirm|8000',
                    icon: 'fa fa-question',
                    type: 'blue',
                    theme: 'modern',
                    typeAnimated: true,
                    buttons: {
                        confirm: function () {
                            $.ajax({
                                url: url,
                                type: 'PUT',
                                data: {'works': checkedWorks},
                                success: function (responseObject, textStatus, xhr)
                                {
                                    $.confirm({
                                        title: 'Verification successful',
                                        icon: 'fa fa-check',
                                        content: '<b>:name</b>'.replace(':name',  name),
                                        type: 'blue',
                                        typeAnimated: true,
                                        autoClose: 'reload|3000',
                                        theme: 'modern',
                                        buttons: {
                                            reload: {
                                                text: 'Ok',
                                                btnClass: 'btn-blue',
                                                keys: ['enter'],
                                                action: function(){
                                                    window.location.reload()
                                                }
                                            }
                                        }
                                    });
                                },
                                error: function (err)
                                {
                                    console.log(err);
                                    $.confirm({
                                        title: 'Ops something went wrong!',
                                        content: err?.responseJSON,
                                        type: 'red',
                                        typeAnimated: true,
                                        buttons: {
                                            close: {
                                                text: 'Close',
                                                btnClass: 'btn-blue',
                                                keys: ['enter'],
                                            }
                                        }
                                    });
                                }
                            });
                        },
                        cancel: function () {
                        },
                    }
                });
            });
        }

        function select2RequestFilter(el, url){
            el.select2({
                placeholder: "Search",
                minimumInputLength: 3,
                // width: 'resolve',
                theme: 'bootstrap4',
                focus: true,
                ajax: {
                    delay: 500,
                    url: url,
                    dataType: 'json',
                    type: 'GET',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    }
                }
            })

            el.on('select2:open', function (e) {
                document.querySelector('.select2-search__field').focus();
            });
        }
    </script>
@endsection