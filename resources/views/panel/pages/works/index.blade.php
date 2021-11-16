@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    <form action="{{route('works.index')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div class="col-6">
                <div class="input-group mb-3">
                    <input type="search" name="search" value="{{request()->get('search')}}" class="form-control" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit"><i class="fal fa-search"></i></button>
                        <a class="btn btn-outline-danger" href="{{route('works.index')}}"><i class="fal fa-times"></i></a>
                    </div>
                </div>
            </div>
            @can('create', App\Models\Work::class)
                <div class="col-4">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-work">@lang('translates.buttons.create')</a>
                </div>
            @endcan
            <div class="col-12">
                <table class="table table-responsive-sm table-hover">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">@lang('translates.fields.user')</th>
                        <th scope="col">@lang('translates.columns.department')</th>
                        <th scope="col">@lang('translates.navbar.service')</th>
                        <th scope="col">@lang('translates.fields.clientName')</th>
                        <th scope="col">@lang('translates.fields.detail')</th>
                        <th scope="col">@lang('translates.general.earning')</th>
                        <th scope="col">@lang('translates.general.currency')</th>
                        <th scope="col">@lang('translates.general.currency_rate')</th>
                        <th scope="col">@lang('translates.columns.actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($works as $work)
                        <tr>
                            <th scope="row">{{$loop->iteration}}</th>
                            <td>{{$work->getRelationValue('user')->getAttribute('fullname')}}</td>
                            <td>{{$work->getRelationValue('department')->getAttribute('name')}}</td>
                            <td>{{$work->getRelationValue('service')->getAttribute('name')}}</td>
                            <td>{{$work->getRelationValue('client')->getAttribute('fullname')}}</td>
                            <td>{{$work->getAttribute('detail')}}</td>
                            <td>{{$work->getAttribute('earning')}}</td>
                            <td>{{$work->getAttribute('currency')}}</td>
                            <td>{{$work->getAttribute('currency_rate')}}</td>
                            <td>
                                <div class="btn-sm-group">
                                    @can('view', $work)
                                        <a href="{{route('works.show', $work)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('update', $work)
                                        <a href="{{route('works.edit', $work)}}" class="btn btn-sm btn-outline-success">
                                            <i class="fal fa-pen"></i>
                                        </a>
                                    @endcan
                                    @can('delete', $work)
                                        <a href="{{route('works.destroy', $work)}}" delete data-name="{{$work->getAttribute('name')}}" class="btn btn-sm btn-outline-danger" >
                                            <i class="fal fa-trash"></i>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th colspan="9">
                                <div class="row justify-content-center m-3">
                                    <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                                </div>
                            </th>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-6">
                <div class="float-right">
                    {{$works->appends(request()->input())->links()}}
                </div>
            </div>
        </div>
    </form>
    <div class="modal fade" id="create-work">
        <div class="modal-dialog">
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
                            <label for="data-service">@lang('translates.navbar.service')</label>
                            <select class="select2" id="data-service" name="service_id" required style="width: 100% !important;">
                                <option value="">@lang('translates.general.select_service')</option>
                                @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
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
        $('.select2').select2()
        $('.select2').on('select2:open', function (e) {
            document.querySelector('.select2-search__field').focus();
        });
    </script>
@endsection