<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParameterRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Option;
use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Parameter::class, 'parameter');
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit  = $request->get('limit', 10);
        $type   = $request->get('type');

        return view('pages.parameters.index')
            ->with([
                'types' => Parameter::types(),
                'parameters' => Parameter::with('option')
                    ->when($type,   fn ($query) => $query->where('type', $type))
                    ->when($search, fn ($query) => $query->where('name', 'LIKE', "%$search%"))
                    ->latest('id')
                    ->paginate($limit)
            ]);
    }

    public function create()
    {
        return view('pages.parameters.edit')
            ->with([
                'action' => route('parameters.store'),
                'method' => null,
                'data'   => null,
                'options'=> Option::with([
                    'parameters' =>
                        fn($query) => $query->select(['id', 'name'])
                ])
                    ->select(['id', 'text'])->get(),
                'parameterDepartments' => collect([]),
                'departments' => Department::get(['id','name']),
                'companies' => Company::get(['id','name']),
                'types' => Parameter::types()
            ]);
    }

    public function store(ParameterRequest $request)
    {
        $validated = $request->validated();

        $parameter = Parameter::create($validated);

        $parameter->departments()->sync($validated['departments'] ?? []);

        return redirect()
            ->route('parameters.edit', $parameter)
            ->withNotify('success', $parameter->getAttribute('name'));
    }

    public function show(Parameter $parameter)
    {
        return view('pages.parameters.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $parameter,
                'options'=> Option::with([
                    'parameters' =>
                        fn($query) => $query->select(['id', 'name'])
                ])
                    ->select(['id', 'text'])->get(),
                'parameterDepartments' => $parameter->getRelationValue('departments'),
                'companies' => Company::get(['id','name']),
                'departments' => Department::get(['id','name']),
                'types' => Parameter::types()
            ]);
    }

    public function edit(Parameter $parameter)
    {
        return view('pages.parameters.edit')
            ->with([
                'action' => route('parameters.update', $parameter),
                'method' => "PUT",
                'data'   => $parameter,
                'options'=> Option::with([
                    'parameters' =>
                        fn($query) => $query->select(['id', 'name'])
                    ])
                    ->select(['id', 'text'])->get(),
                'parameterDepartments' => $parameter->getRelationValue('departments')->unique(),
                'departments' => Department::get(['id','name']),
                'companies' => Company::get(['id','name']),
                'types' => Parameter::types()
            ]);
    }

    public function update(ParameterRequest $request, Parameter $parameter)
    {
        $validated = $request->validated();

        $parameter->update($validated);

        $parameter->departments()->sync($validated['departments'] ?? []);

        self::saveParameterDepartments($parameter, $validated['companies'] ?? []);
        self::saveParameterDepartmentsOptions($parameter, $validated['options'] ?? []);

        return back()->withNotify('info', $parameter->getAttribute('name'));
    }

    protected static function saveParameterDepartments(Parameter $parameter, array $requestCompanies)
    {
        // detach all relations before adding new ones
        $parameter->companies()->detach();

        foreach ($requestCompanies as $department => $companies){
            foreach ($companies ?? [] as $company){
                $parameter->companies()->attach([
                    $company => ['department_id' => $department]
                ]);
            }
        }
    }

    protected static function saveParameterDepartmentsOptions(Parameter $parameter, array $requestOptions)
    {
        // detach all relations before adding new ones
        $parameter->options()->detach();

        foreach ($requestOptions as $department_id => $companies){
            foreach ($companies ?? [] as $company_id => $options){
                foreach ($options ?? [] as $option){
                    $parameter->options()->attach(
                        $option, [
                            'company_id' => $company_id,
                            'department_id' => $department_id
                        ]
                    );
                }
            }
        }
    }

    public function destroy(Parameter $parameter)
    {
        if ($parameter->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
