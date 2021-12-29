<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrganizationRequest;
use App\Models\organization;
use Illuminate\Http\RedirectResponse;

class OrganizationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Organization::class, 'organization');
    }

    public function index()
    {
        return view('panel.pages.organizations.index')->with([
            'organizations' => Organization::paginate(10)
        ]);
    }

    public function create()
    {
        return view('panel.pages.organizations.edit')->with([
            'action' => route('organizations.store'),
            'method' => null,
            'data' => null,
        ]);
    }

    public function store(OrganizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $this->translates($validated);
        $validated['is_certificate'] = $request->has('is_certificate');

        $organization = Organization::create($validated);


        return redirect()
            ->route('organizations.edit', $organization)
            ->withNotify('success', $organization->getAttribute('name'));
    }

    public function show(Organization $organization)
    {
        return view('panel.pages.organizations.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $organization,
        ]);
    }

    public function edit(Organization $organization)
    {
        return view('panel.pages.organizations.edit')->with([
            'action' => route('organizations.update', $organization),
            'method' => 'PUT',
            'data' => $organization,
        ]);
    }

    public function update(OrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $validated = $request->validated();
        $this->translates($validated);
        $validated['is_certificate'] = $request->has('is_certificate');

        $organization->update($validated);

        return redirect()
            ->route('organizations.edit', $organization)
            ->withNotify('success', $organization->getAttribute('name'));
    }

    public function destroy(Organization $organization)
    {
        if ($organization->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}