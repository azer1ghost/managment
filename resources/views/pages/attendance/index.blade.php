@extends('layouts.main')

@section('title', 'Tabel')

@section('style')
<style>
    .tabel-wrap {
        overflow-x: auto;
        font-size: 12px;
    }
    .tabel-table {
        border-collapse: collapse;
        min-width: 100%;
        table-layout: fixed;
    }
    .tabel-table th,
    .tabel-table td {
        border: 1px solid #dee2e6;
        text-align: center;
        vertical-align: middle;
        padding: 2px 3px;
        white-space: nowrap;
    }
    .tabel-table .col-name {
        width: 160px;
        min-width: 160px;
        text-align: left;
        position: sticky;
        left: 0;
        background: #fff;
        z-index: 2;
        font-weight: 600;
        padding: 4px 8px;
    }
    .tabel-table thead th.col-name {
        background: #343a40;
        color: #fff;
        z-index: 3;
    }
    .tabel-table .col-day {
        width: 34px;
        min-width: 34px;
        cursor: pointer;
    }
    .tabel-table .col-summary {
        width: 50px;
        min-width: 50px;
        background: #f8f9fa;
        font-weight: 600;
    }
    .tabel-table thead th {
        background: #343a40;
        color: #fff;
        font-size: 11px;
        padding: 4px 2px;
    }
    .tabel-table thead th.weekend { background: #495057; }
    .tabel-table thead th.holiday { background: #1a5276; }

    .cell {
        min-height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 3px;
        font-weight: 600;
        font-size: 11px;
        cursor: pointer;
        position: relative;
        user-select: none;
    }
    .cell.absent-yellow { background: #fff3cd !important; color: #856404 !important; }
    .cell:hover { opacity: 0.8; }
    .cell .note-dot {
        position: absolute;
        top: 1px;
        right: 2px;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #dc3545;
    }

    /* Status badge colors */
    .status-B   { background: #cce5ff; color: #004085; }
    .status-İ   { background: #e2e3e5; color: #383d41; }
    .status-E   { background: #fff3cd; color: #856404; }
    .status-ƏM  { background: #d4edda; color: #155724; }
    .status-X   { background: #f8d7da; color: #721c24; }
    .status-AM  { background: #e2d9f3; color: #432874; }
    .status-Ö   { background: #f9f9f9; color: #6c757d; border: 1px solid #dee2e6; }
    .status-ÜS  { background: #fde8d8; color: #7d3c0b; }

    /* Modal cell editor */
    #cellModal .status-btn {
        font-size: 12px;
        margin: 3px;
        min-width: 90px;
    }
    .leave-badge {
        font-size: 11px;
        padding: 2px 6px;
        border-radius: 10px;
    }

    /* Bulk fill bar */
    .bulk-bar {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 12px;
    }

    /* Holiday manager */
    #holidayList .badge { font-size: 11px; }

    .tabel-table tbody tr:hover .col-name { background: #f1f3f5; }

    .month-nav .btn { font-size: 13px; }
</style>
@endsection

@section('content')

<x-bread-crumb>
    <x-bread-crumb-link :link="route('dashboard')">İdarə paneli</x-bread-crumb-link>
    <x-bread-crumb-link>Tabel</x-bread-crumb-link>
</x-bread-crumb>

{{-- ── Filters ─────────────────────────────────────────── --}}
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('attendance.index') }}" class="form-row align-items-end">
            <div class="col-auto">
                <label class="mb-1" style="font-size:12px">İl</label>
                <select name="year" class="form-control form-control-sm" onchange="this.form.submit()">
                    @for($y = now()->year + 1; $y >= now()->year - 3; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <label class="mb-1" style="font-size:12px">Ay</label>
                <select name="month" class="form-control form-control-sm" onchange="this.form.submit()">
                    @php $monthNames = ['01'=>'Yanvar','02'=>'Fevral','03'=>'Mart','04'=>'Aprel','05'=>'May','06'=>'İyun','07'=>'İyul','08'=>'Avqust','09'=>'Sentyabr','10'=>'Oktyabr','11'=>'Noyabr','12'=>'Dekabr'] @endphp
                    @foreach($monthNames as $num => $name)
                        <option value="{{ (int)$num }}" {{ $month == (int)$num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="mb-1" style="font-size:12px">Departament</label>
                <select name="department_id" class="form-control form-control-sm" onchange="this.form.submit()">
                    <option value="">Hamısı</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto d-flex" style="gap:6px">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#bulkModal">
                    <i class="fas fa-fill-drip"></i> Kütləvi doldur
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#holidayModal">
                    <i class="fas fa-calendar-day"></i> Bayramlar
                </button>
                <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#leaveModal">
                    <i class="fas fa-umbrella-beach"></i> Məzuniyyət hüququ
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Legend ──────────────────────────────────────────── --}}
<div class="d-flex flex-wrap mb-2" style="gap:6px;font-size:11px">
    @foreach(\App\Models\AttendanceRecord::$statuses as $code => $s)
        <span class="px-2 py-1 rounded" style="background:{{ $s['color'] }};color:{{ $s['text'] }}">
            <b>{{ $code }}</b> – {{ $s['label'] }}
        </span>
    @endforeach
    <span class="px-2 py-1 rounded" style="background:#fff3cd;color:#856404;border:1px solid #ffc107">
        <b>Sarı</b> – Gəlməyib (qərar gözlənilir)
    </span>
</div>

{{-- ── Tabel grid ──────────────────────────────────────── --}}
<div class="card">
    <div class="card-body p-2">
        <div class="tabel-wrap">
            <table class="tabel-table">
                <thead>
                    <tr>
                        <th class="col-name">Əməkdaş</th>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dayDate   = $startOfMonth->copy()->day($d);
                                $dow       = $dayDate->dayOfWeek; // 0=Sun,6=Sat
                                $isHoliday = $holidays->has($d);
                                $dayClass  = $isHoliday ? 'holiday' : ($dow == 0 || $dow == 6 ? 'weekend' : '');
                            @endphp
                            <th class="col-day {{ $dayClass }}" title="{{ $dayDate->format('d.m.Y') }} – {{ $dayDate->translatedFormat('l') }}">
                                {{ $d }}<br>
                                <span style="font-size:9px">{{ $dayDate->format('D') }}</span>
                            </th>
                        @endfor
                        <th class="col-summary">İş<br>günü</th>
                        <th class="col-summary">Məz.<br>qalıq</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    @php
                        $userRecords    = $records->get($user->id, collect());
                        $schedule       = $user->work_schedule ?? '5_day';
                        $entitlement    = $entitlements->get($user->id);
                        $leaveUsed      = $entitlement ? $entitlement->usedDays($year) : 0;
                        $leaveTotal     = $entitlement ? ($entitlement->total_days + $entitlement->extra_days) : 21;
                        $leaveRemaining = $leaveTotal - $leaveUsed;
                        $workDays       = 0;
                    @endphp
                    <tr>
                        <td class="col-name">
                            {{ $user->name }} {{ $user->surname }}
                            <br><small class="text-muted">{{ $schedule === '5_day' ? '5 gün' : ($schedule === '6_day' ? '6 gün' : '6 gün (y/ş)') }}</small>
                        </td>
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $dayDate = $startOfMonth->copy()->day($d);
                                $dow     = $dayDate->dayOfWeek;
                                $record  = $userRecords->get($d);
                                $status  = $record ? $record->status : null;
                                $note    = $record ? $record->note : null;
                                $absent  = $record ? $record->is_absent : false;

                                // Auto İ logic
                                $autoI = false;
                                if (!$status) {
                                    if ($schedule === '5_day' && ($dow === 0 || $dow === 6)) $autoI = true;
                                    if ($schedule === '6_day' && $dow === 0) $autoI = true;
                                    if ($schedule === '6_day_half' && $dow === 0) $autoI = true;
                                }

                                // Auto B (holiday)
                                $autoB = !$status && $holidays->has($d);

                                // Display
                                if ($status) {
                                    $displayCode = $status;
                                    $cssKey = str_replace(['.', ' '], '', $status);
                                    $bgColor = \App\Models\AttendanceRecord::$statuses[$status]['color'] ?? '#fff';
                                    $txtColor = \App\Models\AttendanceRecord::$statuses[$status]['text'] ?? '#000';
                                } elseif ($autoB) {
                                    $displayCode = 'B';
                                    $cssKey = 'B'; $bgColor = '#cce5ff'; $txtColor = '#004085';
                                } elseif ($autoI) {
                                    $displayCode = 'İ';
                                    $cssKey = 'İ'; $bgColor = '#e2e3e5'; $txtColor = '#383d41';
                                } else {
                                    $displayCode = '';
                                    $cssKey = ''; $bgColor = $absent ? '#fff3cd' : '#fff'; $txtColor = $absent ? '#856404' : '#212529';
                                }

                                // Count work days (not İ, B, X, A.M, Ö, ÜS, Ə.M)
                                if (!in_array($displayCode, ['İ', 'B', 'X', 'A.M', 'Ö', 'ÜS', 'Ə.M']) && $displayCode !== '') {
                                    // working day with code E or just present
                                }
                                if ($displayCode === '' && !$autoI && !$autoB) $workDays++;
                                if ($displayCode === 'E') $workDays++;
                            @endphp
                            <td class="col-day p-0"
                                onclick="openCell({{ $user->id }}, '{{ $dayDate->toDateString() }}', '{{ addslashes($user->name . ' ' . $user->surname) }}', '{{ $status }}', '{{ addslashes($note ?? '') }}', {{ $absent ? 'true' : 'false' }})"
                                title="{{ $note ? '📝 '.$note : '' }}"
                                style="padding:1px!important">
                                <div class="cell status-{{ $cssKey }}"
                                     style="background:{{ $bgColor }};color:{{ $txtColor }};{{ $absent && !$status ? 'background:#fff3cd;color:#856404;' : '' }}"
                                     id="cell-{{ $user->id }}-{{ $d }}">
                                    {{ $displayCode }}
                                    @if($note)
                                        <span class="note-dot"></span>
                                    @endif
                                </div>
                            </td>
                        @endfor
                        <td class="col-summary">{{ $workDays }}</td>
                        <td class="col-summary">
                            <span class="badge {{ $leaveRemaining < 5 ? 'badge-danger' : 'badge-success' }}">
                                {{ $leaveRemaining }}
                            </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL: Single Cell Editor
     ═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="cellModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="cellModalTitle">Gün seç</h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="cm_user_id">
                <input type="hidden" id="cm_date">

                <div class="mb-2">
                    <label style="font-size:11px">Status</label>
                    <div class="d-flex flex-wrap">
                        @foreach(\App\Models\AttendanceRecord::$statuses as $code => $s)
                            <button type="button"
                                class="btn btn-sm status-btn"
                                style="background:{{ $s['color'] }};color:{{ $s['text'] }};border:1px solid #ccc"
                                onclick="selectStatus('{{ $code }}')">
                                <b>{{ $code }}</b>
                            </button>
                        @endforeach
                        <button type="button" class="btn btn-sm btn-outline-secondary status-btn" onclick="selectStatus('')">
                            Sil
                        </button>
                    </div>
                    <input type="hidden" id="cm_status">
                </div>

                <div class="form-group mb-2">
                    <label style="font-size:11px">Qeyd</label>
                    <input type="text" id="cm_note" class="form-control form-control-sm" placeholder="İstəyə görə qeyd...">
                </div>

                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" id="cm_absent">
                    <label class="form-check-label" for="cm_absent" style="font-size:12px">
                        Gəlməyib (sarı)
                    </label>
                </div>

                <div id="leaveInfo" class="alert alert-info py-1 px-2 d-none" style="font-size:11px"></div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Bağla</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="saveCell()">Saxla</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL: Bulk Fill
     ═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="bulkModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Kütləvi doldur</h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label style="font-size:12px">Əməkdaşlar</label>
                    <select id="bulk_users" class="form-control form-control-sm" multiple style="height:120px">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Ctrl+click ilə çoxlu seçin</small>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label style="font-size:12px">Başlanğıc tarix</label>
                        <input type="date" id="bulk_from" class="form-control form-control-sm"
                               value="{{ $startOfMonth->format('Y-m-d') }}"
                               min="{{ $startOfMonth->format('Y-m-d') }}"
                               max="{{ $startOfMonth->copy()->endOfMonth()->format('Y-m-d') }}">
                    </div>
                    <div class="col">
                        <label style="font-size:12px">Son tarix</label>
                        <input type="date" id="bulk_to" class="form-control form-control-sm"
                               value="{{ $startOfMonth->copy()->endOfMonth()->format('Y-m-d') }}"
                               min="{{ $startOfMonth->format('Y-m-d') }}"
                               max="{{ $startOfMonth->copy()->endOfMonth()->format('Y-m-d') }}">
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label style="font-size:12px">Status</label>
                    <select id="bulk_status" class="form-control form-control-sm">
                        <option value="">— Statusu sil —</option>
                        @foreach(\App\Models\AttendanceRecord::$statuses as $code => $s)
                            <option value="{{ $code }}">{{ $code }} – {{ $s['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label style="font-size:12px">Qeyd (hamısına)</label>
                    <input type="text" id="bulk_note" class="form-control form-control-sm" placeholder="İstəyə görə...">
                </div>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Ləğv et</button>
                <button type="button" class="btn btn-sm btn-primary" onclick="saveBulk()">Tətbiq et</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL: Public Holidays
     ═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="holidayModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Bayram günləri — {{ $year }}</h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-row mb-2">
                    <div class="col">
                        <input type="date" id="hol_date" class="form-control form-control-sm" placeholder="Tarix">
                    </div>
                    <div class="col">
                        <input type="text" id="hol_name" class="form-control form-control-sm" placeholder="Ad (məs. Novruz)">
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-success" onclick="addHoliday()">Əlavə et</button>
                    </div>
                </div>
                <ul class="list-group list-group-flush" id="holidayList">
                    @foreach(\App\Models\PublicHoliday::whereYear('date', $year)->orderBy('date')->get() as $h)
                        <li class="list-group-item d-flex justify-content-between align-items-center py-1" id="hol-{{ $h->id }}">
                            <span>
                                <span class="badge badge-primary mr-1">{{ $h->date->format('d.m.Y') }}</span>
                                {{ $h->name }}
                            </span>
                            <button class="btn btn-sm btn-outline-danger py-0" onclick="deleteHoliday({{ $h->id }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Bağla</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════════
     MODAL: Leave Entitlements
     ═══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="leaveModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">Məzuniyyət hüquqları — {{ $year }}</h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-sm table-bordered mb-0" style="font-size:12px">
                    <thead class="thead-dark">
                        <tr>
                            <th>Əməkdaş</th>
                            <th style="width:100px">Cəmi hüquq</th>
                            <th style="width:90px">Staja əlavə</th>
                            <th style="width:80px">İstifadə</th>
                            <th style="width:80px">Qalıq</th>
                            <th style="width:80px">Saxla</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        @php
                            $ent = $entitlements->get($user->id);
                            $used = $ent ? $ent->usedDays($year) : 0;
                            $total = $ent ? $ent->total_days : 21;
                            $extra = $ent ? $ent->extra_days : 0;
                            $remaining = $total + $extra - $used;
                        @endphp
                        <tr>
                            <td>{{ $user->name }} {{ $user->surname }}</td>
                            <td>
                                <select class="form-control form-control-sm" id="ent_total_{{ $user->id }}">
                                    <option value="21" {{ $total == 21 ? 'selected' : '' }}>21 gün</option>
                                    <option value="30" {{ $total == 30 ? 'selected' : '' }}>30 gün</option>
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control form-control-sm" id="ent_extra_{{ $user->id }}"
                                       value="{{ $extra }}" min="0" max="60">
                            </td>
                            <td class="text-center font-weight-bold" id="ent_used_{{ $user->id }}">{{ $used }}</td>
                            <td class="text-center" id="ent_rem_{{ $user->id }}">
                                <span class="badge {{ $remaining < 5 ? 'badge-danger' : 'badge-success' }}">{{ $remaining }}</span>
                            </td>
                            <td>
                                <button class="btn btn-xs btn-primary" style="font-size:11px;padding:2px 8px"
                                        onclick="saveEntitlement({{ $user->id }}, {{ $year }})">
                                    Saxla
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer py-1">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Bağla</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const CSRF = '{{ csrf_token() }}';
    const STATUSES = @json(\App\Models\AttendanceRecord::$statuses);

    // ── Cell editor ──────────────────────────────────────
    function openCell(userId, date, name, currentStatus, currentNote, isAbsent) {
        document.getElementById('cm_user_id').value = userId;
        document.getElementById('cm_date').value = date;
        document.getElementById('cellModalTitle').textContent = name + ' — ' + date;
        document.getElementById('cm_status').value = currentStatus || '';
        document.getElementById('cm_note').value = currentNote || '';
        document.getElementById('cm_absent').checked = isAbsent;
        document.getElementById('leaveInfo').classList.add('d-none');
        highlightStatus(currentStatus || '');
        $('#cellModal').modal('show');
    }

    function selectStatus(code) {
        document.getElementById('cm_status').value = code;
        highlightStatus(code);
    }

    function highlightStatus(code) {
        document.querySelectorAll('.status-btn').forEach(btn => {
            btn.style.outline = btn.textContent.trim() === code ? '3px solid #0056b3' : 'none';
        });
    }

    function saveCell() {
        const userId  = document.getElementById('cm_user_id').value;
        const date    = document.getElementById('cm_date').value;
        const status  = document.getElementById('cm_status').value;
        const note    = document.getElementById('cm_note').value;
        const absent  = document.getElementById('cm_absent').checked;

        fetch('{{ route("attendance.cell") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ user_id: userId, date, status: status || null, note, is_absent: absent })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return alert('Xəta baş verdi');

            // Update cell in DOM
            const day = parseInt(date.split('-')[2]);
            const cell = document.getElementById('cell-' + userId + '-' + day);
            if (cell) {
                if (status && STATUSES[status]) {
                    cell.textContent = status;
                    cell.style.background = STATUSES[status].color;
                    cell.style.color = STATUSES[status].text;
                } else if (absent) {
                    cell.textContent = '';
                    cell.style.background = '#fff3cd';
                    cell.style.color = '#856404';
                } else {
                    cell.textContent = '';
                    cell.style.background = '#fff';
                    cell.style.color = '#212529';
                }
                if (note) {
                    if (!cell.querySelector('.note-dot')) {
                        const dot = document.createElement('span');
                        dot.className = 'note-dot';
                        cell.appendChild(dot);
                    }
                } else {
                    const dot = cell.querySelector('.note-dot');
                    if (dot) dot.remove();
                }
                // Update onclick
                cell.parentElement.setAttribute('onclick',
                    `openCell(${userId}, '${date}', '${cell.closest('tr').querySelector('.col-name').textContent.trim().split('\n')[0].trim()}', '${status}', '${note.replace(/'/g,"\\'")}', ${absent})`);
                cell.parentElement.setAttribute('title', note ? '📝 ' + note : '');
            }

            // Show leave info
            if (data.leave_remaining !== null) {
                const info = document.getElementById('leaveInfo');
                info.textContent = `Əmək məzuniyyəti: istifadə ${data.leave_used} gün, qalıq ${data.leave_remaining} gün`;
                info.classList.remove('d-none');
            }

            $('#cellModal').modal('hide');
        });
    }

    // ── Bulk fill ────────────────────────────────────────
    function saveBulk() {
        const select   = document.getElementById('bulk_users');
        const userIds  = Array.from(select.selectedOptions).map(o => o.value);
        const dateFrom = document.getElementById('bulk_from').value;
        const dateTo   = document.getElementById('bulk_to').value;
        const status   = document.getElementById('bulk_status').value;
        const note     = document.getElementById('bulk_note').value;

        if (!userIds.length) return alert('Ən azı bir əməkdaş seçin');
        if (!dateFrom || !dateTo) return alert('Tarix aralığı seçin');

        fetch('{{ route("attendance.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ user_ids: userIds, date_from: dateFrom, date_to: dateTo, status: status || null, note })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                $('#bulkModal').modal('hide');
                location.reload();
            } else {
                alert('Xəta baş verdi');
            }
        });
    }

    // ── Holidays ─────────────────────────────────────────
    function addHoliday() {
        const date = document.getElementById('hol_date').value;
        const name = document.getElementById('hol_name').value;
        if (!date || !name) return alert('Tarix və ad daxil edin');

        fetch('{{ route("attendance.holiday.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ date, name })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return alert('Xəta');
            const h = data.holiday;
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center py-1';
            li.id = 'hol-' + h.id;
            li.innerHTML = `<span><span class="badge badge-primary mr-1">${h.date}</span>${h.name}</span>
                <button class="btn btn-sm btn-outline-danger py-0" onclick="deleteHoliday(${h.id})"><i class="fas fa-times"></i></button>`;
            document.getElementById('holidayList').appendChild(li);
            document.getElementById('hol_date').value = '';
            document.getElementById('hol_name').value = '';
        });
    }

    function deleteHoliday(id) {
        if (!confirm('Silinsin?')) return;
        fetch(`{{ url('/attendance/holiday') }}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) document.getElementById('hol-' + id)?.remove();
        });
    }

    // ── Leave entitlements ───────────────────────────────
    function saveEntitlement(userId, year) {
        const total = document.getElementById('ent_total_' + userId).value;
        const extra = document.getElementById('ent_extra_' + userId).value;

        fetch('{{ route("attendance.entitlement") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ user_id: userId, year, total_days: parseInt(total), extra_days: parseInt(extra) })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return alert('Xəta');
            document.getElementById('ent_used_' + userId).textContent = data.used;
            const remEl = document.getElementById('ent_rem_' + userId);
            remEl.innerHTML = `<span class="badge ${data.remaining < 5 ? 'badge-danger' : 'badge-success'}">${data.remaining}</span>`;
        });
    }
</script>
@endpush
