<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use Carbon\Carbon;
use App\Models\{Company, Department, Service, User, Work};
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Work::class, 'work');
    }

    public function index(Request $request)
    {
        $department = Work::userCannotViewAll() ?
                auth()->user()->getAttribute('department_id') :
                $request->get('department_id');

        $filters = [
            'user_id' => $request->get('user_id'),
            'department_id' => $department,
            'service_id' => $request->get('service_id'),
            'asan_imza_id' => $request->get('asan_imza_id'),
            'client_id' => $request->get('client_id'),
            'verified' => $request->get('verified'),
            'status' => $request->get('status'),
//            'started_at' => $request->get('started_at') ?? now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d'),
            'done_at' => $request->get('done_at') ?? now()->firstOfMonth()->format('Y/m/d') . ' - ' . now()->format('Y/m/d'),
        ];

        $dateRanges = [
//            'started_at' => explode(' - ', $filters['started_at']),
            'done_at' => explode(' - ', $filters['done_at']),
        ];

        $dateFilters = [
//            'started_at' => $request->has('check-started_at'),
            'done_at' => $request->has('check-done_at'),
        ];

        $user = auth()->user();

        $users = User::isActive()->get(['id', 'name', 'surname', 'position_id', 'role_id']);
        $departments = Department::get(['id', 'name']);
        $statuses = Work::statuses();
        $verifies = [1 => 'Unverified', 2 => 'Verified'];

        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user){
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = Work::query()
            ->where(function($query) use ($filters, $dateRanges, $dateFilters){
                foreach ($filters as $column => $value) {
                    $query->when($value, function ($query, $value) use ($column, $dateRanges, $dateFilters) {
                        if($column == 'verified'){
                            switch ($value){
                                case 1:
                                    $query->whereNull('verified_at');
                                    break;
                                case 2:
                                    $query->whereNotNull('verified_at');
                                    break;
                            }
                        }else{
                            if (is_numeric($value)){
                                $query->where($column, $value);
                            }
                            else if(is_string($value) && $dateFilters[$column]){
                                $query->whereBetween($column, [Carbon::parse($dateRanges[$column][0])->startOfDay(), Carbon::parse($dateRanges[$column][1])->endOfDay()]);
                            }
                        }
                    });
                }
            })
            ->when(Work::userCannotViewAll(), function ($query) use ($user){
                if(auth()->user()->hasPermission('viewAllDepartment-work')){
                    $query->where('department_id', $user->getAttribute('department_id'));
                }else{
                    $query->where('user_id', $user->getAttribute('id'))->orWhere(function ($q) use ($user){
                        $q->whereNull('user_id')->where('department_id', $user->getAttribute('department_id'));
                    });
                }
                $query->orWhere('creator_id', $user->getAttribute('id'));
            })
            ->latest('id')
            ->paginate(10);

        return view('panel.pages.works.index', compact('works', 'services', 'users', 'departments', 'filters', 'statuses', 'verifies'));
    }

    public function create()
    {
        return view('panel.pages.works.edit')->with([
            'action' => route('works.store'),
            'method' => null,
            'data' => null,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id', 'name']),
            'departments' => Department::get(['id', 'name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function store(WorkRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['creator_id'] = auth()->id();
        $validated['verified_at'] = $request->has('verified') ? now() : null;
        $validated['status'] = $request->filled('rejected') ? Work::REJECTED : $validated['status'];

        $work = Work::create($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);

        return redirect()
            ->route('works.edit', $work)
            ->withNotify('success', $work->getAttribute('name'));
    }

    public function show(Work $work)
    {
        return view('panel.pages.works.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $work,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function edit(Work $work)
    {
        return view('panel.pages.works.edit')->with([
            'action' => route('works.update', $work),
            'method' => 'PUT',
            'data' => $work,
            'users' => User::get(['id', 'name', 'surname']),
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::get(['id', 'name']),
        ]);
    }

    public function update(WorkRequest $request, Work $work): RedirectResponse
    {
        $validated = $request->validated();
        $validated['verified_at'] = $request->filled('verified') ? now() : null;
        $validated['status'] = $request->filled('rejected') ? Work::REJECTED : $validated['status'];
        $work->update($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);

        return redirect()
            ->route('works.show', $work)
            ->withNotify('success', $work->getAttribute('name'));
    }

    public function destroy(Work $work)
    {
        if ($work->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
