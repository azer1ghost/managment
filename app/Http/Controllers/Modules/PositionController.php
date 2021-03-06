<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\PositionRequest;
use App\Traits\Permission;
use App\Models\{Department, Position, Role};
use Illuminate\Http\Request;

class PositionController extends Controller
{
    use Permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Position::class, 'position');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit  = $request->get('limit', 25);
        $role = $request->get('role');
        $department = $request->get('department');

        return view('pages.positions.index')
            ->with([
                'positions' => Position::with(['role', 'department'])
                    ->when($search, fn($q) => $q->where('name', 'LIKE', "%$search%"))
                    ->when($role, fn($q) => $q->where('role_id', $role))
                    ->when($department, fn($q) => $q->where('department_id', $department))
                    ->orderBy('order')
                    ->simplePaginate($limit),
                'roles' => Role::get(['id', 'name']),
                'departments' => Department::get(['id', 'name']),
            ]);
    }

    public function create()
    {
        return view('pages.positions.edit')
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

        $this->permissions($validated, new Position());

        $position = Position::create($validated);

        return redirect()
            ->route('positions.edit', $position)
            ->withNotify('success', $position->getAttribute('name'));
    }

    public function show(Position $position)
    {
        return view('pages.positions.edit')
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
        return view('pages.positions.edit')
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

        $this->permissions($validated, $position);

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
