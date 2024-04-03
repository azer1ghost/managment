<?php

namespace App\Http\Controllers\Modules;

use App\Events\EmployeeSatisfactionCreated;
use App\Http\Requests\EmployeeSatisfactionRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use App\Models\EmployeeSatisfaction;
use App\Http\Controllers\Controller;
use App\Exports\EmployeeSatisfactionsExport;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Maatwebsite\Excel\Excel;

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
            $employeeSatisfaction = $employeeSatisfaction->where(function ($query) {
                $query->where('user_id', auth()->id())->orWhere(function ($query) {
                    $query->where('type', 3)->where('user_id', auth()->id());
                });
            });
        }
        if($request->has('created_at')){
            $created_at = $request->get('created_at');
        }else{
            $created_at = now()->firstOfYear()->format('Y/m/d') . ' - ' . now()->endOfYear()->format('Y/m/d');
        }
        [$from, $to] = explode(' - ', $created_at);

        return view('pages.employee-satisfactions.index')
            ->with([
                'employeeSatisfactions' => $employeeSatisfaction
                    ->when($request->has('created_at'), fn($query) => $query->whereBetween('created_at', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]))
                    ->when($status, fn ($q) => $q->where('status', $status))
                    ->when($type, fn ($q) => $q->where('type', $type))
                    ->latest('created_at')
                    ->paginate($limit),
                'created_at' => $created_at,
                'types' => EmployeeSatisfaction::types(),
                'statuses' => EmployeeSatisfaction::statuses(),
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

        event(new EmployeeSatisfactionCreated($employeeSatisfaction));

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
    public function export(Request $request)
    {
        return \Excel::download(new EmployeeSatisfactionsExport(), 'employee-satisfactions.xlsx');
    }

}
