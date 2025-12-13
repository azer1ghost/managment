<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Account;
use App\Models\Client;
use App\Models\Company;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
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
        $startOfMonth = now()->firstOfMonth()->format('Y/m/d');
        $endOfMonth = now()->format('Y/m/d');

        $limit = $request->get('limit', 25);
        $filters = [
            'search' => $request->get('search'),
            'company' => $request->get('company'),
            'client' => $request->get('client'),
            'status' => $request->get('status'),
            'user' => $request->get('user'),
            'account' => $request->get('account'),
//            'source' => $request->get('account'),
            'method' => $request->get('method'),
            'type' => $request->get('type'),
            'created_at' => $request->get('created_at') ?? $startOfMonth . ' - ' . $endOfMonth,
            'created_at_date' => $request->has('check-created_at'),
        ];
        $dateRanges = explode(' - ', $filters['created_at']);

        $transactions = Transaction::with(['client', 'user', 'company', 'account'])
            ->when($filters['search'], fn($query) => $query
                ->where('note', 'like', "%" . $filters['search'] . "%"))
            ->when($filters['company'], fn($query) => $query
                ->where('company_id', $filters['company']))
            ->when($filters['client'], fn($query) => $query
                ->where('client_id', $filters['client']))
            ->when($filters['status'], fn($query) => $query
                ->where('status', $filters['status']))
            ->when($filters['user'], fn($query) => $query
                ->where('user_id', $filters['user']))
            ->when($filters['account'], fn($query) => $query
                ->where('account_id', $filters['account']))
            ->when($filters['method'], fn($query) => $query
                ->where('method', $filters['method']))
            ->when($filters['type'], fn($query) => $query
                ->where('type', $filters['type']))
            ->when($filters['created_at_date'], fn($query) => $query
                ->where(function($q) use ($dateRanges) {
                    $q->whereBetween('transaction_date', [Carbon::parse($dateRanges[0])->startOfDay(),
                            Carbon::parse($dateRanges[1])->endOfDay()])
                      ->orWhereBetween('created_at', [Carbon::parse($dateRanges[0])->startOfDay(),
                            Carbon::parse($dateRanges[1])->endOfDay()]);
                }))
            ->orderByDesc('id')->paginate($limit);

        return view('pages.transactions.index')->with([
            'companies' => Company::get(['id', 'name', 'logo']),
            'accounts' => Account::get(['id', 'name']),
            'clients' => Client::has('works')->get(['id', 'fullname']),
            'statuses' => Transaction::statuses(),
            'types' => Transaction::types(),
            'methods' => Transaction::methods(),
            'users' => User::has('transactions')->get(['id', 'name', 'surname']),
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

