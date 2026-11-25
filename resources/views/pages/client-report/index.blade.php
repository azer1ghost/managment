@extends('layouts.main')

@section('title', 'Müştəri hesabatı')

@section('style')
    <style>
        .branch-row td { background: #f8f9fa; font-size: .9rem; }
        .branch-row td:first-child { border-left: 3px solid #007bff; }
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

    <form action="{{ route('clients.report') }}">
        <div class="row mb-3 align-items-end">
            <div class="form-group col-12 col-md-4">
                <label for="createdAtFilter">Müştərinin yaranma tarixi</label>
                <input class="form-control custom-daterange" id="createdAtFilter" type="text" readonly
                       name="created_at" value="{{ $filters['created_at'] }}">
            </div>
            <div class="form-group col-12 col-md-8 d-flex justify-content-end">
                <div class="btn-group">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-filter"></i> @lang('translates.buttons.filter')
                    </button>
                    <a href="{{ route('clients.report') }}" class="btn btn-outline-danger">
                        <i class="fal fa-times-circle"></i> @lang('translates.filters.clear')
                    </a>
                </div>
            </div>
        </div>
    </form>

    <p class="mb-2">
        Müştəri: <b>{{ $clients->count() }}</b> ·
        Filial: <b>{{ $clients->sum(fn($c) => $c->clients->count()) }}</b>
    </p>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>#</th>
                <th>@lang('translates.columns.full_name')</th>
                <th>VÖEN</th>
                <th>@lang('translates.columns.type')</th>
                <th>Yaranma tarixi</th>
                <th>İş sayı</th>
                <th>İlk iş tarixi</th>
                <th>Son iş tarixi</th>
                <th>Filial sayı</th>
            </tr>
            </thead>
            <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <a href="{{ route('clients.show', $client) }}" target="_blank">{{ $client->fullname }}</a>
                    </td>
                    <td>{{ $client->voen }}</td>
                    <td>{{ $types[$client->type] ?? '' }}</td>
                    <td>{{ optional($client->created_at)->format('d.m.Y') }}</td>
                    <td>{{ $client->works_count }}</td>
                    <td>{{ $client->first_work_at ? \Carbon\Carbon::parse($client->first_work_at)->format('d.m.Y') : '—' }}</td>
                    <td>{{ $client->last_work_at ? \Carbon\Carbon::parse($client->last_work_at)->format('d.m.Y') : '—' }}</td>
                    <td>{{ $client->clients->count() }}</td>
                </tr>
                @foreach($client->clients as $branch)
                    <tr class="branch-row">
                        <td></td>
                        <td>
                            <i class="fal fa-level-up fa-rotate-90 mr-1"></i>
                            <a href="{{ route('clients.show', $branch) }}" target="_blank">{{ $branch->fullname }}</a>
                        </td>
                        <td>{{ $branch->voen }}</td>
                        <td>{{ $types[$branch->type] ?? '' }}</td>
                        <td>{{ optional($branch->created_at)->format('d.m.Y') }}</td>
                        <td>{{ $branch->works_count }}</td>
                        <td>{{ $branch->first_work_at ? \Carbon\Carbon::parse($branch->first_work_at)->format('d.m.Y') : '—' }}</td>
                        <td>{{ $branch->last_work_at ? \Carbon\Carbon::parse($branch->last_work_at)->format('d.m.Y') : '—' }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted">Bu tarix aralığında müştəri yoxdur</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
