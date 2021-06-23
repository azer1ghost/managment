<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('pages.companies.index')->with([
            'companies' => Company::select(['id', 'logo', 'name'])->get()
        ]);
    }

    public function create()
    {
        return view('pages.companies.edit')->with([
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

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
