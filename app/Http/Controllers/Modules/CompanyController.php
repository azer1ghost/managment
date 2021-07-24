<?php

namespace App\Http\Controllers\Modules;

use App\Models\Company;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->authorize('viewAny', Company::class);

        return view('panel.pages.companies.index')
            ->with([
                'companies' => Company::select(['id', 'logo', 'name'])->paginate(10)
            ]);
    }

    public function create()
    {
        $this->authorize('manage', Company::class);

        return view('panel.pages.companies.edit')
            ->with([
                'action' => route('companies.store'),
                'method' => "POST",
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

        return redirect()
            ->route('companies.index')
            ->with(
                notify()->success($company->name)
            );
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
        $this->authorize('manage', $company);

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

        return back()->with(
            notify()->info($company->name)
        );
    }

    public function destroy(Company $company)
    {
        $this->authorize('manage', $company);

        if ($company->delete()) {
            if (Storage::exists($company->logo)) {
                Storage::delete($company->logo);
            }
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
