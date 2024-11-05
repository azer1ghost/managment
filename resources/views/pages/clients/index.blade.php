@extends('layouts.main')

@section('title', __('translates.navbar.client'))
@section('style')
    <style>

        .modal.right .modal-dialog {
            position: fixed;
            margin: auto;
            width: 50%;
            height: 100%;
            right: 0;
            top: 0;
            bottom: 0;
            transition: transform 0.3s ease-out;
        }

        .modal.right .modal-content {
            height: 100%;
            overflow-y: auto;
        }

        .modal.right.fade .modal-dialog {
            transform: translateX(100%);
        }

        .modal.right.fade.show .modal-dialog {
            transform: translateX(0);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
        }

        .modal-body {
            padding: 10px 15px;
        }

    </style>
    <style>
        .nav-tabs {
            width: 100%;
            display: table;
            table-layout: fixed;
        }
        .nav-item {
            float: none;
            display: table-cell;
            text-align: center;
        }
        .nav-link {
            width: 100%;
        }
    </style>
@endsection
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
                        <select id="data-sales-company" name="company_id"  class="form-control" data-selected-text-format="count"
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
                            <input name="free_coordinator" @if($filters['free_coordinator']) checked @endif type="checkbox" id="coordinator-check">
                            <label class="form-check-label" for="coordinator-check">@lang('translates.filters.free_coordinator')</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <select id="data-sales" name="sale"  class="form-control" data-selected-text-format="count"
                                data-width="fit" title="@lang('translates.clients.selectSales')">
                            <option value=""> @lang('translates.filters.sales') </option>
                            @foreach($sales as $sale)
                                <option
                                        @if($filters['sale'] == $sale->getAttribute('id')) selected @endif
                                value="{{$sale->getAttribute('id')}}">
                                    {{$sale->getAttribute('name')}}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-group ml-2">
                            <input name="free_sale" @if($filters['free_sale']) checked @endif type="checkbox" id="sale-check">
                            <label class="form-check-label" for="sale-check">@lang('translates.filters.free_sale')</label>
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
        <div>
            <div class="tab-content" id="clientTabsContent">
                <div class="tab-pane fade show active" id="group1" role="tabpanel" aria-labelledby="group1-tab">
                    <div class="col-12" style="overflow-x: auto;">
                        <table  class="table table-responsive-sm table-hover">
                            <thead>
                            <tr>
                                @if(auth()->user()->hasPermission('canAssignUsers-client'))
                                    <th><input aria-label="check" type="checkbox" id="clients-all-group1"></th>
                                @endif
                                <th scope="col">#</th>
                                <th scope="col">@lang('translates.columns.type')</th>
                                <th scope="col">@lang('translates.columns.company')</th>
                                <th scope="col">@lang('translates.columns.creator')</th>
                                <th scope="col">@lang('translates.general.coordinator')</th>
                                <th scope="col">@lang('translates.columns.full_name')</th>
                                @if(auth()->user()->hasPermission('viewAll-client'))
                                    <th scope="col">@lang('translates.columns.email')</th>
                                    <th scope="col">@lang('translates.columns.phone')</th>
                                @endif
                                <th scope="col">VOEN/GOOEN</th>
                                <th scope="col">Channel</th>
                                <th scope="col">@lang('translates.navbar.document')</th>
                                    <th scope="col">@lang('translates.columns.sales')</th>
                                    <th scope="col">@lang('translates.columns.created_at')</th>
                                @if(auth()->user()->hasPermission('viewAll-client'))
                                    <th scope="col">@lang('translates.columns.actions')</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($clients as $client)
                                <tr data-toggle="collapse" data-target="#client-demo{{$client->getAttribute('id')}}" class="accordion-toggle" @if(\App\Models\Client::userCanViewAll())
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
                                        <td>
                                            @foreach($client->coordinators as $coordinator)
                                                {{ $coordinator->name }} <br>
                                            @endforeach
                                        </td>
                                    <td>
                                        <label for="data-checkbox-{{$client->getAttribute('id')}}">
                                            {{$client->getAttribute('fullname')}}
                                        </label>
                                    </td>
                                    @if(auth()->user()->hasPermission('viewAll-client'))
                                        <td>{{$client->getAttribute('email1') ? $client->getAttribute('email1') : trans('translates.clients.email_empty')}} </td>
                                        <td>{{$client->getAttribute('phone1') ? $client->getAttribute('phone1') : trans('translates.clients.phone_empty')}} </td>


                                    @endif
                                    <td>{{$client->getAttribute('voen') ? $client->getAttribute('voen') : trans('translates.clients.voen_empty')}} </td>
                                    <td>
                                        @if($client->getAttribute('channel') > 0)
                                            @lang('translates.client_channels.' . $client->getAttribute('channel'))
                                        @elseif($client->inquiries->last())
                                            <p>{{optional($client->inquiries->last()->getParameter('user'))->getAttribute('value')}}</p>
                                            <p>{{optional($client->inquiries->last()->getParameter('digital_channels'))->getAttribute('text')}}</p>
                                            <p>{{optional($client->inquiries->last()->getParameter('traditional_channel'))->getAttribute('text')}}</p>
                                        @endif
                                    </td>
                                    <td style="min-width: 130px">
                                        @php $supportedTypes = \App\Models\Document::supportedTypeIcons() @endphp
                                        @foreach($client->documents as $document)
                                            @php $type = $supportedTypes[$document->type] @endphp
                                            @php $route = $document->type == 'application/pdf' ? route('document.temporaryUrl', $document) : route('document.temporaryViewerUrl', $document) @endphp
                                                <a href="{{$route}}" data-toggle="tooltip" title="{{$document->name}}" target="_blank" class="text-dark" style="word-break: break-word">
                                                    <i class="fa fa-file-{{$type['icon']}} fa-2x text-{{$type['color']}}"></i>
                                                </a>
                                        @endforeach
                                    </td>
                                        <td>
                                            @foreach($client->sales as $sale)
                                                {{ $sale->name }} <br>
                                            @endforeach
                                        </td>
                                    <td>{{$client->getAttribute('created_at')}}</td>
                                    @if(auth()->user()->hasPermission('viewAll-client'))
                                        <td>
                                            <div class="btn-sm-group">
                                                @can('view', $client)
                                                    <a data-toggle="modal" data-target="#clientDetailModal" class="btn btn-sm btn-outline-primary" data-client-id="{{ $client->id }}">
                                                        <i class="fal fa-eye"></i>
                                                    </a>
                                                @endcan

                                                    @if($client->getRelationValue('users')->getAttribute('id') === auth()->id() || in_array($client->department_id, [3, 4, 7]))
                                                    @can('update', $client)
                                                            <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-outline-success">
                                                                <i class="fal fa-pen"></i>
                                                            </a>
                                                    @endcan
                                                    @endif

                                                @can('delete', $client)
                                                    <a href="{{route('clients.destroy', $client)}}" delete data-name="{{$client->getAttribute('fullname')}}" class="btn btn-sm btn-outline-danger">
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
                </div>
            </div>
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
        <button type="button" class="btn btn-outline-primary" id="sum-assign-sales" data-toggle="modal" data-target="#sum-assign-modal-sales">
            @lang('translates.clients.assignSales')
        </button>
        <div class="modal fade" id="sum-assign-modal-sales" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{route('clients.sum.assign-sales')}}" method="POST" id="sum-assign-form-sales">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Assign Coordinator</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="form-group">
                                <label for="data-sales">Select Sales</label><br/>
                                <select id="data-sales" name="users[]" multiple required class="filterSelector form-control" data-selected-text-format="count"
                                        data-width="fit" title="@lang('translates.filters.select')">
                                    @foreach($sales as $sale)
                                        <option value="{{$sale->getAttribute('id')}}">{{$sale->getAttribute('name')}}</option>
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

    <div class="modal right fade" id="clientDetailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title font-weight-bolder" id="clientDetailModalLabel">Client Name</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img src="{{asset('assets/images/logos/broker.png')}}" alt="Logo" class="img-fluid my-2" style="max-width: 100px;">
                        <h6 class="mt-2">client.email@mail.com</h6>
                    </div>
                    <div class="d-flex justify-content-around mb-4 nav" id="clientDetailTab" role="tablist">
                        <a id="sendEmail" href="#" class="btn btn-outline-secondary"><i class="fas fa-envelope"></i> Email</a>
                        <a id="call" class="btn btn-outline-secondary"><i class="fas fa-phone"></i> Call</a>
                        <button class="active tab btn btn-outline-secondary" id="detailNote-tab" data-toggle="tab" href="#detailNote" role="tab" aria-controls="detailNote" aria-selected="true">
                            <i class="fas fa-sticky-note"></i> Note
                        </button>
                        <button class="btn btn-outline-secondary" id="detailWorks-tab" data-toggle="tab" href="#detailWorks" role="tab" aria-controls="detailWorks" aria-selected="false">
                            <i class="fas fa-tasks"></i> Works
                        </button>
                        <button class="btn btn-outline-secondary" id="detailInquiries-tab" data-toggle="tab" href="#detailInquiries" role="tab" aria-controls="detailInquiries" aria-selected="false">
                            <i class="fas fa-phone-office"></i> Inquiries
                        </button>
                        <button class="btn btn-outline-secondary" id="detailEmployees-tab" data-toggle="tab" href="#detailEmployees" role="tab" aria-controls="detailEmployees" aria-selected="false">
                            <i class="fas fa-user"></i> Employees
                        </button>
                    </div>
                    <div>
                        <h6 class="font-weight-bold text-center">About This Contact</h6>
                        <p><b>Companies: </b><span id="companiesSection"></span></p>
                        <p><b>Voen: </b><span id="clientDetailVoen"></span><button class="btn btn-xs"><i class="fas fa-copy"></i></button></p>
                        <p><b>Email: </b><a id="clientDetailEmail" class="text-black">--</a></p>
                        <p><b>Phone number: </b><a id="clientDetailPhone" class="text-black">--</a></p>
                        <p><b>Address: </b><a id="clientDetailAddress" class="text-black">--</a></p>
                    </div>
                    <div>
                        <h6 class="font-weight-bold">Documents</h6>
                        <div id="documentsSection"></div>
                    </div>
                    <div class="tab-content" id="clientDetailTab">
                        <div class="tab-pane fade show active" id="detailNote" role="tabpanel" aria-labelledby="detailNote-tab">
                            <div class="form-group">
                                <textarea name="" class="form-control" id="clientDetailNote" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="detailWorks" role="tabpanel" aria-labelledby="detailWorks-tab">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Service</th>
                                    <th scope="col">Status</th>
                                </tr>
                                </thead>
                                <tbody id="worksTableBody"></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="detailInquiries" role="tabpanel" aria-labelledby="detailInquiries-tab">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Created_at</th>
                                </tr>
                                </thead>
                                <tbody id="inquiriesTableBody"></tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="detailEmployees" role="tabpanel" aria-labelledby="detailEmployees-tab">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                </tr>
                                </thead>
                                <tbody id="subClientsTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var target = $(e.target).attr("href"); // activated tab
            $(target).addClass("show active");
        });
        $('#clientDetailModal').on('show.bs.modal', function (event) {
            let button = $(event.relatedTarget);
            let clientId = button.data('client-id');
            let url = "{{ route('clients.show', ':id') }}";
            url = url.replace(':id', clientId);

            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $('#clientDetailModalLabel').html(data.client.fullname);
                    $('#clientDetailVoen').html(data.client.voen)
                    $('#clientDetailNote').html(data.client.detail)
                    $('#clientDetailAddress').html(data.client.address1)
                    let email = $('#clientDetailEmail')
                    let phone = $('#clientDetailPhone')
                    email.html(data.client.email1)
                    phone.html(data.client.phone1)
                    $('#sendEmail').attr('href', 'mailto:' + data.client.email1);
                    $('#call').attr('href', 'tel:' + data.client.phone1);
                    email.attr('href', 'mailto:' + data.client.email1);
                    phone.attr('href', 'tel:' + data.client.phone1);

                    var worksHtml = '';
                    if (data.works && data.works.length > 0) {
                        data.works.forEach(function(work) {
                            worksHtml += '<tr>';
                            worksHtml += '<th scope="row">' + (work.id) + '</th>';
                            worksHtml += '<td>' + (work.user ? work.user.name : '-') + '</td>';
                            worksHtml += '<td>' + (work.service ? work.service.name['az'] : '-') + '</td>';
                            worksHtml += '<td>' + work.status + '</td>';
                            worksHtml += '</tr>';
                        });
                    } else {
                        worksHtml = '<tr><td colspan="4">No works found for this client.</td></tr>';
                    }
                    $('#worksTableBody').html(worksHtml);

                    var inquiriesHtml = '';
                    if (data.inquiries && data.inquiries.length > 0) {
                        data.inquiries.forEach(function(inquiries) {
                            inquiriesHtml += '<tr>';
                            inquiriesHtml += '<th scope="row">' + (inquiries.id) + '</th>';
                            inquiriesHtml += '<td>' + (inquiries.user ? inquiries.user.name : '-') + '</td>';
                            inquiriesHtml += '<td>' + inquiries.created_at + '</td>';
                            inquiriesHtml += '</tr>';
                        });
                    } else {
                        inquiriesHtml = '<tr><td colspan="4">No works found for this client.</td></tr>';
                    }
                    $('#inquiriesTableBody').html(inquiriesHtml);

                    var subClientsHtml = '';
                    if (data.subClients && data.subClients.length > 0) {
                        data.subClients.forEach(function(subClients) {
                            subClientsHtml += '<tr>';
                            subClientsHtml += '<th scope="row">' + (subClients.id) + '</th>';
                            subClientsHtml += '<td>' + (subClients.fullname) + '</td>';
                            subClientsHtml += '<td>' + (subClients.position) + '</td>';
                            subClientsHtml += '<td>' + (subClients.email1) + '</td>';
                            subClientsHtml += '<td>' + (subClients.phone1) + '</td>';
                            subClientsHtml += '</tr>';
                        });
                    } else {
                        subClientsHtml = '<tr><td colspan="4">No works found for this client.</td></tr>';
                    }
                    $('#subClientsTableBody').html(subClientsHtml);

                    var documentsHtml = '';
                    if (data.documents && data.documents.length > 0) {
                        data.documents.forEach(function(document) {
                            documentsHtml += '<a href="' + document.url + '" data-toggle="tooltip" title="' + document.name + '" target="_blank" class="text-dark" style="word-break: break-word">';
                            documentsHtml += '<i class="fa fa-file-' + document.icon + ' fa-2x text-' + document.color + '"></i>';
                            documentsHtml += '</a> ';
                        });
                    } else {
                        documentsHtml = '<p>No documents found for this client.</p>';
                    }
                    $('#documentsSection').html(documentsHtml);

                    var companiesHtml = '';
                    if (data.companies && data.companies.length > 0) {
                        companiesHtml = data.companies.join(', ');
                    } else {
                        companiesHtml = 'No companies found for this client.';
                    }
                    $('#companiesSection').html(companiesHtml);
                },
                error: function () {
                    $('#clientDetailContent').html('<p>An error occurred while fetching the data.</p>');
                }
            });
        });
    </script>
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
        $('#sum-assign-form-sales').submit(function (e){
            e.preventDefault();
            const checkedClients = [];
            $("input[name='clients[]']:checked").each(function(){
                checkedClients.push($(this).val());
            });

            const params = new URLSearchParams({
                clients: checkedClients,
            });

            $('#sum-assign-modal-sales').modal('hide');

            $.ajax({
                type: "POST",
                url: '{{route('clients.sum.assign-sales')}}',
                data: $('#sum-assign-form-sales').serialize() + "&" + params.toString(),
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