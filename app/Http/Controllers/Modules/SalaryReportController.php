<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryReportRequest;
use App\Models\Company;
use App\Models\SalaryReport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SalaryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $search = $request->get('search');

        return view('pages.salary-reports.index' )->with([
            'salaryReports' => SalaryReport::query()
                ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                ->paginate($limit)
        ]);
    }

    public function create()
    {
        return view('pages.salary-reports.edit')->with([
            'action' => route('salary-reports.store'),
            'method' => 'POST',
            'data' => new SalaryReport(),
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function store(SalaryReportRequest $request)
    {
        SalaryReport::create($request->validated());

        return response()->json(['message' => 'Salary report saved successfully'], 200);
    }

    public function show(SalaryReport $salaryReport)
    {
        return view('pages.salary-reports.edit')->with([
            'action' => route('salaryReports.store', $salaryReport),
            'method' => null,
            'data' => $salaryReport,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function edit(SalaryReport $salaryReport)
    {
        return view('pages.salary-reports.edit')->with([
            'action' => route('salaryReports.update', $salaryReport),
            'method' => 'PUT',
            'data' => $salaryReport,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
        ]);
    }

    public function update(SalaryReportRequest $request, SalaryReport $salaryReport): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_service'] = $request->has('is_service');
        $salaryReport->update($validated);

        return redirect()
            ->route('salary-reports.edit', $salaryReport)
            ->withNotify('success', $salaryReport->getAttribute('name'));
    }

    public function destroy(SalaryReport $salaryReport)
    {
        if ($salaryReport->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
