<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
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
        $filters = [
            'user_id' => $request->get('user_id'),
            'department_id' => $request->get('department_id'),
            'service_id' => $request->get('service_id'),
            'client_id' => $request->get('client_id'),
        ];

        $user = auth()->user();

        $users = User::isActive()->get(['id', 'name', 'surname', 'position_id', 'role_id']);
        $departments = Department::get(['id', 'name']);
        $services = Service::query()
            ->when(!$user->isDeveloper() && !$user->isDirector(), function ($query) use ($user){
                $query->whereBelongsTo($user->getRelationValue('company'));
            })->get(['id', 'name', 'detail']);

        $works = Work::query()
            ->where(function($query) use ($filters){
                foreach ($filters as $column => $value) {
                    $query->when($value, function ($query, $value) use ($column) {
                        $query->where($column, $value);
                    });
                }
            })
            ->when(Work::userCannotViewAll(), function ($query) use ($user){
                $query->where('user_id', $user->getAttribute('id'))->orWhere(function ($q) use ($user){
                    $q->whereNull('user_id')->where('department_id', $user->getAttribute('department_id'));
                });
            })
            ->paginate(10);

        return view('panel.pages.works.index', compact('works', 'services', 'users', 'departments'));
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
        $work->update($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $key => $parameter) {
            $parameters[$key] = ['value' => $parameter];
        }

        $work->parameters()->sync($parameters);

        return redirect()
            ->route('works.edit', $work)
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
