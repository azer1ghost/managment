<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Company;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Transaction::class, 'transaction');
    }

    public function index(Request $request)
    {
        $limit = $request->get('limit', 25);
        $filters = [
            'search' => $request->get('search'),
            'company' => $request->get('company_id'),
            'status' => $request->get('status'),
            'supplier' => $request->get('supplier_id'),
        ];
        $transactions = Transaction::paginate($limit);
        return view('pages.transactions.index')->with([
            'companies' => Company::orderBy('id')->get(['id', 'name', 'logo']),
            'filters' => $filters,
            'transactions' => $transactions,
        ]);
    }

    public function create(Request $request)
    {
        if ($request->get('id')) {

            $data = Transaction::whereId($request->get('id'))->first();
        } else {
            $data = new Transaction();
        }
        return view('pages.transactions.edit')->with([
            'action' => route('transactions.store'),
            'method' => 'POST',
            'data' => $data,
            'companies' => Company::pluck('name', 'id')->toArray(),
        ]);
    }

    public function store(TransactionRequest $request)
    {
        $transaction = Transaction::create($request->validated());

        return redirect()
            ->route('transactions.edit', $transaction)
            ->withNotify('success', $transaction->getAttribute('name'));

    }

    public function show(Transaction $transaction)
    {
        return view('pages.transactions.edit')->with([
            'action' => null,
            'method' => null,
            'data' => $transaction,
            'companies' => Company::pluck('name', 'id')->toArray(),
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('pages.transactions.edit')->with([
            'action' => route('transactions.update', $transaction),
            'method' => 'PUT',
            'data' => $transaction,
            'companies' => Company::pluck('name', 'id')->toArray(),
        ]);
    }

    public function update(TransactionRequest $request, Transaction $transaction)
    {
        $validated = $request->validated();

        $transaction->update($validated);

        return redirect()
            ->route('transactions.edit', $transaction)
            ->withNotify('success', $transaction->getAttribute('name'));
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->delete()) {
            return response('OK');
        }
        return response()->setStatusCode('204');
    }
}

