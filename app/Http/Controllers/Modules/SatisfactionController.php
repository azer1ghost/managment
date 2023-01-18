<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\SatisfactionRequest;
use App\Models\Company;
use App\Models\Satisfaction;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SatisfactionController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth');
//        $this->authorizeResource(Satisfaction::class, 'satisfaction');
//    }

    public function index(Request $request)
    {
        return view('pages.satisfactions.index')
            ->with([
            'satisfactions' => Satisfaction::with( 'company')->get()
        ]);
    }

    public function create()
    {
        return view('pages.satisfactions.edit')->with([
            'action' => route('satisfactions.store'),
            'method' => 'POST',
            'data' => new Satisfaction(),
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function store(SatisfactionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $satisfaction = Satisfaction::create($validated);

        return redirect()
            ->route('satisfactions.edit', $satisfaction)
            ->withNotify('success', $satisfaction->getAttribute('name'));
    }

    public function show(Satisfaction $satisfaction)
    {
        return view('pages.satisfactions.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $satisfaction,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function edit(Satisfaction $satisfaction)
    {
        return view('pages.satisfactions.edit')->with([
            'action' => route('satisfactions.update', $satisfaction),
            'method' => 'PUT',
            'data' => $satisfaction,
            'companies' => Company::get(['id','name']),
        ]);
    }

    public function update(SatisfactionRequest $request, Satisfaction $satisfaction): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->has('is_active');

        $satisfaction->update($validated);

        // Satisfaction parameters
        \Cache::forget('satisfactionParameters');
        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $parameter){
            $parameters[$parameter['id']] = [
            ];
        }
        $satisfaction->parameters()->sync($parameters);

        return redirect()
            ->route('satisfactions.edit', $satisfaction)
            ->withNotify('success', $satisfaction->getAttribute('id'));
    }

    public function destroy(Satisfaction $satisfaction)
    {
        if ($satisfaction->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
