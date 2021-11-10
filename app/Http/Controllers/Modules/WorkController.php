<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Models\{Company, Department, Service, User, Work};
use Illuminate\Http\RedirectResponse;

class WorkController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Work::class, 'work');
    }

    public function index()
    {
        return view('panel.pages.works.index')->with([
            'works' => Work::paginate(10)
        ]);
    }

    public function create()
    {
        return view('panel.pages.works.edit')->with([
            'action' => route('works.store'),
            'method' => null,
            'data' => null,
            'users' => User::get(['id', 'name']),
            'companies' => Company::get(['id', 'name']),
            'departments' => Department::get(['id', 'name']),
            'services' => Service::get(['id', 'name'])
        ]);
    }

    public function store(WorkRequest $request): RedirectResponse
    {
        $work = Work::create($request->validated());
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
            'users' => User::get(['id', 'name']),
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::get(['id', 'name'])
        ]);
    }

    public function edit(Work $work)
    {
        return view('panel.pages.works.edit')->with([
            'action' => route('works.update', $work),
            'method' => 'PUT',
            'data' => $work,
            'users' => User::get(['id', 'name']),
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::get(['id', 'name'])
        ]);
    }

    public function update(WorkRequest $request, Work $work): RedirectResponse
    {
        $work->update($request->validated());

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
