<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\EmployeeSatisfactionRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\EmployeeSatisfaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;

class EmployeeSatisfactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(EmployeeSatisfaction::class, 'employee_satisfaction');
    }
    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $type = $request->get('type');
        $employeeSatisfaction = EmployeeSatisfaction::query()->with('users');
        if (!auth()->user()->hasPermission('measure-employeeSatisfaction')) {
            $employeeSatisfaction = $employeeSatisfaction->where('user_id', auth()->id());
        }

        return view('pages.employee-satisfactions.index')
            ->with([
                'employee_satisfactions' =>$employeeSatisfaction
                    ->when($type, fn ($q) => $q->where('type', $type))
                    ->paginate($limit),
                'types' => EmployeeSatisfaction::types()
            ]);
    }

    public function create()
    {
        return view('pages.employee-satisfactions.edit')
            ->with([
                'action' => route('employee-satisfaction.store'),
                'method' => 'POST',
                'data' => new EmployeeSatisfaction(),
                'departments' => Department::get()->pluck('name', 'id')->toArray(),
                'statuses' => EmployeeSatisfaction::statuses(),
                'users' => User::get()->pluck('name', 'surname', 'id')->toArray()
            ]);
    }

    public function store(EmployeeSatisfactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        $employeeSatisfaction = EmployeeSatisfaction::create($validated);

        return redirect()
            ->route('employee-satisfaction.index')
            ->withNotify('success', $employeeSatisfaction->getAttribute('name'));
    }

    public function show(EmployeeSatisfaction $employeeSatisfaction)
    {
        return view('pages.employee-satisfactions.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $employeeSatisfaction,
                'departments' => Department::get()->pluck('name', 'id')->toArray(),
                'statuses' => EmployeeSatisfaction::statuses(),
                'users' => User::isActive()->get()->pluck('name', 'surname', 'id')->toArray()
            ]);
    }

    public function edit(EmployeeSatisfaction $employeeSatisfaction)
    {
        return view('pages.employee-satisfactions.edit')
            ->with([
                'action' => route('employee-satisfaction.update', $employeeSatisfaction),
                'method' => "PUT",
                'data' => $employeeSatisfaction,
                'departments' => Department::get()->pluck('name', 'id')->toArray(),
                'statuses' => EmployeeSatisfaction::statuses(),
                'users' => User::isActive()->get()->pluck('name', 'surname', 'id')->toArray()
            ]);
    }

    public function update(EmployeeSatisfactionRequest $request, EmployeeSatisfaction $employeeSatisfaction): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->status == 2) {
            $validated['datetime'] = now();
        }
        $validated['more_time'] = $request->has('more_time');
        $validated['is_enough'] = $request->has('is_enough');
        $employeeSatisfaction->update($validated);
        return back()->withNotify('info', $employeeSatisfaction->getAttribute('name'));
    }

    public function destroy(EmployeeSatisfaction $employeeSatisfaction)
    {
        if ($employeeSatisfaction->delete()) {

            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
