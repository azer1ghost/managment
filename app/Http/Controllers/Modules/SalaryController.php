<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SalaryRequest;
use App\Models\Salary;
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
        $limit = $request->get('limit', 25);
        $search = $request->get('search');

        return view('pages.salaries.index' )->with([
            'salaries' => Salary::query()
                ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                ->paginate($limit)
        ]);
    }

    public function create()
    {
        return view('pages.salaries.edit')->with([
            'action' => route('salaries.store'),
            'method' => 'POST',
            'data' => new Salary(),
        ]);
    }

    public function store(SalaryRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_service'] = $request->has('is_service');
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
        ]);
    }

    public function edit(Salary $salary)
    {
        return view('pages.salaries.edit')->with([
            'action' => route('salaries.update', $salary),
            'method' => 'PUT',
            'data' => $salary,
        ]);
    }

    public function update(SalaryRequest $request, Salary $salary): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_service'] = $request->has('is_service');
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
