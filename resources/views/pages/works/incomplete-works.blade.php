@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <style>
        .table td, .table th{
            vertical-align: middle !important;
        }
        .hiddenRow {
            padding: 0 4px !important;
            background-color: #eeeeee;
            font-size: 13px;
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

{{--    <button class="btn btn-outline-success showFilter">--}}
{{--        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')--}}
{{--    </button>--}}

{{--    <form action="{{route('incomplete-works')}}">--}}
{{--        <div class="row d-flex justify-content-between mb-2">--}}
{{--            <div id="filterContainer" class="mb-3" @if(request()->has('datetime')) style="display:block;" @else style="display:none;" @endif>--}}
{{--                <div class="col-12">--}}
{{--                    <div class="row m-0">--}}
{{--                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">--}}
{{--                            <label for="codeFilter">Qaimə nömrəsinə görə axtarış</label>--}}
{{--                            <input type="search" id="codeFilter" name="code" value="{{$filters['code']}}"--}}
{{--                                   placeholder="E-qaimə" class="form-control">--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">--}}
{{--                            <label for="codeFilter">Sorğu nömrəsinə görə axtarış</label>--}}
{{--                            <input type="search" id="codeFilter" name="declaration_no" value="{{$filters['declaration_no']}}"--}}
{{--                                   placeholder="Sorğu nömrəsi" class="form-control">--}}
{{--                        </div>--}}

{{--                        @if(\App\Models\Work::userCanViewAll())--}}
{{--                            <div class="form-group col-12 col-md-3 my-3 pl-0">--}}
{{--                                <label class="d-block" for="departmentFilter">{{__('translates.general.department_select')}}</label>--}}
{{--                                <select id="departmentFilter" class="select2"--}}
{{--                                        name="department_id"--}}
{{--                                        data-width="fit" title="{{__('translates.filters.select')}}"--}}
{{--                                        @if(\App\Models\Work::userCannotViewAll()) disabled @endif>--}}
{{--                                    <option value="">@lang('translates.filters.select')</option>--}}
{{--                                    @foreach($departments as $department)--}}
{{--                                        <option--}}
{{--                                                @if($department->getAttribute('id') == $filters['department_id']) selected @endif--}}
{{--                                        value="{{$department->getAttribute('id')}}"--}}
{{--                                        >--}}
{{--                                            {{ucfirst($department->getAttribute('name'))}}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(\App\Models\Work::userCanViewAll() || \App\Models\Work::userCanViewDepartmentWorks())--}}
{{--                            <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                                <label class="d-block" for="userFilter">{{__('translates.general.user_select')}}</label>--}}
{{--                                <select id="userFilter" class="select2"--}}
{{--                                        name="user_id"--}}
{{--                                        data-width="fit" title="{{__('translates.filters.select')}}">--}}
{{--                                    <option value="">@lang('translates.filters.select')</option>--}}
{{--                                    @foreach($users as $user)--}}
{{--                                        <option--}}
{{--                                            @if($user->getAttribute('id') == $filters['user_id']) selected @endif--}}
{{--                                                value="{{$user->getAttribute('id')}}">--}}
{{--                                            {{$user->getAttribute('fullname_with_position')}}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="serviceFilter">{{__('translates.general.select_service')}}</label>--}}
{{--                            <select id="serviceFilter" multiple--}}
{{--                                    class="select2 js-example-theme-multiple"--}}
{{--                                    name="service_id[]"--}}
{{--                                    data-width="fit"--}}
{{--                                    title="{{__('translates.filters.select')}}">--}}
{{--                                <option value="">@lang('translates.filters.select')</option>--}}
{{--                                @foreach($services as $service)--}}
{{--                                    <option--}}
{{--                                            @if($filters['service_id'])--}}
{{--                                            @if(in_array($service->getAttribute('id'), $filters['service_id'])) selected @endif--}}
{{--                                    @endif--}}
{{--                                    value="{{$service->getAttribute('id')}}">--}}
{{--                                        {{$service->getAttribute('name')}}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="clientFilter">{{trans('translates.general.select_client')}}</label>--}}
{{--                            <select name="client_id"--}}
{{--                                    id="clientFilter"--}}
{{--                                    class="custom-select2" style="width: 100% !important;"--}}
{{--                                    data-url="{{route('clients.search')}}"--}}
{{--                            >--}}
{{--                                @if(is_numeric($filters['client_id']))--}}
{{--                                    <option value="{{$filters['client_id']}}">{{\App\Models\Client::find($filters['client_id'])->getAttribute('fullname_with_voen')}}</option>--}}
{{--                                @endif--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="asanCompanyFilter">Asan Imza @lang('translates.columns.company')</label>--}}
{{--                            <select name="asan_imza_company_id" id="asanCompanyFilter" class="select2" data-width="fit" style="width: 100% !important;">--}}
{{--                                <option value="">@lang('translates.filters.select')</option>--}}
{{--                                @foreach($companies as $company)--}}
{{--                                    <option--}}
{{--                                            @if($company->getAttribute('id') == $filters['asan_imza_company_id']) selected @endif--}}
{{--                                    value="{{$company->getAttribute('id')}}"--}}
{{--                                    >--}}
{{--                                        {{$company->getAttribute('name')}}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="asanUserFilter">Asan Imza @lang('translates.columns.user')</label>--}}
{{--                            <select name="asan_imza_id" id="asanUserFilter" class="custom-select2" style="width: 100% !important;" data-url="{{route('asanImza.user.search')}}">--}}
{{--                                @if(is_numeric($filters['asan_imza_id']))--}}
{{--                                    @php--}}
{{--                                        $asanUser = \App\Models\AsanImza::find($filters['asan_imza_id']);--}}
{{--                                    @endphp--}}
{{--                                    <option value="{{$filters['asan_imza_id']}}">--}}
{{--                                        {{$asanUser->getRelationValue('user')->getAttribute('fullname')}}--}}
{{--                                        ({{$asanUser->getRelationValue('company')->getAttribute('name')}})--}}
{{--                                    </option>--}}
{{--                                @endif--}}
{{--                            </select>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="statusFilter">{{trans('translates.general.status_choose')}}</label>--}}
{{--                            <select name="status" id="statusFilter" class="form-control" style="width: 100% !important;">--}}
{{--                                <option value="">@lang('translates.filters.select')</option>--}}
{{--                                @foreach($statuses as $status)--}}
{{--                                    <option value="{{$status}}"--}}
{{--                                            @if($status == $filters['status']) selected @endif>--}}
{{--                                        @lang('translates.work_status.' . $status)--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.created_at')}}</label>--}}
{{--                            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">--}}
{{--                            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.entry_date')}}</label>--}}
{{--                            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="injected_at" value="{{$filters['injected_at']}}">--}}
{{--                            <input type="checkbox" name="check-injected_at" id="check-injected_at" @if(request()->has('check-injected_at')) checked @endif> <label for="check-injected_at">@lang('translates.filters.filter_by')</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.date')}}</label>--}}
{{--                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="datetime" value="{{$filters['datetime']}}">--}}
{{--                            <input type="checkbox" name="check-datetime" id="check-datetime" @if(request()->has('check-datetime')) checked @endif> <label for="check-datetime">@lang('translates.filters.filter_by')</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.paid_at')}}</label>--}}
{{--                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="paid_at_date" value="{{request()->get('paid_at_date')}}">--}}
{{--                            <input type="checkbox" name="check-paid_at" id="check-paid_at" @if(request()->has('check-paid_at')) checked @endif> <label for="check-paid_at">@lang('translates.filters.filter_by')</label>--}}
{{--                            <input type="checkbox" name="check-paid_at-null" id="check-paid_at-null" @if(request()->has('check-paid_at-null')) checked @endif> <label for="check-paid_at-null">Ödəniş tarixi boş olanlar</label>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.invoiced_date')}}</label>--}}
{{--                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="invoiced_date" value="{{$filters['invoiced_date']}}">--}}
{{--                            <input type="checkbox" name="check-invoiced_date" id="check-invoiced_date" @if(request()->has('check-invoiced_date')) checked @endif> <label for="check-invoiced_date">@lang('translates.filters.filter_by')</label>--}}
{{--                        </div>--}}

{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.vat_paid_at')}}</label>--}}
{{--                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="vat_date" value="{{$filters['vat_date']}}">--}}
{{--                            <input type="checkbox" name="check-vat_paid_at" id="check-vat_paid_at" @if(request()->has('check-vat_paid_at')) checked @endif> <label for="check-vat_paid_at">@lang('translates.filters.filter_by')</label>--}}
{{--                        </div>--}}


{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="verifiedFilter">@lang('translates.columns.verified')</label>--}}
{{--                            <select name="verified_at" id="verifiedFilter" class="form-control" style="width: 100% !important;">--}}
{{--                                <option value="">@lang('translates.filters.select')</option>--}}
{{--                                @foreach($verifies as $key => $verify)--}}
{{--                                    <option--}}
{{--                                        value="{{$key}}" @if($key == $filters['verified_at']) selected @endif>{{$verify}}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="paidVerifiedFilter">@lang('translates.columns.paid')</label>--}}
{{--                            <select name="paid_at" id="paidVerifiedFilter" class="form-control" style="width: 100% !important;">--}}
{{--                                <option value="">@lang('translates.filters.select')</option>--}}
{{--                                @foreach($priceVerifies as $key => $paid)--}}
{{--                                    <option--}}
{{--                                        value="{{$key}}" @if($key == $filters['paid_at']) selected @endif>{{$paid}}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">--}}
{{--                            <label class="d-block" for="paymentMethodFilter">@lang('translates.general.payment_method')</label>--}}
{{--                            <select name="payment_method" id="paymentMethodFilter" class="form-control" style="width: 100% !important;">--}}
{{--                                <option value="">@lang('translates.filters.select')</option>--}}
{{--                                @foreach($paymentMethods as $paymentMethod)--}}
{{--                                    <option value="{{$paymentMethod}}"--}}
{{--                                            @if($paymentMethod == $filters['payment_method']) selected @endif>--}}
{{--                                        @lang('translates.payment_methods.' . $paymentMethod)--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                            <input type="checkbox" name="check-returned_at" id="check-returned_at" @if(request()->has('check-returned_at')) checked @endif> <label for="check-returned_at">Geri Qayıtmış İşləri Filterlə</label>--}}
{{--                        </div>--}}


{{--                        <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">--}}
{{--                            <div class="btn-group" role="group" aria-label="Basic example">--}}
{{--                                <button type="submit" class="btn btn-outline-primary"><i--}}
{{--                                            class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>--}}
{{--                                <a href="{{route('incomplete-works')}}" class="btn btn-outline-danger"><i--}}
{{--                                            class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-sm-3 pt-2 d-flex align-items-center">--}}
{{--                <p class="mb-0"> @lang('translates.total_items', ['count' => $works->count(), 'total' => is_numeric($filters['limit']) ? $works->total() : $works->count()])</p>--}}
{{--                <div class="input-group col-md-6">--}}
{{--                    <select name="limit" class="custom-select" id="size">--}}
{{--                        @foreach([25, 50, 100, 250, 500] as $size)--}}
{{--                            <option @if($filters['limit'] == $size) selected @endif value="{{$size}}">{{$size}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-sm-3 pt-2 d-flex align-items-center">--}}
{{--                <div class="input-group">--}}
{{--                    <div class="d-flex align-items-center">--}}
{{--                        <a class="btn btn-outline-success  mr-2" data-toggle="modal" data-target="#report-work" >@lang('translates.navbar.report')</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            @can('create', App\Models\Work::class)--}}
{{--                <div class="col-sm-6 py-3">--}}
{{--                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-work">@lang('translates.buttons.create')</a>--}}
{{--                    @if(auth()->user()->hasPermission('canRedirect-work'))--}}
{{--                        <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('works.export', [--}}
{{--                            'filters' => json_encode($filters),--}}
{{--                            'dateFilters' => json_encode($dateFilters)--}}
{{--                            ])}}">--}}
{{--                            @lang('translates.buttons.export')--}}
{{--                        </a>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            @endcan--}}

{{--        </div>--}}
{{--    </form>--}}
    @if(is_numeric($filters['limit']))
        <div class="col-12 mt-2">
            <div class="float-right">
                {{$works->appends(request()->input())->links()}}
            </div>
        </div>
    @endif

    <table class="table table-responsive-sm table-hover" id="table">
        <thead>
        <tr class="text-center">
            <th scope="col">@lang('translates.columns.department')</th>
            <th scope="col">@lang('translates.fields.user')</th>
            <th scope="col">Asan imza və Təmsilçilik Şirkət</th>
            <th scope="col">@lang('translates.navbar.service')</th>
            <th scope="col">@lang('translates.fields.clientName')</th>
            <th scope="col">Status</th>
            <th scope="col">Timer</th>
            <th scope="col">Təyinat Orqanı</th>
            <th scope="col">@lang('translates.navbar.document')</th>
            <th scope="col">@lang('translates.fields.created_at')</th>
            <th scope="col">Gb Say</th>
            <th scope="col">Kod Say</th>
            <th scope="col">Say</th>
            <th scope="col">Əməliyyatlar</th>
            <th scope="col">Rəng</th>
        </tr>
        </thead>
        <tbody>
        @forelse($works as $work)
            @php
                $injected_at = \Illuminate\Support\Carbon::parse($work->getAttribute('injected_at'));
                $now = \Illuminate\Support\Carbon::now();
            @endphp
            <tr @if($now->diffInHours($injected_at) >= 24 && $work->getAttribute('status') == $work::INJECTED) style="background: #f16b6b" @endif @if(is_null($work->getAttribute('user_id'))) style="background: #eed58f" @endif title="{{$work->getAttribute('code')}}" @if($work->getAttribute('painted') == 1 && $work->getAttribute('status') == $work::STARTED) style="background-color: #ff0000" @endif>
                <td>{{$work->getRelationValue('department')->getAttribute('short')}}</td>
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
                                    $color = 'muted';
                                    break;
                                case(2):
                                    $color = 'warning';
                                    break;
                                case(3):
                                    $color = 'info';
                                    break;
                                case(4):
                                    $color = 'primary';
                                    break;
                                case(5):
                                    $color = 'dark';
                                    break;
                                case(6):
                                    $color = 'success';
                                    break;
                                case(7):
                                    $color = 'danger';
                                    break;
                            }
                        @endphp
                    @endif
                    <span class="badge badge-{{$color}}" style="font-size: 12px">
                         {{trans('translates.work_status.' . $work->getAttribute('status'))}}
                    </span>
                </td>
                <td><div id="demo1" class="demo">00:00:00 </div> </td>
                <td>
                        @if($work->getAttribute('destination') === null)
                            Təyinat orqanı boşdur
                        @else
                            {{ trans('translates.work_destination.' . $work->getAttribute('destination')) }}
                        @endif
                    </td>
                <td style="min-width: 130px">
                @php $supportedTypes = \App\Models\Document::supportedTypeIcons() @endphp
            @foreach($work->documents as $document)
                    @php $type = $supportedTypes[$document->type] @endphp
                    @php $route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document) @endphp
                    <a href="{{$route}}" data-toggle="tooltip" title="{{$document->name}}" target="_blank" class="text-dark" style="word-break: break-word">
                                <i class="fa fa-file-{{$type['icon']}} fa-2x text-{{$type['color']}}"></i>
                            </a>
                @endforeach
                </td>
                <td title="{{optional($work->getAttribute('created_at'))->diffForHumans()}}" data-toggle="tooltip">{{$work->getAttribute('created_at')}}</td>
                <td>{{$work->getParameter($work::GB)}}</td>
                <td>{{$work->getParameter($work::CODE)}}</td>
                <td>{{$work->getParameter($work::SERVICECOUNT)}}</td>
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
                            <div>
                                @can('view', $work)
                                    <a href="{{route('works.show', $work)}}" class="dropdown-item-text text-decoration-none">
                                        <i class="fal fa-eye pr-2 text-primary"></i>@lang('translates.buttons.view')
                                    </a>
                                @endcan
                                @if(auth()->user()->hasPermission('update-work') || $work->getAttribute('creator_id') == auth()->id() || $work->getAttribute('user_id') == auth()->id() || auth()->user()->isDeveloper() )
                                    @can('update', $work)
                                        <a href="{{route('works.edit', $work)}}" class="dropdown-item-text text-decoration-none">
                                            @if($work->getAttribute('creator_id') == auth()->id() || auth()->user()->isDeveloper() || auth()->user()->hasPermission('update-work'))
                                                <i class="fal fa-pen pr-2 text-success"></i>@lang('translates.tasks.edit')
                                            @elseif($work->getAttribute('user_id') == auth()->id())
                                                <i class="fal fa-arrow-right pr-2 text-success"></i>@lang('translates.buttons.execute')
                                            @endif
                                        </a>
                                    @endcan
                                @endif
                                @if(auth()->user()->hasPermission('canVerify-work') && in_array(optional($work)->getAttribute('status') , [3,4,6]) && is_null($work->getAttribute('verified_at')))
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
                </td>
                <td>
                    <button type="button" class="colorButton btn btn-primary" data-works='@json($work)'>
                        @if($work->getAttribute('painted') == 1)
                            Rəngi sil
                        @else
                            Təcili
                        @endif
                    </button>
                    <button type="button" class="docButton btn @if($work->getAttribute('doc') == 1) btn-success @else btn-danger @endif" data-works='@json($work)'>
                        @if($work->getAttribute('doc') == 1)
                            Sənədlər var
                        @else
                            Sənəd yoxdur
                        @endif
                    </button>
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
                                            @if($dep->id == auth()->user()->getAttribute('department_id')) selected @endif>
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

@endsection
@section('scripts')
    <script>
        function getWorks() {
            let works = $(this).data('works');
        }
        $('.colorButton').on('click', function (e) {
            getWorks()
            let works = $(this).data('works');
            let column = $(this).parent().parent();
            let button = $(this)
            let paintValue = ''
            let buttonName = ''
            if (column.css('background-color') === 'rgb(255, 0, 0)') {
                paintValue = 0
                buttonName = 'Təcili'
                column.css('background-color', 'rgb(245,247,255)');
            } else {
                column.css('background-color', 'red');
                paintValue = 1
                buttonName = 'Rəngi sil'
            }
            $.ajax({
                url: '/module/works/updateColor',
                type: 'POST',
                data: {
                    id: works.id,
                    painted: paintValue
                },
                success: function (response) {
                    button.html(buttonName)
                    console.log('Painted:', response);
                },
                error: function (error) {
                    console.log('There is a problem:', error);
                }
            });
        });
        $('.docButton').on('click', function (e) {
            let button = $(this);
            let works = button.data('works');
            let docValue = works.doc === 1 ? 0 : 1;

            if (docValue === 1) {
                button.removeClass('btn-danger').addClass('btn-success');
                button.html('Sənədlər var');
            } else {
                button.removeClass('btn-success').addClass('btn-danger');
                button.html('Sənəd yoxdur');
            }

            $.ajax({
                url: '/module/works/updateDoc',
                type: 'POST',
                data: {
                    id: works.id,
                    doc: docValue
                },
                success: function (response) {
                    console.log('Doc updated:', response);
                },
                error: function (error) {
                    console.log('There is a problem:', error);
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>

    @endsection
