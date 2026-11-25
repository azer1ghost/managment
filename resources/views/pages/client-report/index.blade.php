@extends('layouts.main')

@section('title', 'Müştəri hesabatı')

@section('style')
    <style>
        .report-filters { background: #fff; border: 1px solid #e3e6ea; border-radius: 8px; padding: 16px 16px 4px; }
        .report-filters label { font-size: .8rem; font-weight: 600; color: #6c757d; margin-bottom: 4px; }
        .report-summary .card { border: 1px solid #e3e6ea; border-radius: 8px; }
        .report-summary .value { font-size: 1.4rem; font-weight: 700; color: #212529; }
        .report-summary .label { font-size: .8rem; color: #6c757d; }
        .client-name, .client-name:hover { color: #212529; font-weight: 700; }
        .branch-list { list-style: none; padding: 0; margin: 0; }
        .branch-list li { display: flex; justify-content: space-between; gap: 12px; padding: 2px 0; border-bottom: 1px dashed #e3e6ea; font-size: .875rem; }
        .branch-list li:last-child { border-bottom: 0; }
        .branch-list .branch-name { font-weight: 600; color: #212529; }
        .branch-list .branch-meta { color: #6c757d; white-space: nowrap; }
        .report-table td { vertical-align: top; }
    </style>
@endsection

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('clients.index')">
            @lang('translates.navbar.client')
        </x-bread-crumb-link>
        <x-bread-crumb-link is-current="1">
            Müştəri hesabatı
        </x-bread-crumb-link>
    </x-bread-crumb>

    <form action="{{ route('clients.report') }}" class="report-filters mb-3">
        <div class="row">
            <div class="form-group col-12 col-md-3">
                <label for="createdAtFilter">Müştərinin yaranma tarixi</label>
                <input class="form-control custom-daterange" id="createdAtFilter" type="text" readonly
                       name="created_at" value="{{ $filters['created_at'] }}">
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="workDateFilter">İş tarixi</label>
                <input class="form-control custom-daterange" id="workDateFilter" type="text" readonly
                       name="work_date" value="{{ $filters['work_date'] }}">
                <div class="mt-1">
                    <input type="checkbox" name="check_work_date" id="check_work_date" @if($filters['check_work_date']) checked @endif>
                    <label for="check_work_date" class="font-weight-normal">İş tarixinə görə filtrlə</label>
                </div>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="search">Müştəri / VÖEN</label>
                <input type="search" id="search" name="search" class="form-control" value="{{ $filters['search'] }}" placeholder="Ad və ya VÖEN">
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="departments">Filial (şöbə)</label>
                <select name="departments[]" id="departments" class="form-control bootstrap-select" multiple
                        data-live-search="true" data-actions-box="true" data-selected-text-format="count > 2" title="Hamısı">
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" @if(in_array($department->id, $filters['departments'])) selected @endif>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-12 col-md-3">
                <label for="service">Xidmət</label>
                <select name="service" id="service" class="form-control bootstrap-select" data-live-search="true">
                    <option value="">Hamısı</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" @if($filters['service'] == $service->id) selected @endif>{{ $service->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="status">İşin statusu</label>
                <select name="status" id="status" class="custom-select">
                    <option value="">Hamısı</option>
                    @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" @if((string) $filters['status'] === (string) $key) selected @endif>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="type">Müştəri növü</label>
                <select name="type" id="type" class="custom-select">
                    <option value="">Hamısı</option>
                    @foreach($types as $key => $type)
                        <option value="{{ $key }}" @if((string) $filters['type'] === (string) $key) selected @endif>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="active">Aktivlik</label>
                <select name="active" id="active" class="custom-select">
                    <option value="">Hamısı</option>
                    <option value="{{ \App\Models\Client::ACTIVE }}" @if((string) $filters['active'] === (string) \App\Models\Client::ACTIVE) selected @endif>@lang('translates.buttons.active')</option>
                    <option value="{{ \App\Models\Client::PASSIVE }}" @if((string) $filters['active'] === (string) \App\Models\Client::PASSIVE) selected @endif>@lang('translates.buttons.passive')</option>
                </select>
            </div>

            <div class="form-group col-12 col-md-3">
                <label for="coordinator">Koordinator</label>
                <select name="coordinator" id="coordinator" class="form-control bootstrap-select" data-live-search="true">
                    <option value="">Hamısı</option>
                    @foreach($coordinators as $user)
                        <option value="{{ $user->id }}" @if($filters['coordinator'] == $user->id) selected @endif>{{ $user->fullname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="sale">Satış meneceri</label>
                <select name="sale" id="sale" class="form-control bootstrap-select" data-live-search="true">
                    <option value="">Hamısı</option>
                    @foreach($sales as $user)
                        <option value="{{ $user->id }}" @if($filters['sale'] == $user->id) selected @endif>{{ $user->fullname }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="company">Şirkət</label>
                <select name="company" id="company" class="custom-select">
                    <option value="">Hamısı</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" @if($filters['company'] == $company->id) selected @endif>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-3">
                <label for="has_works">İşi olan / olmayan</label>
                <select name="has_works" id="has_works" class="custom-select">
                    <option value="">Hamısı</option>
                    <option value="yes" @if($filters['has_works'] === 'yes') selected @endif>Yalnız işi olanlar</option>
                    <option value="no" @if($filters['has_works'] === 'no') selected @endif>İşi olmayanlar</option>
                </select>
            </div>

            <div class="form-group col-6 col-md-3">
                <label for="sort">Sıralama</label>
                <select name="sort" id="sort" class="custom-select">
                    @foreach([
                        'created_desc'   => 'Yaranma tarixi (yeni → köhnə)',
                        'created_asc'    => 'Yaranma tarixi (köhnə → yeni)',
                        'last_work_desc' => 'Son iş tarixi (yeni → köhnə)',
                        'last_work_asc'  => 'Son iş tarixi (köhnə → yeni)',
                        'works_desc'     => 'İş sayı (çox → az)',
                        'name'           => 'Ad (A → Z)',
                    ] as $key => $label)
                        <option value="{{ $key }}" @if($filters['sort'] === $key) selected @endif>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-6 col-md-2">
                <label for="limit">Səhifədə</label>
                <select name="limit" id="limit" class="custom-select">
                    @foreach([25, 50, 100, 250, 500, trans('translates.general.all')] as $size)
                        <option value="{{ $size }}" @if($filters['limit'] == $size) selected @endif>{{ $size }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-12 col-md-7 d-flex align-items-end justify-content-end">
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> @lang('translates.buttons.filter')
                    </button>
                    <a href="{{ route('clients.report') }}" class="btn btn-outline-danger">
                        <i class="fal fa-times-circle"></i> @lang('translates.filters.clear')
                    </a>
                </div>
            </div>
        </div>
    </form>

    @php
        $isPaginated = $clients instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator;
        $pageWorks = $clients->sum('works_count');
        $pageBranches = $branchStats->flatten(1)->pluck('department_id')->unique()->count();
    @endphp

    <div class="row report-summary mb-3">
        <div class="col-6 col-md-3 mb-2">
            <div class="card p-3">
                <div class="value">{{ $isPaginated ? $clients->total() : $clients->count() }}</div>
                <div class="label">Müştəri</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card p-3">
                <div class="value">{{ $pageWorks }}</div>
                <div class="label">İş{{ $isPaginated && $clients->hasPages() ? ' (bu səhifədə)' : '' }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card p-3">
                <div class="value">{{ $pageBranches }}</div>
                <div class="label">İş görülən filial</div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card p-3">
                <div class="value">{{ $clients->where('works_count', 0)->count() }}</div>
                <div class="label">İşi olmayan müştəri{{ $isPaginated && $clients->hasPages() ? ' (bu səhifədə)' : '' }}</div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover report-table">
            <thead>
            <tr>
                <th>#</th>
                <th>Müştəri</th>
                <th>VÖEN</th>
                <th>@lang('translates.columns.type')</th>
                <th>Koordinator / Satış</th>
                <th>Yaranma tarixi</th>
                <th>İş sayı</th>
                <th>Son iş tarixi</th>
                <th style="min-width: 280px">İşin görüldüyü filial</th>
            </tr>
            </thead>
            <tbody>
            @forelse($clients as $client)
                @php($branches = $branchStats->get($client->id, collect()))
                <tr>
                    <td>{{ $isPaginated ? $clients->firstItem() + $loop->index : $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" target="_blank" class="client-name">{{ $client->fullname }}</a>
                        @if($client->companies->isNotEmpty())
                            <div class="small text-muted">{{ $client->companies->pluck('name')->implode(', ') }}</div>
                        @endif
                    </td>
                    <td>{{ $client->voen }}</td>
                    <td>{{ $types[$client->type] ?? '' }}</td>
                    <td class="small">
                        @if($client->coordinators->isNotEmpty())
                            <div><span class="text-muted">K:</span> {{ $client->coordinators->map->fullname->implode(', ') }}</div>
                        @endif
                        @if($client->sales->isNotEmpty())
                            <div><span class="text-muted">S:</span> {{ $client->sales->map->fullname->implode(', ') }}</div>
                        @endif
                    </td>
                    <td>{{ optional($client->created_at)->format('d.m.Y') }}</td>
                    <td><b>{{ $client->works_count }}</b></td>
                    <td>{{ $client->last_work_at ? \Carbon\Carbon::parse($client->last_work_at)->format('d.m.Y') : '—' }}</td>
                    <td>
                        @if($branches->isEmpty())
                            <span class="text-muted">İş yoxdur</span>
                        @else
                            <ul class="branch-list">
                                @foreach($branches as $branch)
                                    <li>
                                        <span class="branch-name">{{ $branch->department_name ?? 'Filial qeyd olunmayıb' }}</span>
                                        <span class="branch-meta">{{ $branch->works_count }} iş · son {{ \Carbon\Carbon::parse($branch->last_work_at)->format('d.m.Y') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">Seçilmiş filtrlərə uyğun müştəri tapılmadı</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @if($isPaginated)
        <div class="d-flex justify-content-center">
            {{ $clients->links() }}
        </div>
    @endif
@endsection
