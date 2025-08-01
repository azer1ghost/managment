@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <style>
        .table td, .table th{
            vertical-align: middle !important;
        }
        .table tr {
            cursor: pointer;
        }
        .hiddenRow {
            padding: 0 4px !important;
            background-color: #eeeeee;
            font-size: 13px;
        }
        .table{
            overflow-x: scroll;
        }
        /*div.timer {*/
        /*    border:1px #666666 solid;*/
        /*    width:190px;*/
        /*    height:50px;*/
        /*    line-height:50px;*/
        /*    font-size:36px;*/
        /*    font-family:"Courier New", Courier, monospace;*/
        /*    text-align:center;*/
        /*    margin:5px;*/
        /*}*/
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

    <button class="btn btn-outline-success showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>

    <form action="{{route('works.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div id="filterContainer" class="mb-3" @if(request()->has('datetime')) style="display:block;" @else style="display:none;" @endif>
                <div class="col-12">
                    <div class="row m-0">
                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">
                            <label for="codeFilter">Qaimə nömrəsinə görə axtarış</label>
                            <input type="search" id="codeFilter" name="code" value="{{$filters['code']}}"
                                   placeholder="E-qaimə" class="form-control">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="emptyInvoice" name="empty_invoice" value="1"
                                            {{ request('empty_invoice') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emptyInvoice">
                                        Yalnız boş qaimələr
                                    </label>
                                </div>
                        </div>
                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">
                            <label for="codeFilter">Sorğu nömrəsinə görə axtarış</label>
                            <input type="search" id="codeFilter" name="declaration_no" value="{{$filters['declaration_no']}}"
                                   placeholder="Sorğu nömrəsi" class="form-control">
                        </div>
                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">
                            <label for="codeFilter">Nəqliyyat nömrəsinə görə axtarış</label>
                            <input type="search" id="codeFilter" name="transport_no" value="{{$filters['transport_no']}}"
                                   placeholder="Nəqliyyat nömrəsi" class="form-control">
                        </div>

                        @if(\App\Models\Work::userCanViewAll())
                            <div class="form-group col-12 col-md-3 my-3 pl-0">
                                <label class="d-block" for="departmentFilter">{{__('translates.general.department_select')}}</label>
                                <select id="departmentFilter" class="select2"
                                        name="department_id"
                                        data-width="fit" title="{{__('translates.filters.select')}}"
                                        @if(\App\Models\Work::userCannotViewAll()) disabled @endif>
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

                        <form method="GET" action="{{ route('works.index') }}" class="row">
                            @if(\App\Models\Work::userCanViewAll() || \App\Models\Work::userCanViewDepartmentWorks())
                                <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                                    <label class="d-block" for="userFilter">{{ __('translates.general.user_select') }}</label>
                                    <select id="userFilter" class="select2 form-control"
                                            name="user_id"
                                            data-width="fit" title="{{ __('translates.filters.select') }}"
                                            onchange="this.form.submit()">
                                        <option value="">@lang('translates.filters.select')</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->fullname_with_position }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                                <label class="d-block" for="sorterFilter">Sorter</label>
                                <select id="sorterFilter" class="select2 form-control"
                                        name="sorter_id"
                                        data-width="fit" title="-- Seçin --"
                                        onchange="this.form.submit()">
                                    <option value="">-- Seçin --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('sorter_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} {{ $user->surname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                                <label class="d-block" for="analystFilter">Analyst</label>
                                <select id="analystFilter" class="select2 form-control"
                                        name="analyst_id"
                                        data-width="fit" title="-- Seçin --"
                                        onchange="this.form.submit()">
                                    <option value="">-- Seçin --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('analyst_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} {{ $user->surname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>


                        {{--                        <div class="col-md-4">--}}
{{--                            <div class="form-group">--}}
{{--                                <select id="data-sales-coordinator" name="coordinator"  class="form-control" data-selected-text-format="count"--}}
{{--                                        data-width="fit" title="@lang('translates.clients.selectCoordinator')">--}}
{{--                                    <option value=""> @lang('translates.filters.coordinator') </option>--}}
{{--                                    @foreach($coordinators as $coordinator)--}}
{{--                                        <option--}}
{{--                                                @if($filters['coordinator'] == $coordinator->getAttribute('id')) selected @endif--}}
{{--                                        value="{{$coordinator->getAttribute('id')}}">--}}
{{--                                            {{$coordinator->getAttribute('name')}}--}}
{{--                                        </option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}


                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="serviceFilter">{{__('translates.general.select_service')}}</label>
                            <select id="serviceFilter" multiple
                                    class="select2 js-example-theme-multiple"
                                    name="service_id[]"
                                    data-width="fit"
                                    title="{{__('translates.filters.select')}}">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($services as $service)
                                    <option
                                            @if($filters['service_id'])
                                            @if(in_array($service->getAttribute('id'), $filters['service_id'])) selected @endif
                                    @endif
                                    value="{{$service->getAttribute('id')}}">
                                        {{$service->getAttribute('name')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="clientFilter">{{trans('translates.general.select_client')}}</label>
                            <select name="client_id"
                                    id="clientFilter"
                                    class="custom-select2" style="width: 100% !important;"
                                    data-url="{{route('clients.search')}}"
                            >
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
                            <select name="asan_imza_id" id="asanUserFilter" class="custom-select2" style="width: 100% !important;" data-url="{{route('asanImza.user.search')}}">
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
                            <label class="d-block" for="destinationFilter">{{trans('translates.general.destination_choose')}}</label>
                            <select name="destination" id="destinationFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($destinations as $destination)
                                    <option value="{{$destination}}"
                                            @if($destination == $filters['destination']) selected @endif>
                                        @lang('translates.work_destination.' . $destination)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.created_at')}}</label>
                            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
                            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
                        </div>
                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.injected_at')}}</label>
                            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="injected_at" value="{{$filters['injected_at']}}">
                            <input type="checkbox" name="check-injected_at" id="check-injected_at" @if(request()->has('check-injected_at')) checked @endif> <label for="check-injected_at">@lang('translates.filters.filter_by')</label>
                        </div>
                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="entryDateFilter">{{trans('translates.fields.entry_date')}}</label>
                            <input class="form-control custom-daterange mb-1" id="entryDateFilter" type="text" readonly name="entry_date" value="{{$filters['entry_date']}}">
                            <input type="checkbox" name="check-entry_date" id="check-entry_date" @if(request()->has('check-entry_date')) checked @endif> <label for="check-entry_date">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.date')}}</label>
                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="datetime" value="{{$filters['datetime']}}">
                            <input type="checkbox" name="check-datetime" id="check-datetime" @if(request()->has('check-datetime')) checked @endif> <label for="check-datetime">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.paid_at')}}</label>
                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="paid_at_date" value="{{request()->get('paid_at_date')}}">
                            <input type="checkbox" name="check-paid_at" id="check-paid_at" @if(request()->has('check-paid_at')) checked @endif> <label for="check-paid_at">@lang('translates.filters.filter_by')</label>
                            <input type="checkbox" name="check-paid_at-null" id="check-paid_at-null" @if(request()->has('check-paid_at-null')) checked @endif> <label for="check-paid_at-null">Ödəniş tarixi boş olanlar</label>
                        </div>
                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.invoiced_date')}}</label>
                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="invoiced_date" value="{{$filters['invoiced_date']}}">
                            <input type="checkbox" name="check-invoiced_date" id="check-invoiced_date" @if(request()->has('check-invoiced_date')) checked @endif> <label for="check-invoiced_date">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.vat_paid_at')}}</label>
                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="vat_date" value="{{$filters['vat_date']}}">
                            <input type="checkbox" name="check-vat_paid_at" id="check-vat_paid_at" @if(request()->has('check-vat_paid_at')) checked @endif> <label for="check-vat_paid_at">@lang('translates.filters.filter_by')</label>
                        </div>


                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="verifiedFilter">@lang('translates.columns.verified')</label>
                            <select name="verified_at" id="verifiedFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($verifies as $key => $verify)
                                    <option
                                        value="{{$key}}" @if($key == $filters['verified_at']) selected @endif>{{$verify}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="paidVerifiedFilter">@lang('translates.columns.paid')</label>
                            <select name="paid_at" id="paidVerifiedFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($priceVerifies as $key => $paid)
                                    <option
                                        value="{{$key}}" @if($key == $filters['paid_at']) selected @endif>{{$paid}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="paymentMethodFilter">@lang('translates.general.payment_method')</label>
                            <select name="payment_method" id="paymentMethodFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($paymentMethods as $paymentMethod)
                                    <option value="{{$paymentMethod}}"
                                            @if($paymentMethod == $filters['payment_method']) selected @endif>
                                        @lang('translates.payment_methods.' . $paymentMethod)
                                    </option>
                                @endforeach
                            </select>
                            <input type="checkbox" name="check-returned_at" id="check-returned_at" @if(request()->has('check-returned_at')) checked @endif> <label for="check-returned_at">Geri Qayıtmış İşləri Filterlə</label>
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
                        @foreach([25, 50, 100, 250, 500] as $size)
                            <option @if($filters['limit'] == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-sm-3 pt-2 d-flex align-items-center">
                <div class="input-group">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('returned-works') }}" class="btn btn-outline-dark ">Geri Qayıtmış işlər</a>

                        <a class="btn btn-outline-success  mr-2" data-toggle="modal" data-target="#report-work" >@lang('translates.navbar.report')</a>
                    </div>
                </div>
            </div>

            @can('create', App\Models\Work::class)
                <div class="col-sm-6 py-3">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-work">@lang('translates.buttons.create')</a>
                    @if(auth()->user()->hasPermission('canRedirect-work'))
                        <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('works.export', [
                            'filters' => json_encode($filters),
                            'dateFilters' => json_encode($dateFilters)
                            ])}}">
                            @lang('translates.buttons.export')
                        </a>
                    @endif
                </div>
            @endcan

        </div>
    </form>
{{--    <form method="POST" action="{{ route('works.update-status') }}">--}}
{{--        @csrf--}}
{{--        <button class="btn btn-outline-primary float-right"  type="submit">Tarixi Güncəllə</button>--}}
{{--    </form>--}}


    @if(is_numeric($filters['limit']))
        <div class="col-12 mt-2">
            <div class="float-right">
                {{$works->appends(request()->input())->links()}}
            </div>
        </div>
    @endif
    <table class="table table-responsive @if($works->count()) table-responsive-md @else table-responsive-sm @endif" style="border-collapse:collapse;" id="table" >
        <thead>
        <tr class="text-center" >
            @if(auth()->user()->hasPermission('canVerify-work'))
                <th><input type="checkbox" id="works-all"></th>
            @endif

            @if(auth()->user()->hasPermission('viewPrice-work') )
                <th scope="col">@lang('translates.columns.e-receipt')</th>
            @endif

{{--            @if(\App\Models\Work::userCanViewAll())--}}
            <th scope="col">@lang('translates.general.coordinator')</th>
                {{--            <th scope="col">@lang('translates.general.operator')</th>--}}
                <th scope="col">@lang('translates.columns.created_by')</th>
                <th scope="col">@lang('translates.columns.department')</th>
                {{--            @endif--}}
                <th scope="col">@lang('translates.general.sorter')</th>
                <th scope="col">@lang('translates.general.analyst')</th>
                <th scope="col">@lang('translates.fields.user')</th>
                <th scope="col">Asan imza və Təmsilçilik Şirkət</th>
            <th scope="col">@lang('translates.navbar.service')</th>
            <th scope="col">@lang('translates.fields.clientName')</th>
            <th scope="col">@lang('translates.columns.status')</th>
{{--            <th scope="col">Timer</th>--}}
            <th scope="col">@lang('translates.general.destination')</th>
            <th scope="col">@lang('translates.navbar.document')</th>
            <th scope="col">@lang('translates.columns.gb')</th>
            <th scope="col">@lang('translates.columns.code_count')</th>
            <th scope="col">@lang('translates.columns.count_other')</th>
            @if(auth()->user()->hasPermission('viewPrice-work'))
{{--            @foreach(\App\Models\Service::serviceParameters() as $param)--}}
{{--                <th scope="col">{{$param['data']->getAttribute('label')}}</th>--}}
{{--            @endforeach--}}
            <th scope="col">@lang('translates.columns.sum_paid')</th>
            <th scope="col">@lang('translates.columns.residue')</th>
            @endif
            <th scope="col">@lang('translates.columns.verified')</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @php
            $totals = []; // array of countable service parameters. Ex: Declaration count
            $balance = [];
            $total_payment = [];
            $hasPending = false; // check if there's pending work
        @endphp
        @forelse($works as $work)

            @if(in_array(optional($work)->getAttribute('status'), [3,4,6,7]) && is_null($work->getAttribute('verified_at')))
                @php
                    $hasPending = true;
                @endphp
            @endif
            @php
                $injected_at = \Illuminate\Support\Carbon::parse($work->getAttribute('injected_at'));
                $now = \Illuminate\Support\Carbon::now();
            @endphp
            <tr data-toggle="collapse" data-target="#demo{{$work->getAttribute('id')}}" class="accordion-toggle" @if($now->diffInHours($injected_at) >= 24 && $work->getAttribute('status') == $work::INJECTED) style="background: #f16b6b" @endif @if(is_null($work->getAttribute('user_id'))) style="background: #eed58f" @endif title="{{$work->getAttribute('code')}}" @if($work->getAttribute('painted') == 1 && $work->getAttribute('status') == $work::STARTED) style="background-color: #ff0000" @endif>
                @if(in_array(optional($work)->getAttribute('status'), [3,4,6,7]) && is_null($work->getAttribute('verified_at')) && auth()->user()->hasPermission('canVerify-work'))
                    <td><input type="checkbox" name="works[]" value="{{$work->getAttribute('id')}}"></td>
                @elseif(auth()->user()->hasPermission('canVerify-work'))
                    <td></td>
                @endif
                @if(auth()->user()->hasPermission('viewPrice-work'))
                    <th @if(auth()->user()->hasPermission('editPrice-work')) class="code" @endif data-name="code" data-pk="{{ $work->getAttribute('id') }}" scope="row">{{$work->getAttribute('code')}}</th>
                @endif
                    <td>
                    @foreach($work->client->coordinators as $user)

                            @if($user->id)
                                {{ $user->getAttribute('fullname') }}
                            @else
                                Koordinator Yoxdur
                            @endif

                    @endforeach
                    </td>
{{--                    <td>{{ $work->operator->fullname ?? '-' }}</td>--}}
                    <td>{{$work->getRelationValue('creator')->getAttribute('fullname_with_position')}}</td>

                    <td>{{$work->getRelationValue('department')->getAttribute('short')}}</td>

                    <td>{{ $work->sorter->fullname ?? '-' }}</td>
                    <td>{{ $work->analyst->fullname ?? '-' }}</td>
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
                                    $color = '';
                                    break;
                                case(7):
                                    $color = 'success';
                                    break;
                                case(8):
                                    $color = 'danger';
                                    break;
                                case(9):
                                    $color = 'secondary';
                                    break;
                            }
                        @endphp
                    @endif
                    <span  @if( $work->getAttribute('status') == '6' ) class="badge" style="font-size: 12px; background-color: #8A2BE2 !important;color: white"@else class="badge badge-{{$color}} " style="font-size: 12px" @endif  >
                         {{trans('translates.work_status.' . $work->getAttribute('status'))}}
                    </span>
                </td>
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
                    <td>{{$work->getParameter($work::GB)}}</td>
                    <td>{{$work->getParameter($work::CODE)}}</td>
                    <td>{{$work->getParameter($work::SERVICECOUNT)}}</td>
                    @php
                        $sum_tax = $work->getParameter($work::PAID) + $work->getParameter($work::VATPAYMENT);
                        $sum_payment = $work->getParameter($work::PAID) + $work->getParameter($work::VATPAYMENT) + $work->getParameter($work::ILLEGALPAID) + $work->getAttribute('bank_charge');
                        $residue_vat = ($work->getParameter($work::VAT) + $work->getParameter($work::AMOUNT) - $sum_tax) * -1;
                        $residue = ($work->getParameter($work::VAT) + $work->getParameter($work::AMOUNT) + $work->getParameter($work::ILLEGALAMOUNT) - $sum_payment) * -1;
                    @endphp
                @if(auth()->user()->hasPermission('viewPrice-work'))
                    <td class="font-weight-bold" data-toggle="tooltip">{{$sum_payment}}</td>
                    <td  class="font-weight-bold" @if($residue < 0) style="color:red" @endif data-toggle="tooltip">@if($residue < 0) {{$residue}} @else 0 @endif</td>
                @endif
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
                        @can('create', \App\Models\Work::class)
                            <a href="{{route('works.create', ['id' => $work])}}" class="btn btn-sm btn-outline-primary">
                                <i class="fal fa-copy"></i>
                            </a>
                        @endcan
{{--                        @if($work->getAttribute('creator_id') != auth()->id() && is_null($work->getAttribute('user_id')) && !auth()->user()->isDeveloper())--}}
{{--                            @can('update', $work)--}}
{{--                                <a title="@lang('translates.buttons.execute')" data-toggle="tooltip" href="{{route('works.edit', $work)}}"--}}
{{--                                   class="btn btn-sm btn-outline-success">--}}
{{--                                    <i class="fal fa-arrow-right"></i>--}}
{{--                                </a>--}}
{{--                            @endcan--}}
{{--                        @endif--}}
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
                                    @if($work->getAttribute('creator_id') == auth()->id() || auth()->user()->isDeveloper() || auth()->id() == $work->getAttribute('user_id') || auth()->user()->hasPermission('canRedirect-work') )
                                        @can('update', $work)
                                            <a href="{{route('works.edit', $work)}}" class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-pen pr-2 text-success"></i>@lang('translates.tasks.edit')
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
                                @if(auth()->user()->isDeveloper() || auth()->user()->hasPermission('editPrice-work') || auth()->user()->hasPermission('canRedirect-work') )
                                        <a data-toggle="modal" data-target="#changeCreate-{{$work->getAttribute('id')}}" class="dropdown-item-text text-decoration-none">
                                            <i class="fal fa-money-check pr-2 text-success"></i>Change Create Date
                                        </a>
                                @endif
                            </div>
                        </div>
                        <td>
                            <button type="button" class="colorButton btn btn-primary" data-works='@json($work)'>
                                @if($work->getAttribute('painted') == 1)
                                    Rəngi sil
                                @else
                                    Təcili
                                @endif
                            </button>
                        </td>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="99" class="hiddenRow">
                    <div class="accordian-body collapse" id="demo{{$work->getAttribute('id')}}">
                        <table>
                            <thead>
                            <tr>
                                @if(auth()->user()->hasPermission('viewPrice-work'))
                                    @php
                                        $desiredOrder = [17, 33, 34, 35, 36, 37, 38, 20, 48, 50, 55];
                                        $serviceParameters = \App\Models\Parameter::whereIn('id', $desiredOrder)
                                                         ->orderByRaw("FIELD(id, " . implode(',', $desiredOrder) . ")")
                                                         ->get();
                                    @endphp
                                    @foreach($serviceParameters as $param)
                                        <th>{{$param->getAttribute('label')}}</th>
                                    @endforeach
                                @endif
                                    <th scope="col">@lang('translates.general.payment_method')</th>
                                    <th scope="col">@lang('translates.fields.created_at')</th>
                                    <th scope="col">@lang('translates.fields.date')</th>
                                    <th scope="col">@lang('translates.fields.paid_at')</th>
                                    <th scope="col">@lang('translates.fields.invoiced_date')</th>

                            </tr>

                            </thead>
                            <tbody>
                            <tr>
                                @if(auth()->user()->hasPermission('viewPrice-work'))
                                    @foreach(\App\Models\Service::serviceParameters() as $param)
                                        @if(in_array($param['data']->getAttribute('id'), [17, 33, 34, 35, 36, 38, 37, 20, 48, 50, 55]))
                                        <td @if(auth()->user()->hasPermission('editPrice-work')) class="update"  @endif data-name="{{$param['data']->getAttribute('id')}}" data-pk="{{ $work->getAttribute('id') }}">{{$work->getParameter($param['data']->getAttribute('id'))}}</td>
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
                                        @endif
                                    @endforeach
                                @endif
                                <td title="{{$work->getAttribute('payment_method')}}" data-toggle="tooltip">{{trans('translates.payment_methods.' . $work->getAttribute('payment_method'))}}</td>
                                <td title="{{optional($work->getAttribute('created_at'))->diffForHumans()}}" data-toggle="tooltip">{{$work->getAttribute('created_at')}}</td>
                                <td title="{{$work->getAttribute('datetime')}}" data-toggle="tooltip">{{$work->getAttribute('datetime')}}</td>
                                <td title="{{$work->getAttribute('paid_at')}}" data-toggle="tooltip">{{optional($work->getAttribute('paid_at'))->format('Y-m-d')}}</td>
                                <td title="{{$work->getAttribute('invoiced_date')}}" data-toggle="tooltip">{{optional($work->getAttribute('invoiced_date'))->format('Y-m-d')}}</td>
                            </tr>
                            <tr>
                                <th colspan="24">Sorğu nömrəsi</th>
                            </tr>
                                <td colspan="24" @if(auth()->user()->hasPermission('editTable-work')) class="declaration" @endif data-name="declaration_no" data-pk="{{$work->getAttribute('id')}}">{{$work->getAttribute('declaration_no')}}</td>

                            </tbody>
                        </table>
                   </div>
                </td>
            </tr>
            @php
                $balance[] = $residue;
                $balance_tax[] = $residue_vat;
                $gb[] = $work->getParameter($work::GB);
                $code[] =  $work->getParameter($work::CODE);
                $serviceCount[] = $work->getParameter($work::SERVICECOUNT);
                $sum_balance = array_sum($balance);
                $sum_balance_tax = array_sum($balance_tax);
                $total_payment[] = $sum_payment;
                $total_tax[] = $sum_tax;
                $sum_total_tax = array_sum($total_tax);
                $sum_total_payment = array_sum($total_payment);
                $gb_count = array_sum($gb);
                $code_count = array_sum($code);
                $service_count = array_sum($serviceCount);
            @endphp
            <div class="modal fade" id="changeCreate-{{$work->getAttribute('id')}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">@lang('translates.fields.created_at')</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('works.changeCreate', $work) }}" method="POST">
                            @csrf @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <input type="date" name="created_at" class="form-control" aria-label="paid_at">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
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
                <td colspan=" @if(auth()->user()->isDeveloper() || auth()->user()->hasPermission('viewPrice-work')) 11 @elseif(auth()->user()->hasPermission('viewAll-work') || auth()->user()->hasPermission('canVerify-work')) 10 @else 9 @endif">
                    <p style="font-size: 16px" class="mb-0"><strong>@lang('translates.total'):</strong></p>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td><p style="font-size: 16px" class="mb-0"><strong>{{ $gb_count}}</strong></p></td>
                <td><p style="font-size: 16px" class="mb-0"><strong>{{ $code_count}}</strong></p></td>
                <td><p style="font-size: 16px" class="mb-0"><strong>{{ $service_count}}</strong></p></td>
                @if(auth()->user()->hasPermission('viewPrice-work'))
                    <td><p style="font-size: 16px" class="mb-0"><strong>{{$sum_total_payment}}</strong></p></td>
                    <td><p style="font-size: 16px" class="mb-0"><strong>{{$residue_vat}}</strong></p></td>
                <td><p style="font-size: 16px" class="mb-0"><strong>{{$sum_balance}}</strong></p></td>
                @endif
                <td colspan="6"></td>
            </tr>
        @endif
        </tbody>
    </table>
    @if($hasPending && auth()->user()->hasPermission('canVerify-work'))
        <div class="col-12 pl-0 py-3">
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
                            <input class="form-control custom-daterange" id="choose-date" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>

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
        @if($works->isNotEmpty())
            const count  = document.getElementById("count").cloneNode(true);
            $("#table > tbody").prepend(count);
        @endif

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
                        cancel: function () {},
                    }
                });
            });
        }
    </script>
    <script>
        const slider = document.querySelector('#table');
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });
        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
        });
        slider.addEventListener('mousemove', (e) => {
            if(!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 3; //scroll-fast
            slider.scrollLeft = scrollLeft - walk;
            console.log(walk);
        });
    </script>
    <script type="text/javascript">
        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $('.code').editable({
            url: "{{ route('work.code') }}",
        });

        $('.declaration').editable({
            url: "{{ route('work.declaration') }}",
        });

        $('.update').editable({
            url: "{{ route('editable') }}",
        });
    </script>
    <script>
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    </script>
{{--    <script>--}}
{{--        function _timer(callback)--}}
{{--        {--}}
{{--            var time = 0;     //  The default time of the timer--}}
{{--            var mode = 1;     //    Mode: count up or count down--}}
{{--            var status = 0;    //    Status: timer is running or stoped--}}
{{--            var timer_id;    //    This is used by setInterval function--}}

{{--            // this will start the timer ex. start the timer with 1 second interval timer.start(1000)--}}
{{--            this.start = function(interval)--}}
{{--            {--}}
{{--                interval = (typeof(interval) !== 'undefined') ? interval : 1000;--}}

{{--                if(status == 0)--}}
{{--                {--}}
{{--                    status = 1;--}}
{{--                    timer_id = setInterval(function()--}}
{{--                    {--}}
{{--                        switch(mode)--}}
{{--                        {--}}
{{--                            default:--}}
{{--                                if(time)--}}
{{--                                {--}}
{{--                                    time--;--}}
{{--                                    generateTime();--}}
{{--                                    if(typeof(callback) === 'function') callback(time);--}}
{{--                                }--}}
{{--                                break;--}}

{{--                            case 1:--}}
{{--                                if(time < 86400)--}}
{{--                                {--}}
{{--                                    time++;--}}
{{--                                    generateTime();--}}
{{--                                    if(typeof(callback) === 'function') callback(time);--}}
{{--                                }--}}
{{--                                break;--}}
{{--                        }--}}
{{--                    }, interval);--}}
{{--                }--}}
{{--            }--}}

{{--            //  Same as the name, this will stop or pause the timer ex. timer.stop()--}}
{{--            this.stop =  function()--}}
{{--            {--}}
{{--                if(status == 1)--}}
{{--                {--}}
{{--                    status = 0;--}}
{{--                    clearInterval(timer_id);--}}
{{--                }--}}
{{--            }--}}

{{--            // Reset the timer to zero or reset it to your own custom time ex. reset to zero second timer.reset(0)--}}
{{--            this.reset =  function(sec)--}}
{{--            {--}}
{{--                sec = (typeof(sec) !== 'undefined') ? sec : 0;--}}
{{--                time = sec;--}}
{{--                generateTime(time);--}}
{{--            }--}}

{{--            // Change the mode of the timer, count-up (1) or countdown (0)--}}
{{--            this.mode = function(tmode)--}}
{{--            {--}}
{{--                mode = tmode;--}}
{{--            }--}}

{{--            // This methode return the current value of the timer--}}
{{--            this.getTime = function()--}}
{{--            {--}}
{{--                return time;--}}
{{--            }--}}

{{--            // This methode return the current mode of the timer count-up (1) or countdown (0)--}}
{{--            this.getMode = function()--}}
{{--            {--}}
{{--                return mode;--}}
{{--            }--}}

{{--            // This methode return the status of the timer running (1) or stoped (1)--}}
{{--            this.getStatus--}}
{{--            {--}}
{{--                return status;--}}
{{--            }--}}

{{--            // This methode will render the time variable to hour:minute:second format--}}
{{--            function generateTime()--}}
{{--            {--}}
{{--                var second = time % 60;--}}
{{--                var minute = Math.floor(time / 60) % 60;--}}
{{--                var hour = Math.floor(time / 3600) % 60;--}}

{{--                second = (second < 10) ? '0'+second : second;--}}
{{--                minute = (minute < 10) ? '0'+minute : minute;--}}
{{--                hour = (hour < 10) ? '0'+hour : hour;--}}

{{--                $('div.timer span.second').html(second);--}}
{{--                $('div.timer span.minute').html(minute);--}}
{{--                $('div.timer span.hour').html(hour);--}}
{{--            }--}}
{{--        }--}}

{{--        // example use--}}
{{--        var timer;--}}

{{--        $(document).ready(function(e)--}}
{{--        {--}}
{{--            timer = new _timer--}}
{{--            (--}}
{{--                function(time)--}}
{{--                {--}}
{{--                    if(time == 0)--}}
{{--                    {--}}
{{--                        timer.stop();--}}
{{--                        alert('time out');--}}
{{--                    }--}}
{{--                }--}}
{{--            );--}}
{{--            timer.reset(0);--}}
{{--            timer.mode(0);--}}
{{--        });--}}
{{--    </script>--}}
    @endsection
