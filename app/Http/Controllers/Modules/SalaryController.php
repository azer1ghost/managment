<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use App\Models\Company;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Salary::class, 'salary');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $company =$request->get('company_id');

        return view('pages.salaries.index' )->with([
            'salaries' => Salary::query()
                ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                ->when($company, fn ($query) => $query->where('company_id', $company))
                ->get()
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
