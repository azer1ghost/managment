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
                        <th scope="col">@lang('translates.columns.activity_area')</th>
                        <th scope="col">@lang('translates.columns.organization')</th>
                        <th scope="col">@lang('translates.columns.is_certificate')</th>
                        <th scope="col">@lang('translates.columns.sales_activity')</th>
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
                            <label for="data-sales_activity_type">@lang('translates.filters.sales_activities_type')</label>
                            <select class="form-control" id="data-sales_activity_type" name="sales_activity_type_id" required style="width: 100% !important;">
                                @foreach($salesActivitiesTypes as $index => $salesActivitiesType)
                                    <option value="{{$index}}">{{$salesActivitiesType}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 mt-3 mb-3 pl-0">
                            <label class="d-block" for="clientFilterModal">{{trans('translates.general.select_client')}}</label>
                            <select name="client_id" id="data-client-type" class="client-search-filter" style="width: 100% !important;" required>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>



    <script>
        const clientSearchFilter = $('.client-search-filter');
        clientSearchFilter.select2({
            placeholder: "Search",
            minimumInputLength: 3,
            // width: 'resolve',
            theme: 'bootstrap4',
            focus: true,
            ajax: {
                delay: 500,
                url: '{{route('sales-clients.search')}}',
                dataType: 'json',
                type: 'GET',
                data: function (params) {
                    return {
                        search: params.term,
                    }
                }
            }
        })

        clientSearchFilter.on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });

    </script>
@endsection