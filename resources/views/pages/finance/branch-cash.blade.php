@extends('layouts.main')

@section('title', 'Filial Kassa')

@section('content')
    <div class="container">
        <x-bread-crumb>
            <x-bread-crumb-link :link="route('dashboard')">
                @lang('translates.navbar.dashboard')
            </x-bread-crumb-link>
            <x-bread-crumb-link>
                Filial Kassa
            </x-bread-crumb-link>
        </x-bread-crumb>

        <form method="GET" action="{{ route('branch-cashes.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-4">
                    <label for="department_id">Filial</label>
                    <select name="department_id" id="department_id" class="form-control" onchange="this.form.submit()">
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}" {{ $departmentId == $dep->id ? 'selected' : '' }}>
                                {{ $dep->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="date">Tarix</label>
                    <input type="date" name="date" id="date" class="form-control"
                           value="{{ $date }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary mr-2">
                        @lang('translates.buttons.show')
                    </button>
                </div>
            </div>
        </form>

        <form method="POST" action="{{ route('branch-cashes.sync-from-works') }}" class="mb-3">
            @csrf
            <input type="hidden" name="branch_cash_id" value="{{ $branchCash->id }}">
            <button type="submit" class="btn btn-success">
                İşləri kassaya yüklə
            </button>
        </form>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <h4 class="text-center mb-1">
                    {{ $branchCash->department->name ?? '' }} KASSA
                </h4>
                <h5 class="text-center mb-4">
                    {{ \Carbon\Carbon::parse($date)->format('d.m.Y') }}
                </h5>

                <form method="POST" action="{{ route('branch-cashes.header.update', $branchCash) }}" class="mb-3">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <label>Günün əvvəlinə qalıq</label>
                            <input type="number" step="0.01" name="opening_balance" class="form-control"
                                   value="{{ $branchCash->opening_balance }}">
                        </div>
                        <div class="col-md-3">
                            <label>Gün ərzində əməliyyatdan qalıq</label>
                            <input type="text" class="form-control" value="{{ number_format($branchCash->operations_balance, 2, '.', ' ') }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Təhvil verildi</label>
                            <input type="number" step="0.01" name="handover_amount" class="form-control"
                                   value="{{ $branchCash->handover_amount }}">
                        </div>
                        <div class="col-md-3">
                            <label>Günün sonuna qalıq</label>
                            <input type="text" class="form-control"
                                   value="{{ number_format($branchCash->closing_balance, 2, '.', ' ') }}" readonly>
                        </div>
                    </div>
                    <div class="mt-2 text-right">
                        <button type="submit" class="btn btn-primary">
                            Yadda saxla
                        </button>
                    </div>
                </form>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mt-3">KASSA MƏDAXİL</h5>
                        <table class="table table-bordered">
                            <thead class="text-center">
                            <tr>
                                <th>Növ</th>
                                <th>GB</th>
                                <th>Say</th>
                                <th>Yığışdır</th>
                                <th>Qiymət</th>
                                <th>Məbləğ</th>
                                <th>Qeyd</th>
                                <th>Əməliyyat</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($incomeItems as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-center">{{ $item->gb }}</td>
                                    <td class="text-center">
                                        @if($item->work_id && $item->work)
                                            {{ $item->work->getParameterValue(20) ?? $item->representative ?? 0 }}
                                        @else
                                            {{ $item->representative ?? 0 }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->sb ?? 0 }}</td>
                                    <td class="text-right">{{ number_format($item->price, 2, '.', ' ') }}</td>
                                    <td class="text-right">{{ number_format($item->amount, 2, '.', ' ') }}</td>
                                    <td>{{ $item->note }}</td>
                                    <td class="text-center">
                                        @if($item->work_id === null)
                                            <form method="POST" action="{{ route('branch-cashes.items.delete', [$branchCash, $item]) }}" class="d-inline" onsubmit="return confirm('Bu sətri silmək istədiyinizə əminsiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Sil
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Avtomatik</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-right font-weight-bold">Cəmi</td>
                                <td class="text-right font-weight-bold">
                                    {{ number_format($incomeSum, 2, '.', ' ') }}
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                        <form method="POST" action="{{ route('branch-cashes.items.store', $branchCash) }}" class="mb-4">
                            @csrf
                            <input type="hidden" name="direction" value="income">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label>Növ</label>
                                    <input type="text" name="description" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <label>GB</label>
                                    <input type="number" name="gb" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <label>Say</label>
                                    <input type="number" name="representative" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <label>Yığışdır</label>
                                    <input type="number" name="sb" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Qiymət</label>
                                    <input type="number" step="0.01" name="price" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Məbləğ</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label>Qeyd</label>
                                    <input type="text" name="note" class="form-control">
                                </div>
                            </div>
                            <div class="mt-2 text-right">
                                <button type="submit" class="btn btn-outline-success">
                                    Manual mədaxil əlavə et
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mt-3">KASSA MƏXARİC</h5>
                        <table class="table table-bordered">
                            <thead class="text-center">
                            <tr>
                                <th>Növ</th>
                                <th>GB</th>
                                <th>Say</th>
                                <th>Yığışdır</th>
                                <th>Qiymət</th>
                                <th>Məbləğ</th>
                                <th>Qeyd</th>
                                <th>Əməliyyat</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($expenseItems as $item)
                                <tr>
                                    <td>{{ $item->description }}</td>
                                    <td class="text-center">{{ $item->gb }}</td>
                                    <td class="text-center">
                                        @if($item->work_id && $item->work)
                                            {{ $item->work->getParameterValue(20) ?? $item->representative ?? 0 }}
                                        @else
                                            {{ $item->representative ?? 0 }}
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->sb ?? 0 }}</td>
                                    <td class="text-right">{{ number_format($item->price, 2, '.', ' ') }}</td>
                                    <td class="text-right">{{ number_format($item->amount, 2, '.', ' ') }}</td>
                                    <td>{{ $item->note }}</td>
                                    <td class="text-center">
                                        @if($item->work_id === null)
                                            <form method="POST" action="{{ route('branch-cashes.items.delete', [$branchCash, $item]) }}" class="d-inline" onsubmit="return confirm('Bu sətri silmək istədiyinizə əminsiniz?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Sil
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-muted small">Avtomatik</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-right font-weight-bold">Cəmi</td>
                                <td class="text-right font-weight-bold">
                                    {{ number_format($expenseSum, 2, '.', ' ') }}
                                </td>
                                <td></td>
                            </tr>
                            </tbody>
                        </table>

                        <form method="POST" action="{{ route('branch-cashes.items.store', $branchCash) }}">
                            @csrf
                            <input type="hidden" name="direction" value="expense">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <label>Növ</label>
                                    <input type="text" name="description" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <label>GB</label>
                                    <input type="number" name="gb" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <label>Say</label>
                                    <input type="number" name="representative" class="form-control">
                                </div>
                                <div class="col-md-1">
                                    <label>Yığışdır</label>
                                    <input type="number" name="sb" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Qiymət</label>
                                    <input type="number" step="0.01" name="price" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label>Məbləğ</label>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                    <label>Qeyd</label>
                                    <input type="text" name="note" class="form-control">
                                </div>
                            </div>
                            <div class="mt-2 text-right">
                                <button type="submit" class="btn btn-outline-danger">
                                    Manual məxaric əlavə et
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

