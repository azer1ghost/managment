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
                @if(\App\Models\Client::userCanViewAll())
                    <div class="col-md-4">
                        <div class="form-group">
                            <select id="data-sales-users" name="salesClient"  class="filterSelector form-control" data-selected-text-format="count"
                                    data-width="fit" title="@lang('translates.clients.selectUser')">
                                @foreach($salesClients as $salesClient)
                                    <option
                                        @if($filters['salesClient'] == $salesClient->getAttribute('id')) selected @endif  value="{{$salesClient->getAttribute('id')}}">
                                        {{$salesClient->getAttribute('fullname')}}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-group ml-2 form-check">
                                <input name="free_clients" @if($filters['free_clients']) checked @endif type="checkbox" class="form-check-input" id="exampleCheck1">
                                <label class="form-check-label" for="exampleCheck1">@lang('translates.filters.free_clients')</label>
                            </div>
                        </div>
                    </div>


                @endif

                <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="submit" class="btn btn-outline-primary"><i
                                    class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                        <a href="{{route('clients.index')}}" class="btn btn-outline-danger"><i
                                    class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                    </div>
                </div>

                <div class="col-8 pt-2 d-flex align-items-center">
                    <p class="mb-0"> @lang('translates.total_items', ['count' => $clients->count(), 'total' => $clients->total()])</p>
                    <div class="input-group col-md-3">
                        <select name="limit" class="custom-select" id="size">
                            @foreach([25, 50, 100, 250, 500] as $size)
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
                </div>
                </div>

                <div class="col-12">
                    <table class="table table-responsive-sm table-hover">
                        <thead>
                        <tr>
                            @if(auth()->user()->hasPermission('canAssignUsers-client'))
                                <th><input type="checkbox" id="clients-all"></th>
                            @endif
                            <th scope="col">#</th>
                            <th scope="col">@lang('translates.columns.type')</th>
                            <th scope="col">@lang('translates.columns.full_name')</th>
                            <th scope="col">@lang('translates.fields.detail')</th>
                            <th scope="col">@lang('translates.columns.email')</th>
                            <th scope="col">@lang('translates.columns.phone')</th>
                            <th scope="col">VOEN/GOOEN</th>
                            <th scope="col">@lang('translates.columns.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($clients as $client)
                            <tr @if(\App\Models\Client::userCanViewAll())
                                    title="@foreach($client->salesUsers as $user) {{$user->getAttribute('fullname')}} @if(!$loop->last),@endif @endforeach"
                                    data-toggle="tooltip"
                                @endif
                                @if(!$client->salesUsers()->exists())
                                    style="background: #eed58f"
                                @endif
                            >
                                @if(auth()->user()->hasPermission('canAssignUsers-client'))
                                    <td><input type="checkbox" name="clients[]" value="{{$client->getAttribute('id')}}" id="data-checkbox-{{$client->getAttribute('id')}}"></td>
                                @endif
                                <th scope="row">{{$client->getAttribute('id')}}</th>
                                <td>@lang("translates.clients_type." . $client->getAttribute('type'))</td>
                                <td><label for="data-checkbox-{{$client->getAttribute('id')}}">{{$client->getAttribute('fullname')}}</label></td>
                                <td>{{$client->getAttribute('detail') ? $client->getAttribute('detail') : trans('translates.clients.detail_empty') }} </td>
                                <td>{{$client->getAttribute('email1') ? $client->getAttribute('email1') : trans('translates.clients.email_empty')}} </td>
                                <td>{{$client->getAttribute('phone1') ? $client->getAttribute('phone1') : trans('translates.clients.phone_empty')}} </td>
                                <td>{{$client->getAttribute('voen') ? $client->getAttribute('voen') : trans('translates.clients.voen_empty')}} </td>
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
                    <div class="float-right">
                        {{$clients->appends(request()->input())->links()}}
                    </div>
                </div>
    </form>
    @if(auth()->user()->hasPermission('canAssignUsers-client'))
        <button type="button" class="btn btn-outline-primary" id="sum-assign-sales" data-toggle="modal" data-target="#sum-assign-modal">
            @lang('translates.clients.assignUser')
        </button>
        <div class="modal fade" id="sum-assign-modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('clients.sum.assign-sales')}}" method="POST" id="sum-assign-form">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Assign sales users</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                @csrf
                                <div class="form-group">
                                    <label for="data-sales-users">Select sales users</label><br/>
                                    <select id="data-sales-users" name="users[]" multiple required class="filterSelector form-control" data-selected-text-format="count"
                                            data-width="fit" title="@lang('translates.filters.select')">
                                        @foreach($salesUsers as $salesUser)
                                            <option value="{{$salesUser->getAttribute('id')}}">{{$salesUser->getAttribute('fullname')}}</option>
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
                $('#sum-assign-sales').attr('disabled', false);
            } else {
                clientsCheckbox.map(function () {
                    $(this).prop('checked', false)
                });
                $('#sum-assign-sales').attr('disabled', true);
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
                $('#sum-assign-sales').attr('disabled', false);
            } else {
                $('#sum-assign-sales').attr('disabled', true);
            }
        }

        $('#sum-assign-form').submit(function (e){
            e.preventDefault();
            const checkedClients = [];
            $("input[name='clients[]']:checked").each(function(){
                checkedClients.push($(this).val());
            });

            const params = new URLSearchParams({
                clients: checkedClients,
            });

            $('#sum-assign-modal').modal('hide');

            $.ajax({
                type: "POST",
                url: '{{route('clients.sum.assign-sales')}}',
                data: $('#sum-assign-form').serialize() + "&" + params.toString(),
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