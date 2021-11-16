<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Service::class, 'service');
    }

    public function index()
    {
        return view('panel.pages.services.index')->with([
            'services' => Service::paginate(10)
        ]);
    }

    public function create()
    {
        return view('panel.pages.services.edit')->with([
            'action' => route('services.store'),
            'method' => 'POST',
            'data' => null,
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name'])
        ]);
    }

    public function store(ServiceRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $this->translates($validated);
        $service = Service::create($validated);

        return redirect()
            ->route('services.edit', $service)
            ->withNotify('success', $service->getAttribute('name'));
    }

    public function show(Service $service)
    {
        return view('panel.pages.services.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $service,
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
        ]);
    }

    public function edit(Service $service)
    {
        return view('panel.pages.services.edit')->with([
            'action' => route('services.update', $service),
            'method' => 'PUT',
            'data' => $service,
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
        ]);
    }

    public function update(ServiceRequest $request, Service $service): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $this->translates($validated);
        $service->update($validated);

        $service->parameters()->sync($validated['parameters'] ?? []);

        return redirect()
            ->route('services.edit', $service)
            ->withNotify('success', $service->getAttribute('name'));
    }

    public function destroy(Service $service)
    {
        if ($service->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
