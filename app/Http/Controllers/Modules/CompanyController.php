<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

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
            'attribute' => null
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'logo'      => 'required|image',
            'website'   => 'required|max:255',
            'mail'      => 'required|email:rfc,dns',
            'phone'     => 'required|string|max:255',
            'mobile'    => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'about'     => 'required|string|max:255',
        ]);

        dd($validated);

//        Company::create($validated);
//
//        return back()->with(['notify' => ['type' => 'success', 'message' => 'Created successfully']]);
    }

    public function show($id)
    {
        //
    }

    public function edit(Company $company)
    {
        return view('panel.pages.companies.edit')->with([
            'action' => route('companies.update', $company),
            'method' => "PUT",
            'attribute' => $company
        ]);
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'logo'      => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'website'   => 'required|max:255',
            'mail'      => 'required|email:rfc,dns',
            'phone'     => 'required|string|max:255',
            'mobile'    => 'required|string|max:255',
            'address'   => 'required|string|max:255',
            'about'     => 'required|string|max:255',
        ]);

        if ($request->file('logo')) {

            $imagePath = $request->file('logo');
            $imageName = $imagePath->getClientOriginalName();

            $path = $request->file('logo')->storeAs('uploads', $imageName, 'public');
        }

//        $validated['logo'] = $path;

        $company->update($validated);

        return back()->with(['notify' => ['type' => 'success', 'message' => 'Updated successfully']]);
    }

    public function destroy($id)
    {
        //
    }
}
