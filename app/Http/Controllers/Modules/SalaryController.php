<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use App\Models\Company;
use App\Models\Salary;
use App\Models\SalaryReport;
use App\Models\User;
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

        return view('pages.salaries.index')->with([
            'salaries' => $salaries,
            'company_id' => $company,
            'year' => $year,
            'month' => $month,
            'date' => $date,
            'isExistingReport' => $isExistingReport,
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
