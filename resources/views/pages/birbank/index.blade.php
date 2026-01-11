@extends('layouts.main')

@section('title', 'Birbank İnteqrasiyası')

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            Birbank İnteqrasiyası
        </x-bread-crumb-link>
    </x-bread-crumb>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ümumi Şirkətlər</h6>
                            <h3 class="mb-0">{{ $stats['total_companies'] }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Token-lı Şirkətlər</h6>
                            <h3 class="mb-0">{{ $stats['with_tokens'] }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="fas fa-key fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Ümumi Transaction-lar</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_transactions']) }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-exchange-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form action="{{ route('birbank.index') }}" method="GET">
        <div class="row d-flex justify-content-between mb-3">
            <div class="col-8 col-md-6 mb-3">
                <div class="input-group">
                    <input type="search" name="search" value="{{ request()->get('search') }}" 
                           class="form-control" placeholder="Şirkət adı və ya username ilə axtarış..." 
                           aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fal fa-search"></i>
                        </button>
                        <a class="btn btn-outline-danger d-flex align-items-center" href="{{ route('birbank.index') }}">
                            <i class="fal fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-4 col-md-3 mb-3">
                <select class="form-control" name="env" onchange="this.form.submit()">
                    <option value="test" @if($env == 'test') selected @endif>Test Mühit</option>
                    <option value="prod" @if($env == 'prod') selected @endif>Production Mühit</option>
                </select>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Şirkət</th>
                            <th>Username</th>
                            <th>Mühit</th>
                            <th>Token Status</th>
                            <th>Auth Type</th>
                            <th>Son Login</th>
                            <th>Transaction-lar</th>
                            <th>Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($credentials as $credential)
                            <tr>
                                <td>
                                    <strong>{{ $credential->company->name ?? 'N/A' }}</strong>
                                    <br>
                                    <small class="text-muted">ID: {{ $credential->company_id }}</small>
                                </td>
                                <td>{{ $credential->username }}</td>
                                <td>
                                    <span class="badge badge-{{ $credential->env == 'test' ? 'warning' : 'danger' }}">
                                        {{ strtoupper($credential->env) }}
                                    </span>
                                </td>
                                <td>
                                    @if($credential->hasValidToken())
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> Aktiv
                                        </span>
                                    @elseif($credential->access_token)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Müddəti bitib
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-times-circle"></i> Yoxdur
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $credential->auth_type ?? '-' }}</td>
                                <td>
                                    @if($credential->last_login_at)
                                        <small>{{ $credential->last_login_at->format('d.m.Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">Heç vaxt</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $txCount = \App\Models\BirbankTransaction::where('company_id', $credential->company_id)
                                            ->where('env', $credential->env)
                                            ->count();
                                    @endphp
                                    <span class="badge badge-info">{{ $txCount }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" 
                                                class="btn btn-sm btn-primary" 
                                                data-toggle="modal" 
                                                data-target="#loginModal{{ $credential->company_id }}{{ $credential->env }}"
                                                title="Login">
                                            <i class="fas fa-sign-in-alt"></i>
                                        </button>
                                        <a href="{{ route('birbank.show', $credential->company_id) }}?env={{ $credential->env }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Detallar">
                                            <i class="fal fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Heç bir credential tapılmadı</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($credentials->hasPages())
                <div class="mt-4">
                    {{ $credentials->appends(request()->input())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Login Modals for each credential -->
    @foreach($credentials as $credential)
        <div class="modal fade" id="loginModal{{ $credential->company_id }}{{ $credential->env }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-key"></i> Login - {{ $credential->company->name ?? 'N/A' }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('birbank.login', $credential->company_id) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="env" value="{{ $credential->env }}">
                            
                            <div class="form-group">
                                <label for="username{{ $credential->company_id }}{{ $credential->env }}">Username</label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username{{ $credential->company_id }}{{ $credential->env }}" 
                                       name="username" 
                                       value="{{ old('username', $credential->username ?? '') }}" 
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password{{ $credential->company_id }}{{ $credential->env }}">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password{{ $credential->company_id }}{{ $credential->env }}" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle"></i> 
                                    Mühit: <strong>{{ strtoupper($credential->env) }}</strong>
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ləğv et</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Quick Login Form for new company -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle"></i> Yeni Şirkət üçün Login
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('birbank.login', 1) }}" method="POST" id="quickLoginForm">
                @csrf
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quick_company_id">Şirkət</label>
                            <select class="form-control" id="quick_company_id" name="company_id" required>
                                <option value="">Şirkət seçin</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ old('company_id', 1) == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quick_env">Mühit</label>
                            <select class="form-control" id="quick_env" name="env" required>
                                <option value="test" {{ $env == 'test' ? 'selected' : '' }}>Test</option>
                                <option value="prod" {{ $env == 'prod' ? 'selected' : '' }}>Production</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quick_username">Username</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="quick_username" 
                                   name="username" 
                                   placeholder="0185231PORTAL" 
                                   value="{{ old('username') }}"
                                   required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quick_password">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="quick_password" 
                                   name="password" 
                                   placeholder="Şifrə" 
                                   required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Update form action when company_id changes
        document.getElementById('quick_company_id').addEventListener('change', function() {
            var companyId = this.value;
            var form = document.getElementById('quickLoginForm');
            form.action = '{{ route("birbank.login", ":id") }}'.replace(':id', companyId);
        });
    </script>
    @endpush
@endsection

