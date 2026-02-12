<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Models\BirbankCredential;
use App\Models\BirbankTransaction;
use App\Models\Company;
use App\Services\Birbank\BirbankApiException;
use App\Services\Birbank\BirbankClient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BirbankController extends Controller
{
    public function index(Request $request)
    {
        $env = $request->get('env', 'test');
        $search = $request->get('search');

        $credentials = BirbankCredential::where('env', $env)
            ->with('company')
            ->when($search, function ($query, $search) {
                $query->whereHas('company', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })->orWhere('username', 'like', "%{$search}%");
            })
            ->latest('last_login_at')
            ->paginate(25);

        // Statistics
        $stats = [
            'total_companies' => BirbankCredential::where('env', $env)->distinct('company_id')->count(),
            'with_tokens' => BirbankCredential::where('env', $env)->whereNotNull('access_token')->count(),
            'total_transactions' => BirbankTransaction::where('env', $env)->count(),
        ];

        // Get companies for dropdown
        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('pages.birbank.index')->with([
            'credentials' => $credentials,
            'env' => $env,
            'search' => $search,
            'stats' => $stats,
            'companies' => $companies,
        ]);
    }

    public function show(Company $company, Request $request)
    {
        $env = $request->get('env', 'test');

        $credential = BirbankCredential::where('company_id', $company->id)
            ->where('env', $env)
            ->first();

        // Transactions
        $transactions = BirbankTransaction::where('company_id', $company->id)
            ->where('env', $env)
            ->latest('booked_at')
            ->paginate(20);

        // Statistics
        $transactionStats = [
            'total' => BirbankTransaction::where('company_id', $company->id)->where('env', $env)->count(),
            'in' => BirbankTransaction::where('company_id', $company->id)->where('env', $env)->where('direction', 'in')->count(),
            'out' => BirbankTransaction::where('company_id', $company->id)->where('env', $env)->where('direction', 'out')->count(),
            'total_amount' => BirbankTransaction::where('company_id', $company->id)
                ->where('env', $env)
                ->where('currency', 'AZN')
                ->sum('amount'),
        ];

        // Live accounts list from Birbank API (for manual statement view)
        $accounts = [];
        $statement = [];
        $statementFilters = [
            'accountNumber' => $request->get('accountNumber'),
            'fromDate' => $request->get('fromDate'),
            'toDate' => $request->get('toDate'),
        ];

        try {
            // Only attempt API calls if we have (or can obtain) a valid token
            $client = new BirbankClient($company->id, $env);
            $accounts = $client->getAccounts();

            if ($statementFilters['accountNumber'] && $statementFilters['fromDate'] && $statementFilters['toDate']) {
                $from = Carbon::parse($statementFilters['fromDate']);
                $to = Carbon::parse($statementFilters['toDate']);
                $statement = $client->getAccountStatement($statementFilters['accountNumber'], $from, $to);
            }
        } catch (BirbankApiException $e) {
            // Live API errors will not break the page; we just won't show statement/accounts
            // Optionally, you could log or flash a notification here.
        }

        return view('pages.birbank.show')->with([
            'company' => $company,
            'credential' => $credential,
            'env' => $env,
            'transactions' => $transactions,
            'stats' => $transactionStats,
            'accounts' => $accounts,
            'statement' => $statement,
            'statementFilters' => $statementFilters,
        ]);
    }

    public function login(Request $request, $company)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'env' => 'required|in:test,prod',
        ]);

        // Handle company_id from form or route parameter
        $companyId = $request->input('company_id') ?? (is_numeric($company) ? (int) $company : $company);
        $companyModel = Company::findOrFail($companyId);

        try {
            $client = new BirbankClient($companyId, $request->env);
            $responseData = $client->login($request->username, $request->password);

            return redirect()
                ->route('birbank.show', $companyId)
                ->with('env', $request->env)
                ->withNotify('success', 'Birbank login uğurla tamamlandı!');

        } catch (BirbankApiException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('env', $request->env)
                ->withNotify('error', $e->getMessage());
        }
    }

    public function syncTransactions(Company $company, Request $request)
    {
        $request->validate([
            'env' => 'required|in:test,prod',
            'days' => 'sometimes|integer|min:1|max:365',
        ]);

        $env = $request->env;
        $days = $request->get('days', 30);

        try {
            $client = new BirbankClient($company->id, $env);

            // Get accounts (stub)
            $accounts = $client->getAccounts();

            if (empty($accounts)) {
                return redirect()
                    ->back()
                    ->with('env', $env)
                    // Using 'info' because notify() helper does not support 'warning' type
                    ->withNotify('info', 'Hesablar tapılmadı və ya endpoint hələ hazır deyil.');
            }

            $to = Carbon::now();
            $from = Carbon::now()->subDays($days);
            $totalSynced = 0;

            foreach ($accounts as $account) {
                // Support both our normalized 'account_ref' and raw API field names.
                // For statement endpoint we primarily need the core account number (custAcNo).
                $accountNumber = $account['account_number']
                    ?? $account['custAcNo']
                    ?? $account['account_ref']
                    ?? $account['ibanAcNo']
                    ?? $account['iban']
                    ?? $account['accountId']
                    ?? 'UNKNOWN';

                $accountRef = $account['account_ref'] ?? $accountNumber;

                $transactions = $client->getAccountStatement($accountNumber, $from, $to);

                if (empty($transactions)) {
                    continue;
                }

                foreach ($transactions as $transactionData) {
                    $transactionUid = $transactionData['uid'] 
                        ?? $transactionData['transaction_uid'] 
                        ?? $transactionData['id'] 
                        ?? md5(json_encode($transactionData));

                    $data = [
                        'company_id' => $company->id,
                        'env' => $env,
                        'account_ref' => $accountRef,
                        'transaction_uid' => $transactionUid,
                        'direction' => $this->extractDirection($transactionData),
                        'amount' => $this->extractAmount($transactionData),
                        'currency' => $this->extractCurrency($transactionData),
                        'booked_at' => $this->extractBookedAt($transactionData),
                        'description' => $this->extractDescription($transactionData),
                        'counterparty' => $this->extractCounterparty($transactionData),
                        'raw' => $transactionData,
                    ];

                    BirbankTransaction::updateOrCreate(
                        [
                            'company_id' => $company->id,
                            'env' => $env,
                            'account_ref' => $accountRef,
                            'transaction_uid' => $transactionUid,
                        ],
                        $data
                    );

                    $totalSynced++;
                }
            }

            return redirect()
                ->back()
                ->with('env', $env)
                ->withNotify('success', "{$totalSynced} transaction sync olundu!");

        } catch (BirbankApiException $e) {
            return redirect()
                ->back()
                ->with('env', $env)
                ->withNotify('error', $e->getMessage());
        }
    }

    protected function extractDirection(array $data): ?string
    {
        // Explicit direction if provided as 'in' / 'out'
        $direction = $data['direction'] ?? $data['type'] ?? null;
        if ($direction) {
            $direction = strtolower($direction);
            if (in_array($direction, ['in', 'out'])) {
                return $direction;
            }
        }

        // Birbank statement: drcrInd = 'D' (debit / çıxan), 'C' (credit / daxil olan)
        if (isset($data['drcrInd'])) {
            $ind = strtoupper((string) $data['drcrInd']);
            if ($ind === 'C') {
                return 'in';
            }
            if ($ind === 'D') {
                return 'out';
            }
        }

        // Fallback: infer from amount sign
        $amount = $this->extractAmount($data);
        return $amount !== null ? ($amount >= 0 ? 'in' : 'out') : null;
    }

    protected function extractAmount(array $data): ?float
    {
        $amount = $data['amount']
            ?? $data['value']
            ?? $data['sum']
            ?? $data['lcyAmount'] // Birbank statement AZN məbləği
            ?? null;
        return $amount !== null ? (float) $amount : null;
    }

    protected function extractCurrency(array $data): ?string
    {
        $currency = $data['currency']
            ?? $data['currency_code']
            ?? $data['ccy']
            ?? $data['acCcy'] // Birbank statement valyutası
            ?? null;
        return $currency ? strtoupper(substr($currency, 0, 3)) : null;
    }

    protected function extractBookedAt(array $data): ?Carbon
    {
        $date = $data['booked_at']
            ?? $data['date']
            ?? $data['transaction_date']
            ?? $data['value_date']
            ?? $data['valueDt']   // Birbank statement value date
            ?? $data['trnDt']     // Operation date
            ?? $data['txnDtTime'] // DateTime string
            ?? null;
        return $date ? Carbon::parse($date) : null;
    }

    protected function extractDescription(array $data): ?string
    {
        return $data['description'] ?? $data['details'] ?? $data['purpose'] ?? $data['narration'] ?? null;
    }

    protected function extractCounterparty(array $data): ?string
    {
        return $data['counterparty'] 
            ?? $data['counterparty_name'] 
            ?? $data['beneficiary'] 
            ?? $data['payer'] 
            ?? $data['contrAccount'] // Birbank: qarşı tərəf hesabı/adı/VOEN-i
            ?? null;
    }
}

