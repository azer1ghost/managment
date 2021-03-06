<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Traits\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    use Permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Role::class, 'role');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');

        return view('pages.roles.index')
            ->with([
                'roles' => Role::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                    ->simplePaginate(10)
            ]);
    }

    public function create()
    {
        return view('pages.roles.edit')
            ->with([
                'action' => route('roles.store'),
                'method' => null,
                'data' => null
            ]);
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $this->permissions($validated, new Role());

        $role = Role::create($validated);

        return redirect()
            ->route('roles.edit', $role)
            ->withNotify('success', $role->getAttribute('name'));
    }

    public function show(Role $role)
    {
        return view('pages.roles.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $role
            ]);
    }

    public function edit(Role $role)
    {
        return view('pages.roles.edit')
            ->with([
                'action' => route('roles.update', $role),
                'method' => "PUT",
                'data' => $role
            ]);
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        $validated = $request->validated();

        $this->permissions($validated, $role);

        $role->update($validated);

        return back()->withNotify('info', $role->getAttribute('name'));
    }

    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
