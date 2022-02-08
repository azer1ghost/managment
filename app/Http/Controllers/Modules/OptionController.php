<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\OptionRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Option;
use App\Models\Parameter;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Option::class, 'option');
    }

    public function index(Request $request)
    {
        $search    =  $request->get('search');
        $limit     =  $request->get('limit', 10);
        $type      =  $request->get('type');
        $company   =  $request->get('company');

        return view('pages.options.index')
            ->with([
                'names' => Parameter::query()
                    ->where('type', 'select')
                    ->pluck('name', 'id')
                    ->map(fn($p) => str_title($p))
                    ->toArray(),
                'options' => Option::with(['parameters', 'companies'])
                    ->when($type,    fn ($query) => $query->whereHas('parameters', fn($q) => $q->where('option_parameter.parameter_id', $type)))
                    ->when($company, fn ($query) => $query->whereHas('companies',  fn($q) => $q->where('option_parameter.company_id', $company)))
                    ->when($search, fn ($query) => $query->where('text', 'like', "%$search%"))
                    ->latest('id')
                    ->paginate($limit),
                'companies' => Company::isInquirable()->pluck('name', 'id')->toArray(),
            ]);
    }

    public function create()
    {
        return view('pages.options.edit')
            ->with([
                'action' => route('options.store'),
                'method' => null,
                'data'   => null,
                'companies' => Company::isInquirable()->get(['id','name']),
                'departments' => Department::get(['id','name']),
                'parameters' => Parameter::select(['id','name'])->where('type', 'select')->pluck('name', 'id')->map(fn($p) => str_title($p)),
            ]);
    }

    public function store(OptionRequest $request)
    {
        $validated = $request->validated();

        $this->translates($validated);

        $option = Option::create($validated);

        return redirect()
            ->route('options.edit', $option)
            ->withNotify('success', $option->getAttribute('name'));
    }

    public function show(Option $option)
    {
        return view('pages.options.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $option,
                'companies' => Company::isInquirable()->select(['id','name'])->get(),
                'departments' => Department::get(['id','name']),
                'parameters' => Parameter::select(['id','name'])->where('type', 'select')->pluck('name', 'id')->map(fn($p) => str_title($p)),
            ]);
    }

    public function edit(Option $option)
    {
        return view('pages.options.edit')
            ->with([
                'action' => route('options.update', $option),
                'method' => "PUT",
                'data'   => $option,
                'companies' => Company::isInquirable()->select(['id','name'])->get(),
                'departments' => Department::get(['id','name']),
                'parameters' => Parameter::select(['id','name'])->where('type', 'select')->pluck('name', 'id')->map(fn($p) => str_title($p)),
            ]);
    }

    public function update(OptionRequest $request, Option $option)
    {
        $validated = $request->validated();

        $this->translates($validated);
        $option->update($validated);

        return back()->withNotify('info', $option->getAttribute('name'));
    }

    public function destroy(Option $option)
    {
        if ($option->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
