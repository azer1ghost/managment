@extends('layouts.main')

@section('title', __('translates.navbar.sales_activities'))
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endsection
@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            @lang('translates.navbar.sales_activities')
        </x-bread-crumb-link>
    </x-bread-crumb>
    <form action="{{route('sales-activities.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-5">

                </div>
            </div>

            @can('create', App\Models\SalesActivity::class)
                <div class="col-2">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-sales-activity">@lang('translates.buttons.create')</a>
                </div>
            @endcan

            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.columns.name')</th>
                        <th scope="col">@lang('translates.fields.address')</th>
                        <th scope="col">@lang('translates.fields.clientName')</th>
                        <th scope="col">Activity area</th>
                        <th scope="col">Organization</th>
                        <th scope="col">Certificate</th>
                        <th scope="col">Sales activity</th>
                        <th scope="col">@lang('translates.fields.date')</th>

                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sale_activities as $sale_activity)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$sale_activity->getAttribute('name')}}</td>
                            <td>{{$sale_activity->getAttribute('address')}}</td>
                            <td>{{$sale_activity->getAttribute('client_name')}}</td>
                            <td>{{$sale_activity->getAttribute('activity_area')}}</td>
                            <td>{{$sale_activity->getRelationValue('organization')->getAttribute('name')}}</td>
                            <td>{{$sale_activity->getRelationValue('certificate')->getAttribute('name')}}</td>
                            <td>{{$sale_activity->getRelationValue('salesActivityType')->getAttribute('name')}}</td>
                            <td>{{$sale_activity->getAttribute('datetime')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $sale_activity)
                                        <a href="{{route('sales-activities.show', $sale_activity)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $sale_activity)
                                        <a href="{{route('sales-activities.edit', $sale_activity)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $sale_activity)
                                        <a href="{{route('sales-activities.destroy', $sale_activity)}}" delete data-name="{{$sale_activity->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="15">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="col-12">
                <div class="float-right">
                    {{$sale_activities->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="create-sales-activity">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{route('sales-activities.create')}}">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('translates.general.select_service')</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="data-sales_activity_type">Sales activity type</label>
                            <select class="form-control" id="data-sales_activity_type" name="sales_activity_type_id" required style="width: 100% !important;">
                                @foreach($salesActivitiesTypes as $index => $salesActivitiesType)
                                    <option value="{{$index}}">{{$salesActivitiesType}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="data-client_name">Client name</label>
                            <select class="form-control" id="data-client_name" name="client_name" required style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($clients as $client)
                                    <option value="{{$client->getAttribute('fullname_with_voen')}}">
                                        {{$client->getAttribute('fullname_with_voen')}}
                                    </option>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const clientName = $('#data-client_name');
        clientName.select2({
            tags: true,
            theme: 'bootstrap4',
            focus: true,
        })

        clientName.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endsection