<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Models\Work;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Return_;

class WorkController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Work::class, 'work');
    }


    public function index()
    {
        $works = Work::paginate(10);
        return view('panel.pages.works.index')->with([
            'works' => $works
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
            'departments' => Department::get(['id', 'name'])

        ]);
    }

    public function store(WorkRequest $request)
    {
        $validated = $request->validated();
        $work = Work::create($validated);

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
            ]);

    }


    public function update(Request $request, Work $work)
    {
        $validated = $request->validated();
        $work->update($validated);

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
