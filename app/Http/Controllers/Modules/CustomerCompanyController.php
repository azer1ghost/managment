<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCompanyRequest;
use App\Models\CustomerCompany;
use Illuminate\Http\RedirectResponse;

class CustomerCompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(CustomerCompany::class, 'customer_company');
    }
    public function index()
    {
        return view('panel.pages.customer-companies.index')->with([
            'customerCompanies' => CustomerCompany::paginate(10)
        ]);
    }

    public function create()
    {
        return view('panel.pages.customer-companies.edit')->with([
            'action' => route('customer-companies.store'),
            'method' => null,
            'data' => null,
        ]);
    }

    public function store(CustomerCompanyRequest $request): RedirectResponse
    {
        $customerCompany = CustomerCompany::create($request->validated());

        return redirect()
            ->route('customer-companies.edit', $customerCompany)
            ->withNotify('success', $customerCompany->getAttribute('name'));
    }

    public function show(CustomerCompany $customerCompany)
    {
        return view('panel.pages.customer-companies.edit')->with([
            'action' => route('customer-companies.store', $customerCompany),
            'method' => null,
            'data' => $customerCompany,
        ]);
    }

    public function edit(CustomerCompany $customerCompany)
    {
        return view('panel.pages.customer-companies.edit')->with([
            'action' => route('customer-companies.update', $customerCompany),
            'method' => 'PUT',
            'data' => $customerCompany,
        ]);
    }

    public function update(CustomerCompanyRequest $request, CustomerCompany $customerCompany): RedirectResponse
    {
        $customerCompany->update($request->validated());

        return redirect()
            ->route('customer-companies.edit', $customerCompany)
            ->withNotify('success', $customerCompany->getAttribute('name'));
    }

    public function destroy(CustomerCompany $customerCompany)
    {
        if ($customerCompany->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}
