<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\ChangeRequest;
use App\Http\Controllers\Controller;
use App\Models\Change;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;

class ChangeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Change::class, 'change');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        return view('pages.changes.index')
            ->with([
                'users' => User::get(['id', 'name', 'surname']),
                'responsible' => User::get(['id', 'name', 'surname']),
                'changes' => Change::when($search, fn ($query) => $query
                    ->where('description', 'like', "%".$search."%"))
                    ->paginate(25)]);
    }

    public function create()
    {
        return view('pages.changes.edit')->with([
            'action' => route('changes.store'),
            'method' => 'POST',
            'data' => new Change(),
            'users' =>User::isActive()->get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name']),
        ]);
    }

    public function store(ChangeRequest $request)
    {
        $change = Change::create($request->validated());

        return redirect()
            ->route('changes.edit', $change)
            ->withNotify('success', $change->getAttribute('name'));
    }

    public function show(Change $change)
    {
        return view('pages.changes.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $change,
            'users' =>User::isActive()->get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name']),
        ]);
    }

    public function edit(Change $change)
    {
        return view('pages.changes.edit')->with([
            'action' => route('changes.update', $change),
            'method' => 'PUT',
            'data' => $change,
            'users' =>User::isActive()->get(['id', 'name', 'surname']),
            'departments' => Department::get(['id', 'name']),
        ]);
    }

    public function update(ChangeRequest $request, Change $change)
    {
        $change->update($request->validated());

        return redirect()
            ->route('changes.edit', $change)
            ->withNotify('success', $change->getAttribute('name'));
    }

    public function destroy(Change $change)
    {
        if ($change->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
