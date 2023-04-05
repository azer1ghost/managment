<?php

namespace App\Http\Controllers\Modules;

use App\Events\EmployeeSatisfactionCreated;
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
        $status = $request->get('status');
        $employeeSatisfaction = EmployeeSatisfaction::query()->with('users');
        if (!auth()->user()->hasPermission('measure-employeeSatisfaction')) {
            $employeeSatisfaction = $employeeSatisfaction->where('user_id', auth()->id())->orWhere('type', 3);
        }

        return view('pages.employee-satisfactions.index')
            ->with([
                'employeeSatisfactions' => $employeeSatisfaction
                    ->when($status, fn ($q) => $q->where('status', $status))
                    ->when($type, fn ($q) => $q->where('type', $type))
                    ->latest('created_at')
                    ->paginate($limit),
                'types' => EmployeeSatisfaction::types(),
                'statuses' => EmployeeSatisfaction::statuses()
            ]);
    }

    public function create()
    {
        return view('pages.employee-satisfactions.edit')
            ->with([
                'action' => route('employee-satisfaction.store'),
                'method' => 'POST',
                'data' => new EmployeeSatisfaction(),
                'statuses' => EmployeeSatisfaction::statuses(),
                'departments' => Department::get(['id', 'name']),
                'users' => User::isActive()->get()
            ]);
    }

    public function store(EmployeeSatisfactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['user_id'] = auth()->id();
        $validated['status'] = 1;

        $employeeSatisfaction = EmployeeSatisfaction::create($validated);

        if ($request->get('type') == EmployeeSatisfaction::INCONSISTENCY)
        {
            event(new EmployeeSatisfactionCreated($employeeSatisfaction));
        }

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
                'departments' => Department::get(['id', 'name']),
                'statuses' => EmployeeSatisfaction::statuses(),
                'users' => User::isActive()->get()
            ]);
    }

    public function edit(EmployeeSatisfaction $employeeSatisfaction)
    {
        return view('pages.employee-satisfactions.edit')
            ->with([
                'action' => route('employee-satisfaction.update', $employeeSatisfaction),
                'method' => "PUT",
                'data' => $employeeSatisfaction,
                'departments' => Department::get(['id', 'name']),
                'statuses' => EmployeeSatisfaction::statuses(),
                'users' => User::isActive()->get()
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
