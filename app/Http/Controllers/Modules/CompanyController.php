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
        return view('panel.pages.companies.index')->with([
            'companies' => Company::select(['id', 'logo', 'name'])->get()
        ]);
    }

    public function create()
    {
        return view('panel.pages.companies.edit')->with([
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

        Company::create($validated);

        return back()->with(['notify' => ['type' => 'success', 'message' => 'Created successfully']]);
    }

    public function show(Company $company)
    {
        return view('panel.pages.companies.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $company
        ]);
    }

    public function edit(Company $company)
    {
        return view('panel.pages.companies.edit')->with([
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

            if (Storage::exists($company->logo)) { Storage::delete($company->logo); }
        }

        $company->update($validated);

        return back()->with(['notify' => ['type' => 'success', 'message' => 'Updated successfully']]);
    }

    public function destroy($id)
    {
        //
    }
}
