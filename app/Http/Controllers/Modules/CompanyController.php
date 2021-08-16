<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Company::class, 'company');
    }

    public function index()
    {
        return view('panel.pages.companies.index')
            ->with([
                'companies' => Company::select(['id', 'logo', 'name'])->simplePaginate(10)
            ]);
    }

    public function create()
    {
        return view('panel.pages.companies.edit')
            ->with([
                'action' => route('companies.store'),
                'method' => null,
                'data' => null
            ]);
    }

    public function store(CompanyRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->file('logo')) {

            $image = $request->file('logo');

            $validated['logo'] = $image->storeAs('logos', $image->hashName());
        }

        $company = Company::create($validated);

        // Add or update social networks
        if(array_key_exists('socials', $validated)){
            $company->socials()->createMany($validated['socials']);
        }

        return redirect()
            ->route('companies.index')
            ->withNotify('success', $company->getAttribute('name'));
    }

    public function show(Company $company)
    {
        return view('panel.pages.companies.edit')
            ->with([
                'action' => null,
                'method' => null,
                'data' => $company
            ]);
    }

    public function edit(Company $company)
    {
        return view('panel.pages.companies.edit')
            ->with([
                'action' => route('companies.update', $company),
                'method' => "PUT",
                'data' => $company
            ]);
    }

    public function update(CompanyRequest $request, Company $company): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->file('logo')) {

            $image = $request->file('logo');

            $validated['logo'] = $image->storeAs('logos', $image->hashName());

            if (Storage::exists($company->logo)) {
                Storage::delete($company->logo);
            }
        }

        $company->update($validated);

        // Add or update social networks
        if(array_key_exists('socials', $validated)){
            collect($validated['socials'])->each(function ($social) use ($company){
                $company->socials()->updateOrCreate(['id' => $social['id']], $social);
            });
        }

        return back()->withNotify('info', $company->getAttribute('name'));
    }

    public function destroy(Company $company)
    {
        if ($company->delete()) {
            if (Storage::exists($company->logo)) {
                Storage::delete($company->logo);
            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
