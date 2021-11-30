<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Traits\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;


class DepartmentController extends Controller
{
    use Permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Department::class, 'department');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit  = $request->get('limit', 25);


        return view('panel.pages.departments.index')
            ->with([
                'departments' => Department::query()
                    ->when($search, fn ($query) => $query->where('name', 'like', "%$search%"))
                    ->simplePaginate($limit),
            ]);
    }

    public function create()
    {
        return view('panel.pages.departments.edit')
            ->with([
                'action' => route('departments.store'),
                'method' => null,
                'data' => null
            ]);
    }

    public function store(DepartmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->translates($validated);
        $this->permissions($validated, new Department());

        $validated['status'] = $request->has('status');

        $department = Department::create($validated);

        return redirect()
            ->route('departments.edit', $department)
            ->withNotify('success', $department->getAttribute('name'));
    }

    public function show(Department $department)
    {
        return view('panel.pages.departments.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $department
            ]);
    }

    public function edit(Department $department)
    {
        return view('panel.pages.departments.edit')
            ->with([
                'action' => route('departments.update', $department),
                'method' => "PUT",
                'data' => $department
            ]);
    }

    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        $validated = $request->validated();
        $this->translates($validated);
        $this->permissions($validated, $department);

        $validated['status'] = $request->has('status');

        $department->update($validated);

        return back()->withNotify('info', $department->getAttribute('name'));
    }

    public function destroy(Department $department)
    {
        if ($department->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
