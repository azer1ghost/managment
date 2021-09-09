<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Position::class, 'position');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $role = $request->get('role');
        $department = $request->get('department');

        return view('panel.pages.positions.index')
            ->with([
                'positions' => Position::with(['role', 'department'])
                    ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
                    ->when($role, fn($q) => $q->where('role_id', $role))
                    ->when($department, fn($q) => $q->where('department_id', $department))
                    ->paginate(),
                'roles' => Role::get(['id', 'name']),
                'departments' => Department::get(['id', 'name']),
            ]);
    }

    public function create()
    {
        return view('panel.pages.positions.edit')
            ->with([
                'action' => route('positions.store'),
                'method' => null,
                'data'   => null,
                'roles' => Role::pluck('name', 'id')->toArray(),
                'departments' => Department::pluck('name', 'id')->toArray(),
            ]);
    }

    public function store(PositionRequest $request)
    {
        $validated = $request->validated();

        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        $validated['permissions'] = empty(trim($validated['permissions'])) ? null : $validated['permissions'];

        $position = Position::create($request->validated());

        return redirect()
            ->route('positions.edit', $position)
            ->withNotify('success', $position->getAttribute('name'));
    }

    public function show(Position $position)
    {
        return view('panel.pages.positions.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data'   => $position,
                'roles' => Role::pluck('name', 'id')->toArray(),
                'departments' => Department::pluck('name', 'id')->toArray(),
            ]);
    }

    public function edit(Position $position)
    {
        return view('panel.pages.positions.edit')
            ->with([
                'action' => route('positions.update', $position),
                'method' => 'PUT',
                'data'   => $position,
                'roles' => Role::pluck('name', 'id')->toArray(),
                'departments' => Department::pluck('name', 'id')->toArray(),
            ]);
    }

    public function update(PositionRequest $request, Position $position)
    {
        $validated = $request->validated();

        $validated['permissions'] = array_key_exists('all_perms', $validated) ? "all" : implode(',', $validated['perms'] ?? []);
        $validated['permissions'] = empty(trim($validated['permissions'])) ? null : $validated['permissions'];

        $position->update($validated);

        return back()->withNotify('info', $position->getAttribute('name'));

    }

    public function destroy(Position $position)
    {
        if ($position->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
