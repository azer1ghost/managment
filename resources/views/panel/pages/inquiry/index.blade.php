@extends('layouts.main')

@section('title', __('translates.navbar.inquiry'))

@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
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
            @lang('translates.navbar.inquiry')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form class="row" id="inquiryForm">
        <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
            <label for="daterange">@lang('translates.filters.date')</label>
            <input type="text" placeholder="@lang('translates.placeholders.range')" name="daterange"
                   value="{{$daterange}}" id="daterange" class="form-control">
        </div>
        <div class="form-group col-12 col-md-3 mb-md-0">
            <label for="codeFilter">@lang('translates.filters.code')</label>
            <input type="search" id="codeFilter" name="code" value="{{request()->get('code')}}"
                   placeholder="@lang('translates.placeholders.code')" class="form-control">
        </div>

        <div class="form-group col-12 col-md-3 mb-md-0">
            <label for="clientNamePhoneFilter">@lang('translates.filters.or', ['first' => __('translates.fields.client'), 'second' => __('translates.fields.phone'), 'third' => __('translates.fields.mail')])</label>
            <input type="search" id="clientNamePhoneFilter" name="search_client"
                   value="{{request()->get('search_client')}}"
                   placeholder="@lang('translates.placeholders.or', ['first' => __('translates.fields.client'), 'second' => __('translates.fields.phone'), 'third' =>  __('translates.fields.mail')])"
                   class="form-control">
        </div>

        <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
            <label for="noteFilter">@lang('translates.fields.note')</label>
            <input id="noteFilter" name="note" value="{{request()->get('note')}}"
                   placeholder="@lang('translates.placeholders.note')" class="form-control"/>
        </div>

        <div class="form-group col-12 col-md-3 mt-3 mb-3 mb-md-0">
            <label class="d-block" for="subjectFilter">@lang('translates.filters.subject')</label>
            <select id="subjectFilter" multiple class="filterSelector form-control" data-selected-text-format="count"
                    data-width="fit" title="@lang('translates.filters.select')">
                @php($subjectsRequest = explode(',', request()->get('subject')))
                @foreach($subjects as $subject)
                    <option
                        @if(in_array($subject->id, $subjectsRequest)) selected @endif
                        value="{{$subject->getAttribute('id')}}"
                    >
                        {{ucfirst($subject->getAttribute('text'))}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
            <label class="d-block" for="statusFilter">Status</label>
            <select id="statusFilter" multiple data-selected-text-format="count" class="filterSelector form-control"
                    data-selected-text-format="count" data-width="fit" title="@lang('translates.filters.select')">
                @php($statusesRequest = explode(',', request()->get('status')))
                @foreach($statuses as $status)
                    <option
                        @if(in_array($status->id, $statusesRequest)) selected @endif
                        value="{{$status->getAttribute('id')}}"
                    >
                        {{ucfirst($status->getAttribute('text'))}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
            <label class="d-block" for="companyFilter">@lang('translates.filters.company')</label>
            <select id="companyFilter" multiple data-selected-text-format="count" class="filterSelector"
                    data-width="fit" title="@lang('translates.filters.select')">
                @php($companiesRequest = explode(',', request()->get('company')))
                @foreach($companies as $company)
                    <option
                        @if(in_array($company->id, $companiesRequest)) selected @endif
                        value="{{$company->getAttribute('id')}}"
                    >
                        {{ucfirst($company->getAttribute('name'))}}
                    </option>
                @endforeach
            </select>
        </div>

        @if(\App\Models\Inquiry::userCanViewAll())
            <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
                <label class="d-block" for="writtenByFilter">@lang('translates.filters.written_by')</label>
                <select id="writtenByFilter" name="user" class="filterSelector" data-width="fit"
                        title="@lang('translates.filters.written_by')">
                    @foreach($users as $user)
                        @php($inactive = (bool) $user->getAttribute('disabled_at'))
                        <option
                            @if($user->id == request()->get('user')) selected @endif
                            value="{{$user->getAttribute('id')}}" class="@if ($inactive) text-danger @endif"
                        >
                            {{$user->getAttribute('fullname')}} @if ($inactive) (@lang('translates.disabled')) @endif
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
            <label class="d-block" for="sourceFilter">@lang('translates.filters.source')</label>
            <select id="sourceFilter" multiple data-selected-text-format="count" class="filterSelector" data-width="fit"
                    title="@lang('translates.filters.source')">
                @php($sourcesRequest = explode(',', request()->get('source')))
                @foreach($sources as $source)
                    <option
                        @if(in_array($source->id, $sourcesRequest)) selected @endif
                        value="{{$source->getAttribute('id')}}"
                    >
                        {{ucfirst($source->getAttribute('text'))}}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
            <label class="d-block" for="contactMethodFilter">@lang('translates.filters.contact_method')</label>
            <select id="contactMethodFilter" multiple data-selected-text-format="count" class="filterSelector"
                    data-width="fit" title="@lang('translates.filters.contact_method')">
                @php($contactMethodsRequest = explode(',', request()->get('contact_method')))
                @foreach($contact_methods as $contact_method)
                    <option
                        @if(in_array($contact_method->id, $contactMethodsRequest)) selected @endif
                        value="{{$contact_method->getAttribute('id')}}"
                    >
                        {{ucfirst($contact_method->getAttribute('text'))}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12 col-md-3 mt-3 mb-md-0">
            <label class="d-block" for="typeFilter">@lang('translates.inquiries.label')</label>
            <select id="typeFilter" name="is_out" class="filterSelector" data-width="fit"
                    title="@lang('translates.inquiries.label')">
                @foreach(['from_customers', 'from_us'] as $index => $type)
                    <option
                        @if(!is_null(request()->get('is_out')) && $index == request()->get('is_out')) selected
                        @endif
                        value="{{$index}}"
                    >
                        @lang('translates.inquiries.types.' . $type)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-12 col-md-3 mt-3 d-flex align-items-center justify-content-end">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="submit" class="btn btn-outline-primary"><i
                            class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                <a href="{{route('inquiry.index')}}" class="btn btn-outline-danger"><i
                            class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
            </div>
        </div>
        <div class="input-group col-md-3 mt-2">
            <select name="limit" class="custom-select" id="size">
                @foreach([25, 50, 100, 250] as $size)
                    <option @if(request()->get('limit') == $size) selected @endif value="{{$size}}">{{$size}}</option>
                @endforeach
            </select>
        </div>
    </form>

    <div class="col-12">
        <hr>
        <div class="float-right">
            @can('create', \App\Models\Inquiry::class)
                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#inquiry-create-modal-btn">
                    <i class="fal fa-plus"></i>
                </button>
            @endcan
            <a href="{{ !request()->has('trash-box') ? route('inquiry.index', ['trash-box' => true]) : route('inquiry.index') }}"
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
                    @if(auth()->user()->isDeveloper())
                        <th><input type="checkbox" id="inquiry-all"></th>
                    @endif
                    <th>@lang('translates.fields.mgCode')</th>
                    <th>@lang('translates.fields.date')</th>
                    <th>@lang('translates.fields.time')</th>
                    <th>@lang('translates.fields.company')</th>
                    <th>@lang('translates.fields.clientName')</th>
                    <th>@lang('translates.fields.writtenBy')</th>
                    <th>@lang('translates.fields.subject')</th>
                    <th class="text-center">Status</th>
                    <th>@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($inquiries as $inquiry)
                    <tr>
                        @if(auth()->user()->isDeveloper())
                            <td><input type="checkbox" name="inquiries[]" value="{{$inquiry->id}}"></td>
                        @endif
                        <td>{{$inquiry->getAttribute('code')}}</td>
                        <td>{{$inquiry->getAttribute('datetime')->format('d-m-Y')}}</td>
                        <td>{{$inquiry->getAttribute('datetime')->format('H:i')}}</td>
                        <td>{{$inquiry->getRelationValue('company')->getAttribute('name')}}</td>
                        <td>{{optional($inquiry->getParameter('fullname'))->getAttribute('value')}}</td>
                        <td>{{$inquiry->getRelationValue('user')->getAttribute('fullname')}} {!! $inquiry->getRelationValue('user')->getAttribute('disabled_at') ? ' <span class="text-danger">(' . __('translates.disabled') . ')</span>' : '' !!}</td>
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
                                @if(!$trashBox)
                                    @can('view', $inquiry)
                                        <a target="_blank" href="{{route('inquiry.show', $inquiry)}}"
                                           class="btn btn-sm btn-outline-primary mr-2">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                @endif
                                <div class="dropdown">
                                    <button class="btn" type="button" id="inquiry_actions-{{$loop->iteration}}"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex">
            @if(auth()->user()->isDeveloper())
                <div class="col-3">
                    <button type="button" disabled class="btn btn-outline-primary" id="inquiries-access-btn"
                            data-toggle="modal" data-target="#inquiries-access">
                        Access
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="inquiries-access" data-backdrop="static" data-keyboard="false"
                         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Access</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input class="form-control editable-ended-at" type="text" name="editable-date">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-outline-primary">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="@if(auth()->user()->isDeveloper()) col-9 @else col-12 @endif">
                <div class="float-right">
                    {{$inquiries->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
    <!-- Inquiry Create Modal -->
    <div class="modal fade" id="inquiry-create-modal-btn" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('inquiry.create')}}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@lang('translates.register.company')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-12 p-0">
                            <select class="form-control" name="company" required>
                                @foreach($companies as $company)
                                    <option value="{{$company->getAttribute('id')}}">
                                        {{$company->getAttribute('name')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('translates.buttons.close')</button>
                        <button type="submit" class="btn btn-primary">@lang('translates.buttons.save')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        const inquiryRoute = '{{route('inquiry.index')}}';

        $('select[name="limit"]').change(function () {
            setParams();
        });

        const form = $('#inquiryForm');

        function setParams(){
            const params = new URLSearchParams({
                subject: $('#subjectFilter').val().join(','),
                company: $('#companyFilter').val().join(','),
                status: $('#statusFilter').val().join(','),
                contact_method: $('#contactMethodFilter').val().join(','),
                source: $('#sourceFilter').val().join(','),
            });
            window.location = inquiryRoute + "?" + form.serialize() + "&" + params.toString();
        }

        form.on('submit', function (e) {
            e.preventDefault();
            setParams();
        });

        $('.editable-ended-at').daterangepicker({
                opens: 'left',
                locale: {
                    format: "YYYY-MM-DD HH:mm:ss",
                },
                singleDatePicker: true,
                timePicker: true,
                timePicker24Hour: true,
            }, function (start, end, label) {
            }
        );

        $(function () {
            $('#daterange').daterangepicker({
                    opens: 'left',
                    locale: {
                        format: "YYYY/MM/DD",
                    },
                    maxDate: new Date(),
                }, function (start, end, label) {
                }
            );
        });

        $('#inquiry-all').change(function () {
            if ($(this).is(':checked')) {
                $("input[name='inquiries[]']").map(function () {
                    $(this).prop('checked', true)
                });
                $('#inquiries-access-btn').prop('disabled', false);
            } else {
                $("input[name='inquiries[]']").map(function () {
                    $(this).prop('checked', false)
                });
                $('#inquiries-access-btn').prop('disabled', true);
            }
        });

        // Check if at least one inquiry selected
        $("input[name='inquiries[]']").change(function () {
            let hasOneChecked = false;
            $("input[name='inquiries[]']").map(function () {
                if ($(this).is(':checked')) {
                    hasOneChecked = true;
                }
            });
            if (hasOneChecked) {
                $('#inquiries-access-btn').prop('disabled', false);
            } else {
                $('#inquiries-access-btn').prop('disabled', true);
            }
        });

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

        $('.filterSelector').selectpicker()

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
                    cancel: function () {

                    },
                }
            });
        }

    </script>


@endsection
