@extends('layouts.main')

@section('title', __('translates.navbar.work'))
@section('style')
    <style>
        .table td, .table th{
            vertical-align: middle !important;
        }
        .table tr {
            cursor: pointer;
        }
        .hiddenRow {
            padding: 0 4px !important;
            background-color: #eeeeee;
            font-size: 13px;
        }
        .table{
            overflow-x: scroll;
        }
    </style>
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

    <button class="btn btn-outline-success showFilter">
        <i class="far fa-filter"></i> @lang('translates.buttons.filter_open')
    </button>

    <form action="{{route('pending-works')}}">
        <div class="row d-flex justify-content-between mb-2">
            <div id="filterContainer" class="mb-3" @if(request()->has('datetime')) style="display:block;" @else style="display:none;" @endif>
                <div class="col-12">
                    <div class="row m-0">
                        @if(\App\Models\Work::userCanViewAll())
                            <div class="form-group col-12 col-md-3 my-3 pl-0">
                                <label class="d-block" for="departmentFilter">{{__('translates.general.department_select')}}</label>
                                <select id="departmentFilter" class="select2"
                                        name="department_id"
                                        data-width="fit" title="{{__('translates.filters.select')}}"
                                        @if(\App\Models\Work::userCannotViewAll()) disabled @endif>
                                    <option value="">@lang('translates.filters.select')</option>
                                    @foreach($departments as $department)
                                        <option
                                                @if($department->getAttribute('id') == $filters['department_id']) selected @endif
                                        value="{{$department->getAttribute('id')}}"
                                        >
                                            {{ucfirst($department->getAttribute('name'))}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        @if(\App\Models\Work::userCanViewAll() || \App\Models\Work::userCanViewDepartmentWorks())
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
                        @endif

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
                                    <option value="{{$filters['client_id']}}">{{\App\Models\Client::find($filters['client_id'])->getAttribute('fullname_with_voen')}}</option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="asanCompanyFilter">Asan Imza @lang('translates.columns.company')</label>
                            <select name="asan_imza_company_id" id="asanCompanyFilter" class="select2" data-width="fit" style="width: 100% !important;">
                                <option value="">@lang('translates.filters.select')</option>
                                @foreach($companies as $company)
                                    <option
                                            @if($company->getAttribute('id') == $filters['asan_imza_company_id']) selected @endif
                                    value="{{$company->getAttribute('id')}}"
                                    >
                                        {{$company->getAttribute('name')}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="asanUserFilter">Asan Imza @lang('translates.columns.user')</label>
                            <select name="asan_imza_id" id="asanUserFilter" class="custom-select2" style="width: 100% !important;" data-url="{{route('asanImza.user.search')}}">
                                @if(is_numeric($filters['asan_imza_id']))
                                    @php
                                        $asanUser = \App\Models\AsanImza::find($filters['asan_imza_id']);
                                    @endphp
                                    <option value="{{$filters['asan_imza_id']}}">
                                        {{$asanUser->getRelationValue('user')->getAttribute('fullname')}}
                                        ({{$asanUser->getRelationValue('company')->getAttribute('name')}})
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3 mt-3 mb-3 pl-0">
                            <label class="d-block" for="createdAtFilter">{{trans('translates.fields.created_at')}}</label>
                            <input class="form-control custom-daterange mb-1" id="createdAtFilter" type="text" readonly name="created_at" value="{{$filters['created_at']}}">
                            <input type="checkbox" name="check-created_at" id="check-created_at" @if(request()->has('check-created_at')) checked @endif> <label for="check-created_at">@lang('translates.filters.filter_by')</label>
                        </div>


                        <div class="col-12 mt-3 mb-5 d-flex align-items-center justify-content-end">
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="submit" class="btn btn-outline-primary"><i
                                            class="fas fa-filter"></i> @lang('translates.buttons.filter')</button>
                                <a href="{{route('pending-works')}}" class="btn btn-outline-danger"><i
                                            class="fal fa-times-circle"></i> @lang('translates.filters.clear')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 pt-2 d-flex align-items-center">
                <p class="mb-0"> @lang('translates.total_items', ['count' => $works->count(), 'total' => is_numeric($filters['limit']) ? $works->total() : $works->count()])</p>
                <div class="input-group col-md-3">
                    <select name="limit" class="custom-select" id="size">
                        @foreach([25, 50, 100, 250, 500] as $size)
                            <option @if($filters['limit'] == $size) selected @endif value="{{$size}}">{{$size}}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            @can('create', App\Models\Work::class)
                <div class="col-sm-6 py-3">
                    <a class="btn btn-outline-success float-right" data-toggle="modal" data-target="#create-work">@lang('translates.buttons.create')</a>
                    @if(auth()->user()->hasPermission('canRedirect-work'))
                        <a class="btn btn-outline-primary float-right mr-sm-2" href="{{route('works.export', [
                            'filters' => json_encode($filters),
                            'dateFilters' => json_encode($dateFilters)
                            ])}}">
                            @lang('translates.buttons.export')
                        </a>
                    @endif
                </div>
            @endcan

        </div>
    </form>

    @if(is_numeric($filters['limit']))
        <div class="col-12 mt-2">
            <div class="float-right">
                {{$works->appends(request()->input())->links()}}
            </div>
        </div>
    @endif
    <table class="table table-condensed-table-responsive @if($works->count()) table-responsive-md @else table-responsive-sm @endif" style="border-collapse:collapse;"  id="table">
        <thead>
        <tr class="text-center">
            <th><input type="checkbox" id="works-all"></th>
            <th scope="col">@lang('translates.fields.mark')</th>
            <th scope="col">@lang('translates.columns.created_by')</th>
            <th scope="col">@lang('translates.columns.department')</th>
            <th scope="col">@lang('translates.fields.user')</th>
            <th scope="col">Asan imza</th>
            <th scope="col">@lang('translates.navbar.service')</th>
            <th scope="col">@lang('translates.fields.clientName')</th>
            <th scope="col">Status</th>
            <th scope="col">@lang('translates.columns.created_at')</th>
        </tr>
        </thead>
        <tbody>
        @forelse($works as $work)

            @if($work->isDone() && is_null($work->getAttribute('verified_at')))
                @php
                    $hasPending = true;
                @endphp
            @endif
            <tr data-toggle="collapse" data-target="#demo{{$work->getAttribute('id')}}" class="accordion-toggle" @if(is_null($work->getAttribute('user_id'))) style="background: #eed58f" @endif title="{{$work->getAttribute('code')}}">

                <td><input type="checkbox" name="works[]" value="{{$work->getAttribute('id')}}"></td>
                <th style="font-weight:bold">{{$work->getAttribute('mark')}}</th>
                <td>{{$work->getRelationValue('creator')->getAttribute('fullname_with_position')}}</td>

                <td>{{$work->getRelationValue('department')->getAttribute('short')}}</td>

                <td>
                    @if(is_numeric($work->getAttribute('user_id')))
                        {{$work->getRelationValue('user')->getAttribute('fullname_with_position')}}
                    @else
                        @lang('translates.navbar.general')
                    @endif
                </td>
                <td>{{$work->asanImza()->exists() ? $work->getRelationValue('asanImza')->getAttribute('user_with_company') : trans('translates.filters.select')}}</td>
                <td><i class="{{$work->getRelationValue('service')->getAttribute('icon')}} pr-2" style="font-size: 20px"></i> {{$work->getRelationValue('service')->getAttribute('name')}}</td>
                <td data-toggle="tooltip" data-placement="bottom" title="{{$work->getRelationValue('client')->getAttribute('fullname')}}" >
                    {{mb_strimwidth($work->getRelationValue('client')->getAttribute('fullname'), 0, 20, '...')}}
                </td>

                <td>
                    <span class="badge badge-warning" style="font-size: 12px">
                         {{trans('translates.work_status.' . $work->getAttribute('status'))}}
                    </span>
                </td>
                <td title="{{optional($work->getAttribute('created_at'))->diffForHumans()}}" data-toggle="tooltip">{{$work->getAttribute('created_at')}}</td>
                <td>
                    <div class="btn-sm-group d-flex align-items-center">
                        @if($work->getAttribute('creator_id') != auth()->id() && is_null($work->getAttribute('user_id')) && !auth()->user()->isDeveloper())
                            @can('update', $work)
                                <a title="@lang('translates.buttons.execute')" data-toggle="tooltip" href="{{route('works.edit', $work)}}"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="fal fa-arrow-right"></i>
                                </a>
                            @endcan
                        @endif
                        <div class="dropdown">
                            <button class="btn" type="button" id="inquiry_actions-{{$loop->iteration}}"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fal fa-ellipsis-v-alt"></i>
                            </button>
                            <div class="dropdown-menu custom-dropdown">
                                @can('view', $work)
                                    <a href="{{route('works.show', $work)}}" class="dropdown-item-text text-decoration-none">
                                        <i class="fal fa-eye pr-2 text-primary"></i>@lang('translates.buttons.view')
                                    </a>
                                @endcan
                                @if(auth()->user()->hasPermission('update-work') || $work->getAttribute('creator_id') == auth()->id() || $work->getAttribute('user_id') == auth()->id() || auth()->user()->isDeveloper() )
                                    @can('update', $work)
                                        <a href="{{route('works.edit', $work)}}" class="dropdown-item-text text-decoration-none">
                                            @if($work->getAttribute('creator_id') == auth()->id() || auth()->user()->isDeveloper() || auth()->user()->hasPermission('update-work'))
                                                <i class="fal fa-pen pr-2 text-success"></i>@lang('translates.tasks.edit')
                                            @elseif($work->getAttribute('user_id') == auth()->id())
                                                <i class="fal fa-arrow-right pr-2 text-success"></i>@lang('translates.buttons.execute')
                                            @endif
                                        </a>
                                    @endcan
                                @endif
                                @if(auth()->user()->hasPermission('canVerify-work') && $work->getAttribute('status') == $work::DONE && is_null($work->getAttribute('verified_at')))
                                    <a href="{{route('works.verify', $work)}}" verify data-name="{{$work->getAttribute('code')}}" class="dropdown-item-text text-decoration-none">
                                        <i class="fal fa-check pr-2 text-success"></i>@lang('translates.buttons.verify')
                                    </a>
                                @endif
                                @can('delete', $work)
                                    <a href="{{route('works.destroy', $work)}}" delete data-name="{{$work->getAttribute('code')}}" class="dropdown-item-text text-decoration-none">
                                        <i class="fal fa-trash pr-2 text-danger"></i>@lang('translates.tasks.delete')
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
                        <div class="col-7 alert alert-danger text-center" role="alert">@lang('translates.general.empty')</div>
                    </div>
                </th>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="modal fade" id="create-work">
        <div class="modal-dialog modal-lg modal-dialog-centered">
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
                            <label for="data-department">@lang('translates.navbar.department')</label>
                            <select class="select2" id="data-department" name="department_id" required style="width: 100% !important;">
                                <option value="">@lang('translates.general.department_select')</option>
                                @foreach($allDepartments as $dep)
                                    <option
                                            value="{{$dep->id}}"
                                            @if($dep->id == auth()->user()->getAttribute('department_id')) selected @endif>
                                        {{$dep->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
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
    <form id="changeStatusForm" action="{{ route('works.update-mark') }}" method="POST">
        @csrf
        @method('PUT')

        <button id="changeStatusBtn" class="btn btn-primary">Statusu Güncəllə</button>
    </form>

@endsection
@section('scripts')
    <script>
        @if($works->isNotEmpty())
        const count  = document.getElementById("count").cloneNode(true);
        $("#table > tbody").prepend(count);
        @endif

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
    <script type="text/javascript">
        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $('.code').editable({
            url: "{{ route('work.code') }}",
        });

        $('.declaration').editable({
            url: "{{ route('work.declaration') }}",
        });

        $('.update').editable({
            url: "{{ route('editable') }}",
        });
    </script>
    <script>
        $(".js-example-theme-multiple").select2({
            theme: "classic"
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="works[]"]');
            var worksAllCheckbox = document.getElementById('works-all');
            var changeStatusForm = document.getElementById('changeStatusForm');
            var updateMarkUrl = "{{ route('works.update-mark') }}";

            // "Tümünü Seç" checkbox'ı tıklandığında
            worksAllCheckbox.addEventListener('click', function() {
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = worksAllCheckbox.checked;
                });
            });

            // Tüm checkbox'lar işaretli mi kontrolü
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    var allChecked = true;
                    checkboxes.forEach(function(checkbox) {
                        if (!checkbox.checked) {
                            allChecked = false;
                        }
                    });
                    worksAllCheckbox.checked = allChecked;
                });
            });

            // Form gönderildiğinde
            changeStatusForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Formun normal submit işlemini durdur

                var selectedWorks = [];
                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        selectedWorks.push(checkbox.value);
                    }
                });

                // Seçilen işlerin ID'lerini sunucuya gönder
                fetch(updateMarkUrl, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ works: selectedWorks })
                })
                    .then(response => {
                        if (response.ok) {
                            location.reload(); // İşlem başarılıysa sayfayı yenile
                        } else {
                            console.error('İşlem başarısız oldu.');
                        }
                    })
                    .catch(error => {
                        console.error('Hata:', error);
                    });
            });
        });
    </script>
@endsection


