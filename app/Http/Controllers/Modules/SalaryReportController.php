<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryReportRequest;
use App\Models\SalaryReport;
use Illuminate\Http\Request;

class SalaryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $company = $request->get('company_id');
        $date = now()->year . '-' . $request->get('date-salary');
        $search = $request->get('search');
        return view('pages.salary-reports.index')->with([
            'salaryReports' => SalaryReport::query()
                ->when($search, fn($query) => $query->where('name', 'like', "%$search%"))
                ->when($company, fn($query) => $query->where('company_id', $company))
                ->when($date, fn($query) => $query->where('date', $date))
                ->get()
        ]);

    }

    public function create()
    {

    }

    public function store(SalaryReportRequest $request)
    {
        SalaryReport::create($request->validated());

        return response()->json(['message' => 'Salary report saved successfully'], 200);
    }

    public function show(SalaryReport $salaryReport)
    {

    }

    public function edit(SalaryReport $salaryReport)
    {

    }

    public function update(SalaryReportRequest $request, SalaryReport $salaryReport)
    {
        $validated = $request->validated();
        $salaryReport->update($validated);

        return response()->json(['message' => 'Salary report saved successfully'], 200);

    }

    public function destroy(SalaryReport $salaryReport)
    {
        if ($salaryReport->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
