<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParameterRequest;
use App\Models\Company;
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
        $limit  = $request->get('limit') ?? 10;
        $type   = $request->get('type');

        return view('panel.pages.parameters.index')
            ->with([
                'types' => Parameter::distinct()->pluck('type')->flip()->map(fn($type, $key) => __("translates.parameters.types.$key"))->toArray(),
                'parameters' => Parameter::with('parameter')
                    ->when($type,   fn ($query) => $query->where('type', $type))
                    ->when($search, fn ($query) => $query->where('name', 'like', "%".ucfirst($search)."%"))
                    ->latest('id')
                    ->paginate($limit)
            ]);
    }

    public function create()
    {
        return view('panel.pages.parameters.edit')
            ->with([
                'action' => route('parameters.store'),
                'method' => null,
                'data'   => null,
                'parameters'=> array_merge(['0' => 'Nothing'], Parameter::select(['id', 'name'])->pluck('name', 'id')->toArray()),
                'companies' => Company::select(['id','name'])->get(),
                'types' => Parameter::distinct()->pluck('type')->flip()->map(fn($type, $key) => __("translates.parameters.types.$key"))->toArray()
            ]);
    }

    public function store(ParameterRequest $request)
    {
        $parameter = Parameter::create($request->validated());
        $parameter->companies()->sync($request->get('companies'));

        return redirect()
            ->route('parameters.edit', $parameter)
            ->withNotify('success', $parameter->getAttribute('name'));
    }

    public function show(Parameter $parameter)
    {
        return view('panel.pages.parameters.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $parameter,
                'parameters'=> array_merge(['0' => 'Nothing'], Parameter::select(['id', 'name'])->pluck('name', 'id')->toArray()),
                'companies' => Company::select(['id','name'])->get(),
                'types' => Parameter::distinct()->pluck('type')->flip()->map(fn($type, $key) => __("translates.parameters.types.$key"))->toArray()
            ]);
    }

    public function edit(Parameter $parameter)
    {
        return view('panel.pages.parameters.edit')
            ->with([
                'action' => route('parameters.update', $parameter),
                'method' => "PUT",
                'data'   => $parameter,
                'parameters'=> array_merge(['0' => 'Nothing'], Parameter::select(['id', 'name'])->pluck('name', 'id')->toArray()),
                'companies' => Company::select(['id','name'])->get(),
                'types' => Parameter::distinct()->pluck('type')->flip()->map(fn($type, $key) => __("translates.parameters.types.$key"))->toArray()
            ]);
    }

    public function update(ParameterRequest $request, Parameter $parameter)
    {
        $parameter->update($request->validated());

        $parameter->companies()->sync($request->get('companies'));

        return back()->withNotify('info', $parameter->getAttribute('name'));
    }

    public function destroy(Parameter $parameter)
    {
        if ($parameter->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
