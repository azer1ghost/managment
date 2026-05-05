<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use App\Models\AttendanceRecord;
use App\Models\Company;
use App\Models\PublicHoliday;
use App\Models\Salary;
use App\Models\SalaryReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(Salary::class, 'salary');
//    }

    public function index(Request $request)
    {
        $company = $request->get('company_id');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->format('m'));
        $date = $year . '-' . $month;

        // Əgər tarix verilmişdirsə, salary_reports cədvəlindən oxu
        $existingReports = SalaryReport::query()
            ->when($company, fn ($query) => $query->where('company_id', $company))
            ->where('date', $date)
            ->get();

        // Əgər qeydiyyat varsa, onu istifadə et, yoxdursa salaries cədvəlindən oxu
        if ($existingReports->isNotEmpty()) {
            $salaries = $existingReports;
            $isExistingReport = true;
        } else {
            $salaries = Salary::query()
                ->when($company, fn ($query) => $query->where('company_id', $company))
                ->get();
            $isExistingReport = false;
        }

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = $startOfMonth->copy()->endOfMonth();

        $holidayDates = PublicHoliday::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->pluck('date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->toArray();

        $userIds = $salaries->pluck('user_id')->filter()->unique();
        $userSchedules = User::whereIn('id', $userIds)
            ->pluck('work_schedule', 'id');

        $rawRecords = AttendanceRecord::whereIn('user_id', $userIds)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy('user_id')
            ->map(fn($recs) => $recs->keyBy(fn($r) => Carbon::parse($r->date)->day));

        $attendanceData = [];
        foreach ($userIds as $uid) {
            $schedule = $userSchedules[$uid] ?? '5_day';
            $userRecords = $rawRecords[$uid] ?? collect();
            $workDays = 0;
            $absenceDays = 0;

            $current = $startOfMonth->copy();
            while ($current <= $endOfMonth) {
                $dow = $current->dayOfWeek; // 0=Sun, 6=Sat
                $isWorkDay = $schedule === '5_day'
                    ? ($dow >= 1 && $dow <= 5)
                    : ($dow >= 1 && $dow <= 6);

                if ($isWorkDay && !in_array($current->toDateString(), $holidayDates)) {
                    $workDays++;
                    $record = $userRecords->get($current->day);
                    if ($record && in_array($record->status, AttendanceRecord::$absenceStatuses)) {
                        $absenceDays++;
                    }
                }
                $current->addDay();
            }

            $actualDays = $workDays - $absenceDays;
            if ($schedule === '6_day_half') {
                $workDays   = round($workDays * 0.5, 1);
                $actualDays = round($actualDays * 0.5, 1);
            }

            $attendanceData[$uid] = ['work_days' => $workDays, 'actual_days' => $actualDays];
        }

        return view('pages.salaries.index')->with([
            'salaries' => $salaries,
            'company_id' => $company,
            'year' => $year,
            'month' => $month,
            'date' => $date,
            'isExistingReport' => $isExistingReport,
            'attendanceData' => $attendanceData,
        ]);
    }

    public function create()
    {
        return view('pages.salaries.edit')->with([
            'action' => route('salaries.store'),
            'method' => 'POST',
            'data' => new Salary(),
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function store(SalaryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $salary = Salary::create($validated);

        return redirect()
            ->route('salaries.edit', $salary)
            ->withNotify('success', $salary->getAttribute('name'));
    }

    public function show(Salary $salary)
    {
        return view('pages.salaries.edit')->with([
            'action' => route('salaries.store', $salary),
            'method' => null,
            'data' => $salary,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function edit(Salary $salary)
    {
        return view('pages.salaries.edit')->with([
            'action' => route('salaries.update', $salary),
            'method' => 'PUT',
            'data' => $salary,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function update(SalaryRequest $request, Salary $salary): RedirectResponse
    {
        $validated = $request->validated();
        $salary->update($validated);

        return redirect()
            ->route('salaries.edit', $salary)
            ->withNotify('success', $salary->getAttribute('name'));
    }

    public function destroy(Salary $salary)
    {
        if ($salary->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
