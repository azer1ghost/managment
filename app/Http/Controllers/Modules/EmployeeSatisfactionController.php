<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmployeeSatisfactionRequest;
use App\Models\Department;
use App\Models\EmployeeSatisfaction;
use App\Models\Partner;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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
//        $search = $request->get('search');
//        $user = $request->get('user');

        return view('pages.employee-satisfactions.index')
            ->with([
                'employee_satisfactions' => EmployeeSatisfaction::paginate(),
                'users' => User::isActive()->get(['id', 'name', 'surname']),
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
                'departments' => Department::get()->pluck('name', 'id')->toArray()
            ]);
    }

    public function store(EmployeeSatisfactionRequest $request): RedirectResponse
    {
        $employeeSatisfaction = EmployeeSatisfaction::create($request->validated());

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
                'users' => User::oldest('name')->get(['id', 'name', 'surname', 'position_id', 'role_id']),
                'partners' => Partner::get(['id', 'name'])
            ]);
    }

    public function edit(EmployeeSatisfaction $employeeSatisfaction)
    {
        return view('pages.employee-satisfactions.edit')
            ->with([
                'action' => route('employee-satisfaction.update', $employeeSatisfaction),
                'method' => "PUT",
                'data' => $employeeSatisfaction,
                'users' => User::get(['id', 'name', 'surname', 'position_id', 'role_id']),
                'partners' => Partner::get(['id', 'name'])
            ]);
    }

    public function update(EmployeeSatisfactionRequest $request, EmployeeSatisfaction $employeeSatisfaction): RedirectResponse
    {
        $employeeSatisfaction->update($request->validated());

        return back()->withNotify('info', $employeeSatisfaction->getAttribute('name'));
    }

    public function destroy(EmployeeSatisfaction $employeeSatisfaction)
    {
        if ($employeeSatisfaction->delete()) {

            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function getAmount(EmployeeSatisfaction $employeeSatisfaction)
    {
        $client = $employeeSatisfaction->getAttribute('client_id');
        $works = Work::query()->where('client_id', $client)->whereMonth('paid_at', now()->subMonth())->get();
        if (isNull($works)){
            $sum_total_payment = 0;
        }
        foreach ($works as $work){
            /**
             * @var Work $work
             */
            $sum_payment = $work->getParameter($work::PAID) + $work->getParameter($work::ILLEGALPAID);
            $total_payment[] = $sum_payment;
            $sum_total_payment = array_sum($total_payment);
        }
        $employeeSatisfaction->setAttribute('amount',$sum_total_payment)->save();

        return redirect()->back();
    }
}
