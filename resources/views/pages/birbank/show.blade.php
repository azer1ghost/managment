@extends('layouts.main')

@section('title', 'Birbank - ' . $company->name)

@section('content')
    <x-bread-crumb>
        <x-bread-crumb-link :link="route('dashboard')">
            @lang('translates.navbar.dashboard')
        </x-bread-crumb-link>
        <x-bread-crumb-link :link="route('birbank.index')">
            Birbank İnteqrasiyası
        </x-bread-crumb-link>
        <x-bread-crumb-link>
            {{ $company->name }}
        </x-bread-crumb-link>
    </x-bread-crumb>

    <!-- Environment Selector -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group" role="group">
                <a href="{{ route('birbank.show', $company) }}?env=test" 
                   class="btn btn-{{ $env == 'test' ? 'warning' : 'outline-warning' }}">
                    Test Mühit
                </a>
                <a href="{{ route('birbank.show', $company) }}?env=prod" 
                   class="btn btn-{{ $env == 'prod' ? 'danger' : 'outline-danger' }}">
                    Production Mühit
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Ümumi Transaction-lar</h6>
                    <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Gələn</h6>
                    <h3 class="mb-0 text-success">{{ number_format($stats['in']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Gedən</h6>
                    <h3 class="mb-0 text-danger">{{ number_format($stats['out']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Ümumi Məbləğ (AZN)</h6>
                    <h3 class="mb-0 text-primary">{{ number_format($stats['total_amount'], 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Credentials Card -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-key"></i> Login Məlumatları
                    </h5>
                </div>
                <div class="card-body">
                    @if($credential)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <strong>Status:</strong>
                                @if($credential->hasValidToken())
                                    <span class="badge badge-success">
                                        <i class="fas fa-check-circle"></i> Token Aktiv
                                    </span>
                                @elseif($credential->access_token)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Token Müddəti Bitib
                                    </span>
                                @else
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-times-circle"></i> Token Yoxdur
                                    </span>
                                @endif
                            </div>

                            @if($credential->access_token)
                                <form action="{{ route('birbank.logout', $company) }}" method="POST" class="ml-2">
                                    @csrf
                                    <input type="hidden" name="env" value="{{ $env }}">
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Bu Birbank hesabından çıxış edilsin?')">
                                        <i class="fas fa-sign-out-alt"></i> Çıxış et
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="mb-2">
                            <strong>Username:</strong> {{ $credential->username }}
                        </div>
                        <div class="mb-2">
                            <strong>Auth Type:</strong> {{ $credential->auth_type ?? '-' }}
                        </div>
                        <div class="mb-2">
                            <strong>Son Login:</strong> 
                            @if($credential->last_login_at)
                                {{ $credential->last_login_at->format('d.m.Y H:i:s') }}
                            @else
                                <span class="text-muted">Heç vaxt</span>
                            @endif
                        </div>
                        @if($credential->token_expires_at)
                            <div class="mb-2">
                                <strong>Token Müddəti:</strong> 
                                {{ $credential->token_expires_at->format('d.m.Y H:i:s') }}
                            </div>
                        @endif
                    @else
                        <p class="text-muted mb-3">Bu şirkət üçün hələ login olunmayıb.</p>
                    @endif

                    {{-- Login Form: yalnız token aktiv deyilsə göstər --}}
                    @if(!$credential || !$credential->hasValidToken())
                        <form action="{{ route('birbank.login', $company) }}" method="POST" class="mt-3">
                            @csrf
                            <input type="hidden" name="env" value="{{ $env }}">
                            
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" 
                                       class="form-control @error('username') is-invalid @enderror" 
                                       id="username" 
                                       name="username" 
                                       value="{{ old('username', $credential->username ?? '') }}" 
                                       required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </form>
                    @else
                        <div class="alert alert-success mt-3 mb-0">
                            <i class="fas fa-check-circle"></i>
                            Birbank hesabı üçün token aktivdir. Yenidən login etməyə ehtiyac yoxdur.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sync"></i> Transaction Sync
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('birbank.sync-transactions', $company) }}" method="POST">
                        @csrf
                        <input type="hidden" name="env" value="{{ $env }}">
                        
                        <div class="form-group">
                            <label for="days">Son neçə günün məlumatlarını sync et?</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="days" 
                                   name="days" 
                                   value="30" 
                                   min="1" 
                                   max="365" 
                                   required>
                            <small class="form-text text-muted">1-365 gün arası</small>
                        </div>

                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-sync-alt"></i> Sync Et
                        </button>
                    </form>

                    <hr>

                    <div class="mt-3">
                        <h6>Artisan Command:</h6>
                        <code class="d-block p-2 bg-light rounded">
                            php artisan birbank:sync-transactions {{ $company->id }} --environment={{ $env }} --days=30
                        </code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Live Statement Viewer -->
    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-file-invoice-dollar"></i> Cari Hesabdan Çıxarış (Birbaşa API-dən)
            </h5>
        </div>
        <div class="card-body">
            @if(!empty($accounts))
                <form method="GET" action="{{ route('birbank.show', $company) }}" class="mb-3">
                    <input type="hidden" name="env" value="{{ $env }}">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="accountNumber">Hesab nömrəsi</label>
                            <select name="accountNumber" id="accountNumber" class="form-control" required>
                                <option value="">Hesab seçin</option>
                                @foreach($accounts as $account)
                                    @php
                                        $custAcNo = $account['custAcNo'] ?? $account['account_number'] ?? null;
                                        $iban = $account['ibanAcNo'] ?? $account['iban'] ?? null;
                                        $ccy = $account['ccy'] ?? $account['acCcy'] ?? null;
                                        $desc = $account['acDesc'] ?? null;
                                        $currAmt = $account['currAmt'] ?? null; // Cari məbləğ
                                        $balancePart = $currAmt !== null ? ' - Cari məbləğ: ' . $currAmt : '';
                                        $optionLabel = trim(
                                            ($custAcNo ? $custAcNo . ' - ' : '') .
                                            ($iban ?: '') . ' ' .
                                            ($ccy ? '(' . $ccy . ')' : '') . ' ' .
                                            ($desc ?: '') .
                                            $balancePart
                                        );
                                    @endphp
                                    <option value="{{ $custAcNo }}"
                                        @if(($statementFilters['accountNumber'] ?? null) == $custAcNo) selected @endif>
                                        {{ $optionLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="fromDate">Başlanğıc tarix</label>
                            <input type="date"
                                   id="fromDate"
                                   name="fromDate"
                                   class="form-control"
                                   value="{{ $statementFilters['fromDate'] ?? now()->subDays(30)->toDateString() }}"
                                   required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="toDate">Son tarix</label>
                            <input type="date"
                                   id="toDate"
                                   name="toDate"
                                   class="form-control"
                                   value="{{ $statementFilters['toDate'] ?? now()->toDateString() }}"
                                   required>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Çıxarış et
                            </button>
                        </div>
                    </div>
                </form>

                @if(!empty($statement))
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Ref №</th>
                                    <th>Tarix</th>
                                    <th>İstiqamət</th>
                                    <th>Məbləğ (AZN)</th>
                                    <th>Valyuta</th>
                                    <th>Təyinat</th>
                                    <th>Qarşı tərəf</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($statement as $row)
                                    @php
                                        $amount = isset($row['lcyAmount']) ? (float) $row['lcyAmount'] : null;
                                        $drcr = strtoupper($row['drcrInd'] ?? '');
                                        if ($drcr === 'C') {
                                            $direction = 'in';
                                        } elseif ($drcr === 'D') {
                                            $direction = 'out';
                                        } else {
                                            $direction = $amount !== null ? ($amount >= 0 ? 'in' : 'out') : null;
                                        }
                                        $date = $row['valueDt'] ?? $row['trnDt'] ?? $row['txnDtTime'] ?? null;
                                    @endphp
                                    <tr>
                                        <td>{{ $row['trnRefNo'] ?? '-' }}</td>
                                        <td>{{ $date ?? '-' }}</td>
                                        <td>
                                            @if($direction === 'in')
                                                <span class="badge badge-success">Gələn</span>
                                            @elseif($direction === 'out')
                                                <span class="badge badge-danger">Gedən</span>
                                            @else
                                                <span class="badge badge-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($amount !== null)
                                                <strong class="{{ $direction === 'in' ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format(abs($amount), 2) }}
                                                </strong>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $row['acCcy'] ?? $row['ccy'] ?? '-' }}</td>
                                        <td>{{ $row['purpose'] ?? '-' }}</td>
                                        <td>{{ $row['contrAccount'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @elseif(request()->has('accountNumber'))
                    <div class="alert alert-info mb-0">
                        Seçilmiş tarix aralığında əməliyyat tapılmadı.
                    </div>
                @endif
            @else
                <div class="alert alert-warning mb-0">
                    Hesablar tapılmadı və ya hesab siyahısı üçün endpoint cavab vermədi.
                </div>
            @endif
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-list"></i> Transaction-lar
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Hesab</th>
                            <th>İstiqamət</th>
                            <th>Məbləğ</th>
                            <th>Valyuta</th>
                            <th>Tarix</th>
                            <th>Təsvir</th>
                            <th>Qarşı tərəf</th>
                            <th>Əməliyyatlar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                            <tr>
                                <td>
                                    <small class="text-muted">{{ $transaction->account_ref }}</small>
                                </td>
                                <td>
                                    @if($transaction->direction == 'in')
                                        <span class="badge badge-success">
                                            <i class="fas fa-arrow-down"></i> Gələn
                                        </span>
                                    @elseif($transaction->direction == 'out')
                                        <span class="badge badge-danger">
                                            <i class="fas fa-arrow-up"></i> Gedən
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $transaction->direction == 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $transaction->amount ? number_format($transaction->amount, 2) : '-' }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $transaction->currency ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($transaction->booked_at)
                                        <small>{{ $transaction->booked_at->format('d.m.Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($transaction->description, 50) ?: '-' }}</small>
                                </td>
                                <td>
                                    <small>{{ $transaction->counterparty ?: '-' }}</small>
                                </td>
                                <td>
                                    @if($transaction->raw)
                                        <button type="button" 
                                                class="btn btn-sm btn-info" 
                                                data-toggle="modal" 
                                                data-target="#transactionModal{{ $transaction->id }}">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            <!-- Transaction Detail Modal -->
                            @if($transaction->raw)
                                <div class="modal fade" id="transactionModal{{ $transaction->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Transaction Detalları</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <pre class="bg-light p-3 rounded">{{ json_encode($transaction->raw, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Heç bir transaction tapılmadı</p>
                                    <p class="text-muted">
                                        <small>Sync etmək üçün yuxarıdakı formu istifadə edin</small>
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($transactions->hasPages())
                <div class="mt-4">
                    {{ $transactions->appends(request()->input())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

