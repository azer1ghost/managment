<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRecord;
use App\Models\Department;
use App\Models\LeaveEntitlement;
use App\Models\PublicHoliday;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $year       = (int) $request->get('year', now()->year);
        $month      = (int) $request->get('month', now()->month);
        $departmentId = $request->get('department_id');

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();
        $daysInMonth  = $startOfMonth->daysInMonth;

        $users = User::query()
            ->whereNull('disabled_at')
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->orderBy('name')
            ->get(['id', 'name', 'surname', 'work_schedule', 'department_id']);

        $userIds = $users->pluck('id');

        $records = AttendanceRecord::query()
            ->whereIn('user_id', $userIds)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy('user_id')
            ->map(fn($recs) => $recs->keyBy(fn($r) => $r->date->day));

        $holidays = PublicHoliday::query()
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('name', 'date')
            ->mapWithKeys(fn($name, $date) => [Carbon::parse($date)->day => $name]);

        $entitlements = LeaveEntitlement::query()
            ->whereIn('user_id', $userIds)
            ->where('year', $year)
            ->get()
            ->keyBy('user_id');

        $departments = Department::orderBy('name')->get(['id', 'name']);

        return view('pages.attendance.index', compact(
            'year', 'month', 'daysInMonth', 'startOfMonth',
            'users', 'records', 'holidays', 'entitlements',
            'departments', 'departmentId'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_ids'   => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'date_from'  => 'required|date',
            'date_to'    => 'required|date|after_or_equal:date_from',
            'status'     => 'nullable|in:B,İ,E,Ə.M,X,A.M,Ö,ÜS',
            'note'       => 'nullable|string|max:500',
            'is_absent'  => 'boolean',
        ]);

        $period  = CarbonPeriod::create($request->date_from, $request->date_to);
        $updated = 0;

        foreach ($request->user_ids as $userId) {
            foreach ($period as $date) {
                AttendanceRecord::updateOrCreate(
                    ['user_id' => $userId, 'date' => $date->toDateString()],
                    [
                        'status'    => $request->status,
                        'note'      => $request->note,
                        'is_absent' => $request->boolean('is_absent'),
                    ]
                );
                $updated++;
            }
        }

        return response()->json(['success' => true, 'updated' => $updated]);
    }

    public function updateCell(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'date'      => 'required|date',
            'status'    => 'nullable|in:B,İ,E,Ə.M,X,A.M,Ö,ÜS',
            'note'      => 'nullable|string|max:500',
            'is_absent' => 'boolean',
        ]);

        $record = AttendanceRecord::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $request->date],
            [
                'status'    => $request->status ?: null,
                'note'      => $request->note,
                'is_absent' => $request->boolean('is_absent'),
            ]
        );

        $leaveUsed = null;
        if ($request->status === 'Ə.M') {
            $year = Carbon::parse($request->date)->year;
            $entitlement = LeaveEntitlement::firstOrCreate(
                ['user_id' => $request->user_id, 'year' => $year],
                ['total_days' => 21, 'extra_days' => 0]
            );
            $leaveUsed = $entitlement->usedDays($year);
            $leaveRemaining = $entitlement->remainingDays($year);
        }

        return response()->json([
            'success'        => true,
            'record'         => $record,
            'leave_used'     => $leaveUsed ?? null,
            'leave_remaining'=> $leaveRemaining ?? null,
        ]);
    }

    public function updateEntitlement(Request $request): JsonResponse
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'year'       => 'required|integer|min:2020|max:2100',
            'total_days' => 'required|integer|in:21,30',
            'extra_days' => 'required|integer|min:0|max:365',
        ]);

        $entitlement = LeaveEntitlement::updateOrCreate(
            ['user_id' => $request->user_id, 'year' => $request->year],
            ['total_days' => $request->total_days, 'extra_days' => $request->extra_days]
        );

        return response()->json([
            'success'         => true,
            'used'            => $entitlement->usedDays($request->year),
            'remaining'       => $entitlement->remainingDays($request->year),
        ]);
    }

    public function storeHoliday(Request $request): JsonResponse
    {
        $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|max:200',
        ]);

        $holiday = PublicHoliday::updateOrCreate(
            ['date' => $request->date],
            ['name' => $request->name]
        );

        return response()->json(['success' => true, 'holiday' => $holiday]);
    }

    public function destroyHoliday(PublicHoliday $holiday): JsonResponse
    {
        $holiday->delete();
        return response()->json(['success' => true]);
    }
}
