<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Models\Social;
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

        // Add social networks
        if($request->has('socials')){
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
//        dd($company->socials()->pluck('id')->toArray(), $validated['socials']);
        // TODO Socials detele not defined
        if ($request->file('logo')) {

            $image = $request->file('logo');

            $validated['logo'] = $image->storeAs('logos', $image->hashName());

            if (Storage::exists($company->logo)) {
                Storage::delete($company->logo);
            }
        }

        $company->update($validated);

        // Add, update or delete social networks
        $companySocialIds = $company->socials()->pluck('id')->toArray();
        $socials = $validated['socials'];
        if($request->has('socials')){
            $socialIds = array_map(fn($s) => $s['id'], $socials);
            $diffs     = array_diff($companySocialIds, $socialIds);
            foreach ($socials as $social):
                $company->socials()->updateOrCreate(['id' => $social['id']], $social);
            endforeach;
            Social::destroy($diffs);
        }else{
            $company->socials()->delete();
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
