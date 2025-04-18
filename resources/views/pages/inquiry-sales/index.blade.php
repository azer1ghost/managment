@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('style')
    <style>
        .custom-dropdown {
            min-width: max-content !important;
        }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.inquiry_sales')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <button class="btn btn-outline-success mb-3 showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>
    <form class="row" id="inquiryForm">

        <div class="col-12" id="filterContainer" @if(request()->has('daterange')) style="display:block;" @else style="display:none;" @endif>

            <div class="col-12 p-0">
                <div class="row m-0">

                    <div class="form-group col-12 col-md-3 mb-3">
                        <label for="daterange">@lang('translates.filters.date')</label>
                        <input type="text" readonly placeholder="@lang('translates.placeholders.range')" name="daterange"
                               value="{{$daterange}}" id="daterange" class="form-control">
                    </div>
                    <div class="form-group col-12 col-md-3 mb-md-0">
                        <label for="codeFilter">@lang('translates.filters.code')</label>
                        <input type="search" id="codeFilter" name="code" value="{{request()->get('code')}}"
                               placeholder="@lang('translates.placeholders.code')" class="form-control">
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="noteFilter">@lang('translates.fields.note')</label>
                        <input id="noteFilter" name="note" value="{{request()->get('note')}}" placeholder="@lang('translates.placeholders.note')" class="form-control"/>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3">
                        <label for="phoneFilter">@lang('translates.fields.phone')</label>
                        <input id="phoneFilter" name="phone" value="{{request()->get('phone')}}" placeholder="@lang('translates.placeholders.phone')" class="form-control"/>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3">
                        <label class="d-block" for="clientFilter">{{trans('translates.general.select_client')}}</label>
                        <select name="client_id" id="clientFilter"
                                class="custom-select2"
                                data-url="{{route('sales-client.search')}}"
                                style="width: 100% !important;">
                            @if(is_numeric(request()->get('client_id')))
                                <option value="{{request()->get('client_id')}}">{{\App\Models\Client::find(request()->get('client_id'))->getAttribute('fullname_with_voen')}}</option>
                            @endif
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="qvsFilter">QVS</label>
                        <input id="qvsFilter" name="qvs" value="{{request()->get('qvs')}}" placeholder="Filter by QVS" class="form-control"/>
                    </div>

                    <div class="form-group col-12 col-md-2 mb-0">
                        <label class="d-block" for="evaluationFilter">Evaluation</label>
                        <select id="evaluationFilter" data-selected-text-format="count" class="filterSelector"
                                title="@lang('translates.filters.select')" name="evaluation">

                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($evaluations as $evaluation)
                                <option
                                        @if($evaluation->id == request()->get('evaluation')) selected @endif
                                value="{{$evaluation->getAttribute('id')}}" >
                                    {{ucfirst($evaluation->getAttribute('text'))}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-2 mb-0">
                        <label class="d-block" for="subjectFilter">@lang('translates.filters.subject')</label>
                        <select id="subjectFilter" data-selected-text-format="count" class="filterSelector"
                                title="@lang('translates.filters.subject')" name="subject">

                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($subjects as $subject)
                                <option
                                        @if($subject->id == request()->get('subject')) selected @endif
                                value="{{$subject->getAttribute('id')}}" >
                                    {{ucfirst($subject->getAttribute('text'))}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-12 col-md-2 mb-0">
                        <label class="d-block" for="statusFilter">Status</label>
                        <select id="statusFilter" data-selected-text-format="count" class="filterSelector"
                                title="@lang('translates.filters.select')" name="status">

                            <option value="">@lang('translates.filters.select')</option>
                            @foreach($statuses as $status)
                                <option
                                        @if($status->id == request()->get('status')) selected @endif
                                            value="{{$status->getAttribute('id')}}" >
                                            {{ucfirst($status->getAttribute('text'))}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(\App\Models\Inquiry::userCanViewAll() || auth()->user()->isDepartmentChief())
                        <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
                            <label class="d-block" for="writtenByFilter">@lang('translates.filters.written_by')</label>
                            <select id="writtenByFilter" name="user" class="filterSelector" data-width="fit" title="@lang('translates.filters.written_by')">
                                @foreach($users as $user)
                                    @php($inactive = (bool) $user->getAttribute('disabled_at'))
                                    <option
                                            @if($user->id == request()->get('user')) selected @endif value="{{$user->getAttribute('id')}}" class="@if ($inactive) text-danger @endif" >
                                            {{$user->getAttribute('fullname')}} @if ($inactive) (@lang('translates.disabled')) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                </div>
                <div class=" col-offset-9 mt-3 float-right">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="submit" form="inquiryForm" class="btn btn-outline-primary"><i class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                        <a href="{{route('inquiry.sales')}}" class="btn btn-outline-danger"><i class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="input-group col-4 col-md-2 mt-3">
            <select name="limit" class="custom-select" id="size">
                @foreach([25, 50, 100, 250] as $size)
                    <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                @endforeach
            </select>
        </div>
    </form>
    <div class="input-group col-4 col-md-2 mt-3">
        <button class="btn btn-success"><a href="{{ route('inquiry.potential-customers') }}">Potensial Müştərilər</a></button>
    </div>
    <div class="col-12">
        <hr>
        <div class="float-right">
            @can('create', \App\Models\Inquiry::class)
                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#inquiry-create-modal-btn">
                    <i class="fal fa-plus"></i>
                </button>
            @endcan
            <a href="{{ !request()->has('trash-box') ? route('inquiry.sales', ['trash-box' => true]) : route('inquiry.sales') }}"
               class="btn btn-outline-secondary">
                <i class="far {{ !request()->has('trash-box') ? 'fa-recycle' : 'fa-phone' }}"></i>
            </a>
        </div>

        <div class="col-6 pt-2 ">
            <p> @lang('translates.total_items', ['count' => $inquiries->count(), 'total' => $inquiries->total()])</p>
        </div>
    </div>

    <form action="{{route('inquiry.editable-mass-access-update')}}" method="POST">
        @csrf
        <div class="col-md-12 overflow-auto">
            <table class="table table-responsive-sm table-hover table-striped" style="min-height: 200px">
                <thead>
                <tr>
                    <th>@lang('translates.fields.mgCode')</th>
                    <th>@lang('translates.fields.date')</th>
                    <th>@lang('translates.fields.time')</th>
                    <th>@lang('translates.fields.clientName')</th>
                    <th>@lang('translates.fields.writtenBy')</th>
                    <th>@lang('translates.columns.evaluation')</th>
                    <th>@lang('translates.fields.subject')</th>
                    <th class="text-center">Status</th>
                    <th>@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($inquiries as $inquiry)
                    <tr @if((optional($inquiry->getParameter('status'))->getAttribute('id') == \App\Models\Inquiry::REJECTED) && $inquiry->getAttribute('checking') == 0) style="background-color: red" @endif >
                        <td>{{$inquiry->getAttribute('code')}}</td>
                        <td>{{$inquiry->getAttribute('datetime')->format('d-m-Y')}}</td>
                        <td>{{$inquiry->getAttribute('datetime')->format('H:i')}}</td>
                        <td>{{$inquiry->getRelationValue('client')->getAttribute('name_with_voen')}}</td>
                        <td>{{$inquiry->getRelationValue('user')->getAttribute('fullname')}} {!! $inquiry->getRelationValue('user')->getAttribute('disabled_at') ? ' <span class="text-danger">(' . __('translates.disabled') . ')</span>' : '' !!}</td>
                        <td>{{optional($inquiry->getParameter('evaluation'))->getAttribute('text')}}</td>
                        <td>{{optional($inquiry->getParameter('subject'))->getAttribute('text')}}</td>
                        <td class="text-center">
                            @if($inquiry->getAttribute('wasDone'))
                                <i class="fa fa-check text-success" style="font-size: 18px"></i>
                            @elseif (auth()->id() != $inquiry->getAttribute('user_id') || optional($inquiry->getParameter('status'))->getAttribute('id') == \App\Models\Inquiry::REDIRECTED)
                                {{optional($inquiry->getParameter('status'))->getAttribute('text') ?? __('translates.filters.select')}}
                            @else
                                @if($trashBox)
                                    {{optional($inquiry->getParameter('status'))->getAttribute('text') ?? __('translates.filters.select')}}
                                @else
                                    <select class="form-control" style="width:auto;"
                                            onfocus="this.oldValue = this.value"
                                            id="inquiry-{{$inquiry->getAttribute('id')}}"
                                            onchange="inquiryStatusHandler(this, {{$inquiry->getAttribute('id')}}, '{{$inquiry->getAttribute('code')}}', this.oldValue, this.value)">
                                        <option value="0" @if (!optional($inquiry->getParameter('status'))->getAttribute('id')) selected @endif>@lang('translates.filters.select')</option>
                                        @foreach ($statuses as $status)
                                            @if($status->getAttribute('id') == \App\Models\Inquiry::REDIRECTED)
                                                @continue
                                            @endif
                                            <option
                                                    @if ($status->getAttribute('id') == optional($inquiry->getParameter('status'))->getAttribute('id')) selected @endif
                                                    value="{{$status->getAttribute('id')}}">
                                                {{$status->getAttribute('text')}}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div class="btn-sm-group d-flex align-items-center justify-content-center">

                                @can('view', $inquiry)
                                    @if($inquiry->getAttribute('client_id'))
                                            <button class="btn btn-sm btn-outline-primary mr-2 client-edit-btn" type="button" data-client='@json($inquiry->getRelationValue('client'))' data-toggle="modal" data-target="#inquiry-client">
                                            <i class="fas fa-user-tie"></i>
                                        </button>
                                    @endif
                                @endcan
                                @if(!$trashBox)
                                    @can('view', $inquiry)
                                        <a target="_blank" href="{{route('inquiry.show', $inquiry)}}"
                                           class="btn btn-sm btn-outline-primary mr-2">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                @endif
                                <div class="dropdown">
                                    <button class="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fal fa-ellipsis-v-alt"></i>
                                    </button>
                                    <div class="dropdown-menu custom-dropdown">
                                        @if($trashBox)
                                            @can('restore', $inquiry)
                                                <a href="{{route('inquiry.restore', $inquiry)}}"
                                                   class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-repeat pr-2 text-info"></i>Restore
                                                </a>
                                            @endcan
                                            @can('forceDelete', $inquiry)
                                                <a href="javascript:void(0)"
                                                   onclick="deleteAction('{{route('inquiry.forceDelete', $inquiry)}}', '{{$inquiry->code}}')"
                                                   class="dropdown-item-text text-decoration-none">
                                                    <i class="fa fa-times pr-2 text-danger"></i>Permanent delete
                                                </a>
                                            @endcan
                                        @else
                                            @can('update', $inquiry)
                                                <a href="{{route('inquiry.edit', $inquiry)}}" target="_blank"
                                                   class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-pen pr-2 text-success"></i>Edit
                                                </a>
                                            @endcan
                                            @can('delete', $inquiry)
                                                <a href="javascript:void(0)"
                                                   onclick="deleteAction('{{route('inquiry.destroy', $inquiry)}}', '{{$inquiry->code}}')"
                                                   class="dropdown-item-text text-decoration-none">
                                                    <i class="fal fa-trash-alt pr-2 text-danger"></i>Delete
                                                </a>
                                            @endcan
                                        @endif
                                        @if(auth()->user()->hasPermission('editAccessToUser-inquiry'))
                                            <a href="{{route('inquiry.access', $inquiry)}}" target="_blank"
                                               class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-lock-open-alt pr-2 text-info"></i>@lang('translates.access')
                                            </a>
                                        @endif
                                        <a href="{{route('inquiry.logs', $inquiry)}}" target="_blank"
                                           class="dropdown-item-text text-decoration-none">
                                            <i class="fal fa-sticky-note pr-2 text-info"></i>Logs
                                        </a>

                                        @php($taskRoute = $inquiry->task()->exists() ? route('tasks.show', $inquiry->task) : route('inquiry.task', $inquiry))
                                        <a href="{{$taskRoute}}" target="_blank"
                                           class="dropdown-item-text text-decoration-none">
                                            <i class="fal fa-list pr-2 text-info"></i>Task
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <th colspan="20">
                            <div class="row justify-content-center m-3">
                                <div class="col-7 alert alert-danger text-center" task="alert">@lang('translates.general.empty')</div>
                            </div>
                        </th>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="col-12">
            <div class="float-right">
                {{$inquiries->appends(request()->input())->links()}}
            </div>
        </div>
    </form>




    <!-- Inquiry Create Modal -->
    <div class="modal fade" id="inquiry-create-modal-btn">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('inquiry.create')}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('translates.fields.client')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-12 mt-3 mb-3 pl-0">
                            <label class="d-block" for="data-client-type">{{trans('translates.general.select_client')}}</label>
                            <select name="client_id" id="data-client-type"
                                    required
                                    class="custom-select2"
                                    data-url="{{route('sales-client.search')}}"
                            >
                            </select>
                        </div>
                        <input type="hidden" name="company" value="{{\App\Models\Company::MOBIL_GROUP}}">

                        <a href="{{route('sales-client.create', ['close' => true])}}" target="_blank" class="btn btn-outline-success"><i class="fas fa-plus"></i></a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('translates.buttons.create')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="inquiry-client">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" id="client-form">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('translates.general.client_data')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-1">
                            <label for="data-name">@lang('translates.fields.name')</label>
                            <input class="form-control"  type="text"  name="name">
                        </div>

                        <div class="form-group mb-1">
                            <label for="data-phone">@lang('translates.fields.phone')</label>
                            <input class="form-control" type="text" name="phone">
                        </div>

                        <div class="form-group mb-1">
                            <label for="data-voen">Voen</label>
                            <input class="form-control" type="text" name="voen">
                        </div>

                        <div class="form-group mb-0">
                            <label for="data-detail">@lang('translates.fields.detail')</label>
                            <textarea class="form-control" type="text" name="detail" cols="30" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                        <button type="submit" form="client-form" class="btn btn-primary submit">@lang('translates.buttons.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $('.client-edit-btn').on('click', function (e){
            let client = $(this).data('client')

            let formAction = "{{route('sales-client.update', 'clinet_id')}}".replace('clinet_id', client.id);

            $('#client-form').attr('action', formAction)

            $('#client-form input[name="name"]').val(client.name)
            $('#client-form input[name="phone"]').val(client.phone)
            $('#client-form input[name="voen"]').val(client.voen)
            $('#client-form textarea[name="detail"]').val(client.detail)
        })
    </script>

    <script>
        function alertHandler(event) {
            $.alert({
                type: event?.detail?.type,
                title: event?.detail?.title,
                content: event?.detail?.message,
                theme: 'modern',
                typeAnimated: true
            });
        }

        addEventListener('alert', alertHandler);

        function inquiryStatusFormHandler(inquiryId, oldVal, newVal) {
            $.ajax({
                url: '{{route('inquiry.update-status')}}',
                data: {
                    inquiryId,
                    oldVal,
                    newVal
                },
                type: 'PUT',
                dataType: 'JSON',
                success: function (response) {
                    const data = response.data;
                    dispatchEvent(new CustomEvent('alert', {
                        detail: {
                            type: data.type,
                            title: data.title,
                            message: data.message
                        }
                    }));
                },
                error: function () {
                    dispatchEvent(new CustomEvent('alert', {
                        detail: {
                            type: 'red',
                            title: 'Error',
                            message: 'Something went wrong, please try again later'
                        }
                    }));
                }
            });
        }

        function inquiryStatusHandler(element, inquiryId, mgCode, oldVal, val) {
            $.confirm({
                title: `${mgCode} update`,
                content: `Are you sure to change status from ${$("#" + $(element).attr('id') + ` option[value=${oldVal}]`).text()} to ${$("#" + $(element).attr('id') + ` option[value=${val}]`).text()}?`,
                autoClose: 'confirm|8000',
                icon: 'fa fa-question',
                type: 'red',
                theme: 'modern',
                typeAnimated: true,
                buttons: {
                    confirm: function () {
                        inquiryStatusFormHandler(+inquiryId, +oldVal, +val);
                    },
                    cancel: function () {
                        $(element).val(oldVal);
                    },
                }
            });
        }

        function deleteAction(url, name) {
            $.confirm({
                title: 'Confirm delete action',
                content: `Are you sure delete <b>${name}</b> ?`,
                autoClose: 'confirm|8000',
                icon: 'fa fa-question',
                type: 'red',
                theme: 'modern',
                typeAnimated: true,
                buttons: {
                    confirm: function () {
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            success: function () {
                                $.confirm({
                                    title: 'Delete successful',
                                    icon: 'fa fa-check',
                                    content: '<b>:name</b>'.replace(':name', name),
                                    type: 'blue',
                                    typeAnimated: true,
                                    autoClose: 'reload|3000',
                                    theme: 'modern',
                                    buttons: {
                                        reload: {
                                            text: 'Ok',
                                            btnClass: 'btn-blue',
                                            keys: ['enter'],
                                            action: function () {
                                                window.location.reload()
                                            }
                                        }
                                    }
                                });
                            },
                            error: function () {
                                $.confirm({
                                    title: 'Confirm!',
                                    content: 'Ops something went wrong! Please reload page and try again.',
                                    type: 'red',
                                    typeAnimated: true,
                                    buttons: {
                                        cancel: function () {

                                        },
                                        reload: {
                                            text: 'Reload page',
                                            btnClass: 'btn-blue',
                                            keys: ['enter'],
                                            action: function () {
                                                window.location.reload()
                                            }
                                        }
                                    }
                                });
                            }
                        });
                    },
                    cancel: function () {},
                }
            });
        }
    </script>
@endsection
