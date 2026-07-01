@extends('layouts.main')

@section('title', 'Bank Hesabları')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">@lang('translates.navbar.dashboard')</x-bread-crumb-link>
        <x-bread-crumb-link>Bank Hesabları</x-bread-crumb-link>
    </x-bread-crumb>

    <div class="float-right mb-2">
        <a class="btn btn-outline-success" href="{{ route('bank-accounts.create') }}">
            <i class="fal fa-plus"></i> Yeni Hesab
        </a>
    </div>

    <div class="table-responsive mt-2">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Şirkət</th>
                    <th>Ad (Label)</th>
                    <th>Slug</th>
                    <th>Bank</th>
                    <th>H/H</th>
                    <th>VÖEN</th>
                    <th>Əməliyyatlar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($accounts as $account)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ optional($account->company)->name }}</td>
                        <td>{{ $account->label }}</td>
                        <td><code>{{ $account->slug }}</code></td>
                        <td>{{ $account->bank_name }}</td>
                        <td><small>{{ $account->hh }}</small></td>
                        <td>{{ $account->voen }}</td>
                        <td>
                            <div class="btn-sm-group">
                                <a href="{{ route('bank-accounts.edit', $account) }}" class="btn btn-sm btn-outline-success">
                                    <i class="fal fa-pen"></i>
                                </a>
                                <a href="{{ route('bank-accounts.destroy', $account) }}"
                                   delete data-name="{{ $account->label }}"
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fal fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="alert alert-danger text-center m-3">@lang('translates.general.empty')</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
