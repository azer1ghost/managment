<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Company;
use App\Models\Department;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Service::class, 'service');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit',10);

        return view('panel.pages.services.index')->with([
            'services' => Service::with('department', 'company')->whereNull('service_id')->paginate($limit)
        ]);
    }

    public function create()
    {
        return view('panel.pages.services.edit')->with([
            'action' => route('services.store'),
            'method' => 'POST',
            'data' => null,
            'companies' => Company::get(['id','name']),
            'departments' => Department::get(['id','name']),
            'services' => Service::whereNull('service_id')->latest()->get(['id', 'name'])
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['has_asan_imza'] = $request->has('has_asan_imza');
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
            'services' => Service::whereNull('service_id')->where('id', '!=', $service->getAttribute('id'))->latest()->get(['id', 'name'])
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
            'services' => Service::whereNull('service_id')->where('id', '!=', $service->getAttribute('id'))->latest()->get(['id', 'name'])
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $validated = $request->validated();
        $validated['has_asan_imza'] = $request->has('has_asan_imza');
        $this->translates($validated);

        $service->update($validated);

        $parameters = [];
        foreach ($validated['parameters'] ?? [] as $parameter){
            $parameters[$parameter['id']] = [
                'show_in_table' => $parameter['show'] ?? 0,
                'show_count' => $parameter['count'] ?? 0
            ];
        }
        $service->parameters()->sync($parameters);

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
