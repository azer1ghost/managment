@extends('layouts.main')

@section('title', __('translates.navbar.barcode'))

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
            @lang('translates.navbar.barcode')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <button class="btn btn-outline-success mb-3 showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>
    <form class="row" id="inquiryForm">

        <div id="filterContainer" @if(request()->has('daterange')) style="display:block;" @else style="display:none;" @endif>

            <div class="col-12">
                <div class="row m-0">

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
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

                    <div class="form-group col-12 col-md-3 mb-3 mb-md-0">
                        <label for="qvsFilter">QVS</label>
                        <input id="qvsFilter" name="qvs" value="{{request()->get('qvs')}}" placeholder="Filter by QVS" class="form-control"/>
                    </div>

                    <div class="form-group col-12 col-md-3 mt-3">
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

                    <div class="form-group col-12 col-md-3 mt-3">
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

                    <div class="form-group col-12 col-md-3 mt-3">
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

                @if(\App\Models\Barcode::userCanViewAll() || auth()->user()->isDepartmentChief())
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
                        <button type="submit" class="btn btn-outline-primary"><i class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                        <a href="{{route('barcode.index')}}" class="btn btn-outline-danger"><i class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
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

    <div class="col-12">
        <hr>
        <div class="float-right">
            @can('create', \App\Models\Barcode::class)
                <a type="button" class="btn btn-outline-success" href="{{route('barcode.create')}}" class="text-secondary">@lang('translates.buttons.create')</a>
            @endcan
        </div>

        <div class="col-6 pt-2 ">
            <p> @lang('translates.total_items', ['count' => $barcodes->count(), 'total' => $barcodes->total()])</p>
        </div>
    </div>
        <div class="col-md-12 overflow-auto">
            <table class="table table-responsive-sm table-hover table-striped" style="min-height: 200px">
                <thead>
                <tr>
                    <th>@lang('translates.navbar.barcode')</th>
                    <th>@lang('translates.fields.created_at')</th>
                    <th>@lang('translates.fields.clientName')</th>
                    <th>@lang('translates.fields.writtenBy')</th>
                    <th>@lang('translates.columns.evaluation')</th>
                    <th>@lang('translates.fields.subject')</th>
                    <th class="text-center">Status</th>
                    <th>@lang('translates.fields.actions')</th>
                </tr>
                </thead>
                <tbody>
                @forelse($barcodes as $barcode)
                    <tr>
                        @php($status = optional($barcode->getParameter('status')))
                        <td>{{$barcode->getAttribute('code')}}</td>
                        <td>{{$barcode->getAttribute('created_at')->format('d-m-Y')}}</td>
                        <td>{{$barcode->getAttribute('customer')}}</td>
                        <td>{{$barcode->getRelationValue('user')->getAttribute('fullname')}} {!! $barcode->getRelationValue('user')->getAttribute('disabled_at') ? ' <span class="text-danger">(' . __('translates.disabled') . ')</span>' : '' !!}</td>
                        <td>{{optional($barcode->getParameter('evaluation'))->getAttribute('text')}}</td>
                        <td>{{optional($barcode->getParameter('subject'))->getAttribute('text')}}</td>
                        <td class="text-center">
                            @if($barcode->getAttribute('wasDone'))
                                <i class="fa fa-check text-success" style="font-size: 18px"></i>
                            @elseif (auth()->id() != $barcode->getAttribute('user_id') || $status->getAttribute('id') == \App\Models\Barcode::REDIRECTED)
                                {{$status->getAttribute('text') ?? __('translates.filters.select')}}
                            @else
                                    {{$status->getAttribute('text') ?? __('translates.filters.select')}}
                            @endif
                        </td>
                        <td>
                            <div class="btn-sm-group d-flex align-items-center justify-content-center">
                                @if(!$trashBox)
                                    @can('view', $barcode)
                                        <a target="_blank" href="{{route('barcode.show', $barcode)}}"
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
                                        @can('update', $barcode)
                                            <a href="{{route('barcode.edit', $barcode)}}" target="_blank"
                                               class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-pen pr-2 text-success"></i>Edit
                                            </a>
                                        @endcan
                                        @can('delete', $barcode)
                                            <a href="javascript:void(0)"
                                               onclick="deleteAction('{{route('barcode.destroy', $barcode)}}', '{{$barcode->code}}')"
                                               class="dropdown-item-text text-decoration-none">
                                                <i class="fal fa-trash-alt pr-2 text-danger"></i>Delete
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
                                <div class="col-7 alert alert-danger text-center" task="alert">@lang('translates.general.empty')</div>
                            </div>
                        </th>
                    </tr>
                @endforelse

                </tbody>
            </table>
        </div>
        <div class="d-flex">

            <div class="@if(auth()->user()->isDeveloper()) col-9 @else col-12 @endif">
                <div class="float-right">
                    {{$barcodes->appends(request()->input())->links()}}
                </div>
            </div>
        </div>


@endsection

@section('scripts')
    <script>

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
