@extends('layouts.main')
@section('title', __('translates.navbar.logistics'))
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.logistics')
        </x-bread-crumb-link>
    </x-bread-crumb>

    <button class="btn btn-outline-success showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>

    <form action="{{route('logistics.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div id="filterContainer" class="mb-3" @if(request()->has('datetime')) style="display:block;" @else style="display:none;" @endif>
                <div class="col-12">
                    <div class="row m-0">
{{--                        <div class="form-group col-12 col-md-3 my-3 mb-md-0 pl-0">--}}
{{--                            <label for="reg_numberFilter">Registration Number</label>--}}
{{--                            <input type="search" id="reg_numberFilter" name="reg_number" value="{{$filters['reg_number']}}"--}}
{{--                                   placeholder="Registration Number" class="form-control">--}}
{{--                        </div>--}}

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="userFilter">{{__('translates.general.user_select')}}</label>
                            <select id="userFilter" class="select2"
                                    name="user_id"
                                    data-width="fit" title="{{__('translates.filters.select')}}">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($users as $user)
                                    <option
                                        @if($user->getAttribute('id') == $filters['user_id']) selected @endif
                                            value="{{$user->getAttribute('id')}}">
                                        {{$user->getAttribute('fullname_with_position')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="reference_id">{{__('translates.navbar.reference')}}</label>
                            <select id="reference_id" class="select2"
                                    name="reference_id"
                                    data-width="fit" title="{{__('translates.filters.select')}}">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($references as $reference)
                                    <option
                                        @if($reference->getAttribute('id') == $filters['reference_id']) selected @endif
                                            value="{{$reference->getAttribute('id')}}">
                                        {{$reference->getAttribute('fullname_with_position')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

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
                                    data-url="{{route('clients.search')}}">
                                @if(is_numeric($filters['client_id']))
                                    <option value="{{$filters['client_id']}}">{{\App\Models\Client::find($filters['client_id'])->getAttribute('fullname')}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="transportTypeFilter">{{trans('translates.general.transport_type_choose')}}</label>
                            <select name="transport_type" id="transportTypeFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($transportTypes as $transportType)
                                    <option value="{{$transportType}}"
                                            @if($transportType == $filters['transport_type']) selected @endif>
                                        @lang('translates.transport_types.' . $transportType)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="statusFilter">{{trans('translates.general.status_choose')}}</label>
                            <select name="status" id="statusFilter" class="form-control" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($statuses as $status)
                                    <option value="{{$status}}"
                                            @if($status == $filters['status']) selected @endif>
                                        @lang('translates.logistics_statuses.' . $status)
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
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.date')}}</label>
                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="datetime" value="{{$filters['datetime']}}">
                            <input type="checkbox" name="check-datetime" id="check-datetime" @if(request()->has('check-datetime')) checked @endif> <label for="check-datetime">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="datetimeFilter">{{trans('translates.fields.paid_at')}}</label>
                            <input class="form-control custom-daterange mb-1" id="datetimeFilter" type="text" readonly name="paid_at_date" value="{{request()->get('paid_at_date')}}">
                            <input type="checkbox" name="check-paid_at" id="check-paid_at" @if(request()->has('check-paid_at')) checked @endif> <label for="check-paid_at">@lang('translates.filters.filter_by')</label>
                        </div>

                        <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="submit" class="btn btn-outline-primary"><i
                                            class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                                <a href="{{route('logistics.index')}}" class="btn btn-outline-danger"><i
                                            class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-3 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $logistics->count(), 'total' => is_numeric($filters['limit']) ? $logistics->total() : $logistics->count()])</p>
                <div class="input-group col-md-6">
                    <select name="limit" aria-label="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250, 500] as $size)
                            <option @if($filters['limit'] == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

                <div class="col-sm-6 py-3">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-logistics">@lang('translates.buttons.create')</a>
{{--                        <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('logistics.export', ['filters' => json_encode($filters), 'dateFilters' => json_encode($dateFilters)])}}">--}}
{{--                            @lang('translates.buttons.export')--}}
{{--                        </a>--}}
                </div>

        </div>
    </form>
    @if(is_numeric($filters['limit']))
        <div class="col-12 mt-2">
            <div class="float-right">
                {{$logistics->appends(request()->input())->links()}}
            </div>
        </div>
    @endif
    <table class="table table-responsive-sm" id="table">
        <thead>
        <tr class="text-center">
{{--            <th scope="col">Registration Number</th>--}}
            <th scope="col">@lang('translates.fields.user')</th>
            <th scope="col">@lang('translates.navbar.service')</th>
            <th scope="col">@lang('translates.general.transport_type')</th>
            <th scope="col">@lang('translates.fields.clientName')</th>
            @if(auth()->user()->hasPermission('update-logistics'))
                @php
                    $serviceParameters = \App\Models\Parameter::whereIn('id', [51, 52, 53, 54])->get();
                @endphp

                @foreach($serviceParameters as $param)
                    <th>{{$param->getAttribute('label')}}</th>
                @endforeach
                <th scope="col">Profit</th>
                <th scope="col">Gəlir</th>
            @endif

            <th scope="col">Status</th>
            <th scope="col">@lang('translates.fields.created_at')</th>
            <th scope="col">@lang('translates.fields.date')</th>
{{--            <th scope="col">@lang('translates.fields.paid_at')</th>--}}
            <th scope="col">@lang('translates.columns.actions')</th>
        </tr>
        </thead>
        <tbody>
        @php
            $totals = []; // array of countable service parameters. Ex: Declaration count
            $total_payment = [];
        @endphp
        @forelse($logistics as $log)
            <tr class="text-center">
{{--                <td>{{$log->getAttribute('reg_number')}}</td>--}}
                <td>{{$log->getRelationValue('user')->getAttribute('fullname_with_position')}}</td>
                <td>{{$log->getRelationValue('service')->getAttribute('name')}}</td>
                <td>{{trans('translates.transport_types.' . $log->getAttribute('transport_type'))}}</td>
                <td data-toggle="tooltip" data-placement="bottom" title="{{$log->getRelationValue('client')->getAttribute('fullname')}}" >
                    {{mb_strimwidth($log->getRelationValue('client')->getAttribute('fullname'), 0, 20, '...')}}
                </td>
                @if(auth()->user()->hasPermission('update-logistics'))
                @foreach(\App\Models\Service::serviceParameters() as $param)
                    @if(in_array($param['data']->getAttribute('id'), [51, 52, 53, 54]))
                    <td>{{$log->getParameter($param['data']->getAttribute('id'))}}</td>
                    @php
                        if($param['count']){ // check if parameter is countable
                            $count = (int) $log->getParameter($param['data']->getAttribute('id'));
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
                    <td>{{$log->getParameter(\App\Models\Logistics::SALES) - $log->getParameter(\App\Models\Logistics::PURCHASE)}}</td>
                    <td>{{$log->getParameter(\App\Models\Logistics::SALESPAID) - $log->getParameter(\App\Models\Logistics::PURCHASEPAID)}}</td>
                @endif

                <td>
                    <span class="badge badge-primary" style="font-size: 12px">
                         {{trans('translates.logistics_statuses.' . $log->getAttribute('status'))}}
                    </span>
                </td>

                <td title="{{optional($log->getAttribute('created_at'))->diffForHumans()}}" data-toggle="tooltip">{{$log->getAttribute('created_at')}}</td>
                <td title="{{$log->getAttribute('datetime')}}" data-toggle="tooltip">{{optional($log->getAttribute('datetime'))->format('Y-m-d')}}</td>
{{--                <td title="{{$log->getAttribute('paid_at')}}" data-toggle="tooltip">{{optional($log->getAttribute('paid_at'))->format('Y-m-d')}}</td>--}}
{{--                    @php--}}
{{--                        $sum_payment = $work->getParameter($work::PAID) + $work->getParameter($work::VATPAYMENT) + $work->getParameter($work::ILLEGALPAID) + $work->getAttribute('bank_charge');--}}
{{--                        $residue = ($work->getParameter($work::VAT) + $work->getParameter($work::AMOUNT) + $work->getParameter($work::ILLEGALAMOUNT) - $sum_payment) * -1;--}}
{{--                    @endphp--}}
                <td>
                    <div>
                        <a href="{{route('logistics.show', $log)}}" class="text-decoration-none">
                            <i class="fal fa-eye pr-2 fa-2x text-primary"></i>
                        </a>

                        <a href="{{route('logistics.edit', $log)}}" class="text-decoration-none">
                            <i class="fal fa-pen fa-2x pr-2 text-success"></i>
                        </a>

                        <a href="{{route('logistics.destroy', $log)}}" delete
                           data-name="{{$log->getAttribute('reg_number')}}" class="text-decoration-none">
                            <i class="fal fa-trash pr-2 fa-2x text-danger"></i>
                        </a>
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

    <div class="modal fade" id="create-logistics">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('logistics.create')}}" method="GET">
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
    <script>
        @if($logistics->isNotEmpty())
        const count  = document.getElementById("count").cloneNode(true);
        $("#table > tbody").prepend(count);
        @endif

        confirmJs($("a[verify]"));
        confirmJs($("#sum-verify"));

        const logisticsCheckbox = $("input[name='logistics[]']");

        $('#logistics-all').change(function () {
            if ($(this).is(':checked')) {
                logisticsCheckbox.map(function () {
                    $(this).prop('checked', true)
                });
                $('#sum-verify').removeClass('disabled');
            } else {
                logisticsCheckbox.map(function () {
                    $(this).prop('checked', false)
                });
                $('#sum-verify').addClass('disabled');
            }
        });

        // Check if at least one inquiry selected
        logisticsCheckbox.change(function () {
            checkUnverifiedLogistics();
        });

        checkUnverifiedLogistics();

        function checkUnverifiedLogistics(){
            let hasOneChecked = false;
            logisticsCheckbox.map(function () {
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
                const checkedLogistics = [];

                $("input[name='logistics[]']:checked").each(function(){
                    checkedLogistics.push($(this).val());
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
                                data: {'logistics': checkedLogistics},
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
    <script>
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    </script>
    @endsection
