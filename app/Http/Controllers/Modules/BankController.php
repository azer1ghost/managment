<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Company;
use Illuminate\Http\Request;

class BankController extends Controller
{

    public function __construct()
    {
//        $this->middleware('auth');
//        $this->authorizeResource(Account::class, 'account');
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'company' => $request->get('company_id'),
        ];
        $accounts = Account::query()
            ->when($filters['search'], fn($query) => $query->where('name', 'like', "%{$filters['search']}%"))
            ->when($filters['company'], fn($query) => $query->where('company_id', $filters['company']))->OrderBy('ordering')->get();
        return view('pages.accounts.index')->with([
            'companies' => Company::get(['id','name']),
            'filters' => $filters,
            'accounts' => $accounts,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->get('id')) {

            $data = Account::whereId($request->get('id'))->first();
        } else {
            $data = new Account();
        }
        return view('pages.accounts.edit')->with([
            'action' => route('banks.store'),
            'method' => 'POST',
            'data' => $data,
            'companies' => Company::pluck('name', 'id')->toArray()
        ]);
    }

    public function store(AccountRequest $request)
    {
        $account = Account::create($request->validated());

        return redirect()
            ->route('banks.edit', $account)
            ->withNotify('success', $account->getAttribute('name'));

    }

    public function show(Account $bank)
    {
        return view('pages.accounts.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $bank,
            'companies' => Company::pluck('name', 'id')->toArray(),
        ]);
    }

    public function edit(Account $bank)
    {
        return view('pages.accounts.edit')->with([
            'action' => route('banks.update', $bank),
            'method' => 'PUT',
            'data' => $bank,
            'companies' => Company::pluck('name', 'id')->toArray(),
        ]);
    }

    public function update(AccountRequest $request, Account $bank)
    {
        $bank->update($request->validated());

        return redirect()
            ->route('banks.edit', $bank)
            ->withNotify('success', $bank->getAttribute('name'));
    }

    public function destroy(Account $account)
    {
        if ($account->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }

    public function updateBankAmount(Request $request)
    {
        Account::whereId($request->get('id'))->update(['amount' => $request->get('amount')]);

        return response()->json(['message' => 'ok'], 200);
    }
    public function sortable(Request $request)
    {
        foreach ($request->get('item') as $key => $value) {
            $account = Account::find($value);
            $account->ordering = $key;
            $account->save();
        }
    }

}
