<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\CompanyRequest;
use App\Http\Requests\ParameterRequest;
use App\Models\Company;
use App\Models\Parameter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ParameterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Parameter::class, 'parameter');
    }

    public function index()
    {
        return view('panel.pages.parameters.index')
            ->with([
                'parameters' => Parameter::with('parameter')->latest('id')->simplePaginate(10)
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
                'types' => Parameter::distinct()->get(['type'])->pluck('type')->toArray()
            ]);
    }

    public function store(ParameterRequest $request)
    {

        $parameter = Parameter::create($request->validated());

        return redirect()
            ->route('parameters.index')
            ->with(
                notify()->success($parameter->getAttribute('name'))
            );
    }

    public function show(Parameter $parameter)
    {
        return view('panel.pages.parameters.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $parameter
            ]);
    }

    public function edit(Parameter $parameter)
    {
        dd(

            Parameter::distinct()->get(['type'])->pluck('type')->map(function($type, $key){
                return [$type => $type];
            })->toArray()

        );

        return view('panel.pages.parameters.edit')
            ->with([
                'action' => route('parameters.update', $parameter),
                'method' => "PUT",
                'data'   => $parameter,
                'parameters'=> array_merge(['0' => 'Nothing'], Parameter::select(['id', 'name'])->pluck('name', 'id')->toArray()),
                'types' => Parameter::distinct()->get(['type'])->pluck('type')->toArray()
            ]);
    }

    public function update(ParameterRequest $request, Parameter $parameter)
    {
        $parameter->update($request->validated());

        return back()->with(
            notify()->info($parameter->getAttribute('name'))
        );
    }

    public function destroy(Parameter $parameter)
    {
        if ($parameter->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
