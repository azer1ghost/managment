<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBankAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyBankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $accounts = CompanyBankAccount::with('company')->orderBy('company_id')->orderBy('label')->get();
        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('pages.finance.bank-accounts.index', compact('accounts', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('pages.finance.bank-accounts.form', [
            'account' => null,
            'companies' => $companies,
            'action' => route('bank-accounts.store'),
            'method' => 'POST',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRequest($request);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::camel($validated['label']);
        }

        CompanyBankAccount::create($validated);

        return redirect()->route('bank-accounts.index')->withNotify('success', $validated['label']);
    }

    public function edit(CompanyBankAccount $bankAccount)
    {
        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('pages.finance.bank-accounts.form', [
            'account' => $bankAccount,
            'companies' => $companies,
            'action' => route('bank-accounts.update', $bankAccount),
            'method' => 'PUT',
        ]);
    }

    public function update(Request $request, CompanyBankAccount $bankAccount): RedirectResponse
    {
        $validated = $this->validateRequest($request);
        $bankAccount->update($validated);

        return redirect()->route('bank-accounts.index')->withNotify('info', $validated['label']);
    }

    public function destroy(CompanyBankAccount $bankAccount)
    {
        if ($bankAccount->delete()) {
            return response('OK');
        }

        return response()->setStatusCode(204);
    }

    private function validateRequest(Request $request): array
    {
        return $request->validate([
            'slug' => 'nullable|string|max:100',
            'label' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'company_display_name' => 'nullable|string|max:255',
            'voen' => 'nullable|string|max:50',
            'hh' => 'nullable|string|max:100',
            'mh' => 'nullable|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'bank_kod' => 'nullable|string|max:20',
            'bank_voen' => 'nullable|string|max:50',
            'swift' => 'nullable|string|max:20',
            'who' => 'nullable|string|max:255',
            'who_footer' => 'nullable|string|max:100',
            'representer' => 'nullable|string|max:100',
            'stamp' => 'nullable|string|max:255',
        ]);
    }
}
