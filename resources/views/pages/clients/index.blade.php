@extends('layouts.main')

@section('title', __('translates.navbar.client'))

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link is-current="1">
            @lang('translates.navbar.client')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('clients.index')}}">
        <div class="row mb-2">
            <div class="col-md-4">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{$filters['search']}}" class="form-control" placeholder="@lang('translates.fields.enter', ['field' => trans('translates.fields.client')])" aria-label="Recipient's clientname" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{route('clients.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <select name="type" class="custom-select" id="type">
                    @foreach($types as $key => $type)
                        <option @if($filters['type'] === "$key") selected @endif value="{{$key}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="active" class="custom-select" id="active">
                    @foreach($actives as $key => $type)
                        <option @if($filters['active'] === "$key") selected @endif value="{{$key}}">{{$type}}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <select aria-label="user" name="users" id="userFilter" class="form-control" >
                    <option value="">@lang('translates.columns.user') @lang('translates.filters.select')</option>
                    @foreach($users as $user)
                        <option value="{{$user->getAttribute('id')}}"
                                @if($user->getAttribute('id') == $filters['users']) selected @endif>
                            {{$user->getAttribute('fullname')}}
                        </option>
                    @endforeach
                </select>
            </div>

            @if(\App\Models\Client::userCanViewAll())
                <div class="col-md-4">
                    <div class="form-group">
                        <select id="data-sales-company" name="company"  class="form-control" data-selected-text-format="count"
                                data-width="fit" title="@lang('translates.clients.selectCompany')">
                            <option value=""> @lang('translates.filters.company') </option>
                            @foreach($companies as $company)
                                <option
                                        @if($filters['company'] == $company->getAttribute('id')) selected @endif
                                value="{{$company->getAttribute('id')}}">
                                    {{$company->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-group ml-2">
                            <input name="free_company" @if($filters['free_company']) checked @endif type="checkbox" id="exampleCheck2">
                            <label class="form-check-label" for="exampleCheck2">@lang('translates.filters.free_company')</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select id="data-sales-coordinator" name="coordinator"  class="form-control" data-selected-text-format="count"
                                data-width="fit" title="@lang('translates.clients.selectCoordinator')">
                            <option value=""> @lang('translates.filters.coordinator') </option>
                            @foreach($coordinators as $coordinator)
                                <option
                                        @if($filters['coordinator'] == $coordinator->getAttribute('id')) selected @endif
                                value="{{$coordinator->getAttribute('id')}}">
                                    {{$coordinator->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-group ml-2">
                            <input name="free_coordinator" @if($filters['free_coordinator']) checked @endif type="checkbox" id="exampleCheck2">
                            <label class="form-check-label" for="exampleCheck2">@lang('translates.filters.free_coordinator')</label>
                        </div>
                    </div>
                </div>
            @endif

            <div class="form-group col-12 col-md-3">
                <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
                <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
            </div>

            <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="submit" class="btn btn-outline-primary"><i
                                class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                    <a href="{{route('clients.index')}}" class="btn btn-outline-danger"><i
                                class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                </div>
            </div>

            <div class="col-8 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $clients->count(), 'total' => is_numeric($filters['limit']) ? $clients->total() : $clients->count()])</p>
                <div class="input-group col-md-3">
                    <select name="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250, 500, trans('translates.general.all')] as $size)
                            <option @if($filters['limit'] == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-4 p-0 pr-3 pb-3 mt-4">
                @can('create', App\Models\Client::class)
                    <a class="btn btn-outline-success float-right " href="{{route('clients.create', ['type' => \App\Models\Client::LEGAL])}}">@lang('translates.buttons.create')</a>
                @endcan
                @if(auth()->user()->hasPermission('canExport-client'))
                    <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('clients.export', ['filters' => json_encode($filters)])}}">@lang('translates.buttons.export')</a>
                @endif
                @can('create', \App\Models\Questionnaire::class)
                    <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('questionnaires.index')}}">@lang('translates.navbar.questionnaire')</a>
                @endif
            </div>
        </div>

        <div class="col-12">
            <table class="table table-responsive-sm table-hover">
                <thead>
                    <tr>
                        @if(auth()->user()->hasPermission('canAssignUsers-client'))
                            <th><input aria-label="check" type="checkbox" id="clients-all"></th>
                        @endif
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.type')</th>
                        <th scope="col">@lang('translates.columns.company')</th>
                        <th scope="col">@lang('translates.columns.creator')</th>
                        <th scope="col">@lang('translates.navbar.reference')</th>
                        <th scope="col">Kanal</th>
                        <th scope="col">@lang('translates.columns.full_name')</th>
                            @if(auth()->user()->hasPermission('viewAll-client'))
                                <th scope="col">@lang('translates.fields.detail')</th>
                                <th scope="col">@lang('translates.columns.email')</th>
                                <th scope="col">@lang('translates.columns.phone')</th>
                            @endif
                            <th scope="col">VOEN/GOOEN</th>
                            <th scope="col">@lang('translates.navbar.document')</th>
                            @if(auth()->user()->hasPermission('viewAll-client'))
                                <th scope="col">@lang('translates.columns.actions')</th>
                            @endif
                    </tr>
                    </thead>
                <tbody>
                    @forelse($clients as $client)
{{--                        @dd()--}}
                        <tr @if(\App\Models\Client::userCanViewAll())
                                title="@foreach($client->coordinators as $user) {{$user->getAttribute('fullname')}} @if(!$loop->last),@endif @endforeach"
                                data-toggle="tooltip"
                            @endif>
                            @if(auth()->user()->hasPermission('canAssignUsers-client'))
                                <td><input type="checkbox" name="clients[]" value="{{$client->getAttribute('id')}}" id="data-checkbox-{{$client->getAttribute('id')}}"></td>
                            @endif
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>@lang("translates.clients_type." . $client->getAttribute('type'))</td>
                                <td>@foreach($client->companies as $company) {{$company->getAttribute('name')}} @if(!$loop->last),@endif @endforeach</td>
                                <td>{{$client->getRelationValue('users')->getAttribute('id') ? $client->getRelationValue('users')->getAttribute('fullname_with_position') : 'Toğrul Surxayzadə-(Hüquqşünas)'}}</td>
                                <td>{{\App\Models\CustomerEngagement::where('client_id', $client->id)->first() ? \App\Models\CustomerEngagement::where('client_id', $client->id)->first()->getRelationValue('user')->getAttribute('fullname_with_position') : 'Birbaşa'}}</td>
                                <td>
                                    <span class="" style="">
                                         {{trans('translates.client_channels.' . $client->getAttribute('channel'))}}
                                    </span>
                                </td>
                                <td><label for="data-checkbox-{{$client->getAttribute('id')}}">{{$client->getAttribute('fullname')}}</label></td>
                                    @if(auth()->user()->hasPermission('viewAll-client'))
                                        <td>{{$client->getAttribute('detail') ? $client->getAttribute('detail') : trans('translates.clients.detail_empty') }} </td>
                                        <td>{{$client->getAttribute('email1') ? $client->getAttribute('email1') : trans('translates.clients.email_empty')}} </td>
                                        <td>{{$client->getAttribute('phone1') ? $client->getAttribute('phone1') : trans('translates.clients.phone_empty')}} </td>
                                   @endif
                                <td>{{$client->getAttribute('voen') ? $client->getAttribute('voen') : trans('translates.clients.voen_empty')}} </td>
                                <td>
                                        @php($supportedTypes = \App\Models\Document::supportedTypeIcons())
                                        @foreach($client->documents as $document)
                                            @php($type = $supportedTypes[$document->type])
                                            @php($route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document))
                                            <a href="{{$route}}" data-toggle="tooltip" title="{{$document->file}}" target="_blank" class="text-dark d-flex align-items-center mr-2" style=" word-break: break-word">
                                                <i class="fa fa-file-{{$type['icon']}} fa-2x m-1 text-{{$type['color']}}"></i>
                                                <span>{{substr($document->name, 0, 10) . '...'}} </span>
                                            </a>
                                        @endforeach
                                    </td>
                            @if(auth()->user()->hasPermission('viewAll-client'))
                                <td>
                                    <div class="btn-sm-group">
                                        @can('view', $client)
                                            <a href="{{route('clients.show', $client)}}" class="btn btn-sm btn-outline-primary">
                                                <i class="fal fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('update', $client)
                                            <a href="{{route('clients.edit', $client)}}" class="btn btn-sm btn-outline-success">
                                                <i class="fal fa-pen"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $client)
                                            <a href="{{route('clients.destroy', $client)}}" delete data-name="{{$client->getAttribute('fullname')}}" class="btn btn-sm btn-outline-danger" >
                                                <i class="fal fa-trash"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <th colspan="20">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.not_found')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
            </table>
        </div>

        <div class="col-12">
            @if(is_numeric($filters['limit']))
                <div class="float-right">
                    {{$clients->appends(request()->input())->links()}}
                </div>
            @endif
        </div>
    </form>
    @if(auth()->user()->hasPermission('canAssignUsers-client'))
        <button type="button" class="btn btn-outline-primary" id="sum-assign-companies" data-toggle="modal" data-target="#sum-assign-modal-companies">
            @lang('translates.clients.assignCompany')
        </button>
        <div class="modal fade" id="sum-assign-modal-companies" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('clients.sum.assign-companies')}}" method="POST" id="sum-assign-form-companies">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Assign Company</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <label for="data-companies">Select Company</label><br/>
                                    <select id="data-companies" name="companies[]" multiple required class="filterSelector form-control" data-selected-text-format="count"
                                            data-width="fit" title="@lang('translates.filters.select')">
                                        @foreach($companies as $company)
                                            <option value="{{$company->getAttribute('id')}}">{{$company->getAttribute('name')}}</option>
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

        <button type="button" class="btn btn-outline-primary" id="sum-assign-coordinators" data-toggle="modal" data-target="#sum-assign-modal-coordinators">
            @lang('translates.clients.assignCoordinator')
        </button>
        <div class="modal fade" id="sum-assign-modal-coordinators" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('clients.sum.assign-coordinators')}}" method="POST" id="sum-assign-form-coordinators">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Assign Coordinator</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <label for="data-coordinators">Select Coordinators</label><br/>
                                    <select id="data-coordinators" name="users[]" multiple required class="filterSelector form-control" data-selected-text-format="count"
                                            data-width="fit" title="@lang('translates.filters.select')">
                                        @foreach($coordinators as $coordinator)
                                            <option value="{{$coordinator->getAttribute('id')}}">{{$coordinator->getAttribute('name')}}</option>
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
    @endif
@endsection
@section('scripts')
    <script>

        const clientsCheckbox = $("input[name='clients[]']");
        $('#clients-all').change(function () {
            if ($(this).is(':checked')) {
                clientsCheckbox.map(function () {
                    $(this).prop('checked', true)
                });
                $('#sum-assign-companies').attr('disabled', false);
            } else {
                clientsCheckbox.map(function () {
                    $(this).prop('checked', false)
                });
                $('#sum-assign-companies').attr('disabled', true);
            }
        });

        // Check if at least one inquiry selected
        clientsCheckbox.change(function () {
            checkUnverifiedWorks();
        });

        checkUnverifiedWorks();

        function checkUnverifiedWorks(){
            let hasOneChecked = false;
            clientsCheckbox.map(function () {
                if ($(this).is(':checked')) {
                    hasOneChecked = true;
                }
            });
            if (hasOneChecked) {
                $('#sum-assign-companies').attr('disabled', false);
            } else {

                $('#sum-assign-companies').attr('disabled', true);
            }
        }
        $('#sum-assign-form-companies').submit(function (e){
            e.preventDefault();
            const checkedClients = [];
            $("input[name='clients[]']:checked").each(function(){
                checkedClients.push($(this).val());
            });

            const params = new URLSearchParams({
                clients: checkedClients,
            });

            $('#sum-assign-modal-companies').modal('hide');

            $.ajax({
                type: "POST",
                url: '{{route('clients.sum.assign-companies')}}',
                data: $('#sum-assign-form-companies').serialize() + "&" + params.toString(),
                success: function() {
                    $.confirm({
                        title: 'Successful',
                        icon: 'fa fa-check',
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
                error: function (err) {
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
        });
        function checkUnverifiedWorks(){
            let hasOneChecked = false;
            clientsCheckbox.map(function () {
                if ($(this).is(':checked')) {
                    hasOneChecked = true;
                }
            });
            if (hasOneChecked) {
                $('#sum-assign-coordinators').attr('disabled', false);
            } else {

                $('#sum-assign-coordinators').attr('disabled', true);
            }
        }
        $('#sum-assign-form-coordinators').submit(function (e){
            e.preventDefault();
            const checkedClients = [];
            $("input[name='clients[]']:checked").each(function(){
                checkedClients.push($(this).val());
            });

            const params = new URLSearchParams({
                clients: checkedClients,
            });

            $('#sum-assign-modal-coordinators').modal('hide');

            $.ajax({
                type: "POST",
                url: '{{route('clients.sum.assign-coordinators')}}',
                data: $('#sum-assign-form-coordinators').serialize() + "&" + params.toString(),
                success: function() {
                    $.confirm({
                        title: 'Successful',
                        icon: 'fa fa-check',
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
                error: function (err) {
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
        });
    </script>
@endsection