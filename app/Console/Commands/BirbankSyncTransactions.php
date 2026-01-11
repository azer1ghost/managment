<?php

namespace App\Console\Commands;

use App\Models\BirbankTransaction;
use App\Models\Company;
use App\Services\Birbank\BirbankApiException;
use App\Services\Birbank\BirbankClient;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BirbankSyncTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birbank:sync-transactions {company_id} {--env=test} {--days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Birbank transactions for a company';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyId = (int) $this->argument('company_id');
        $env = $this->option('env');
        $days = (int) $this->option('days');

        // Validate environment
        if (!in_array($env, ['test', 'prod'])) {
            $this->error("Invalid environment: {$env}. Must be 'test' or 'prod'.");
            return 1;
        }

        // Validate company exists
        $company = Company::find($companyId);
        if (!$company) {
            $this->error("Company with ID {$companyId} not found.");
            return 1;
        }

        $this->info("Syncing Birbank transactions for Company #{$companyId} ({$company->name})");
        $this->info("Environment: {$env}");
        $this->info("Date range: Last {$days} days");

        try {
            $client = new BirbankClient($companyId, $env);

            // Get accounts (stub - will return empty array for now)
            $this->info("Fetching accounts...");
            $accounts = $client->getAccounts();

            if (empty($accounts)) {
                $this->warn("No accounts found or endpoint not yet implemented.");
                $this->info("Using placeholder account for testing...");
                // For testing, use a placeholder
                $accounts = [['account_ref' => 'PLACEHOLDER_ACCOUNT']];
            }

            $this->info("Found " . count($accounts) . " account(s)");

            // Calculate date range
            $to = Carbon::now();
            $from = Carbon::now()->subDays($days);

            $totalSynced = 0;

            foreach ($accounts as $account) {
                $accountRef = $account['account_ref'] ?? $account['iban'] ?? $account['accountId'] ?? 'UNKNOWN';

                $this->info("Fetching transactions for account: {$accountRef}");

                // Get account statement (stub - will return empty array for now)
                $transactions = $client->getAccountStatement($accountRef, $from, $to);

                if (empty($transactions)) {
                    $this->warn("No transactions found or endpoint not yet implemented for account: {$accountRef}");
                    continue;
                }

                $this->info("Found " . count($transactions) . " transaction(s) for account: {$accountRef}");

                // Upsert transactions
                $synced = $this->upsertTransactions($companyId, $env, $accountRef, $transactions);
                $totalSynced += $synced;

                $this->info("Synced {$synced} transaction(s) for account: {$accountRef}");
            }

            $this->info("Sync completed. Total transactions synced: {$totalSynced}");

            return 0;

        } catch (BirbankApiException $e) {
            $this->error("Birbank API error: " . $e->getMessage());
            if ($e->getStatusCode()) {
                $this->error("Status code: " . $e->getStatusCode());
            }
            return 1;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Upsert transactions into database.
     *
     * @param int $companyId
     * @param string $env
     * @param string $accountRef
     * @param array $transactions
     * @return int Number of transactions synced
     */
    protected function upsertTransactions(int $companyId, string $env, string $accountRef, array $transactions): int
    {
        $synced = 0;

        DB::beginTransaction();
        try {
            foreach ($transactions as $transactionData) {
                // Extract transaction UID (adjust based on actual API response structure)
                $transactionUid = $transactionData['uid'] 
                    ?? $transactionData['transaction_uid'] 
                    ?? $transactionData['id'] 
                    ?? md5(json_encode($transactionData));

                // Prepare transaction data
                $data = [
                    'company_id' => $companyId,
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

                // Upsert using unique constraint
                BirbankTransaction::updateOrCreate(
                    [
                        'company_id' => $companyId,
                        'env' => $env,
                        'account_ref' => $accountRef,
                        'transaction_uid' => $transactionUid,
                    ],
                    $data
                );

                $synced++;
            }

            DB::commit();
            return $synced;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Extract direction from transaction data.
     */
    protected function extractDirection(array $data): ?string
    {
        $direction = $data['direction'] ?? $data['type'] ?? null;
        
        if ($direction) {
            $direction = strtolower($direction);
            return in_array($direction, ['in', 'out']) ? $direction : null;
        }

        // Try to infer from amount
        $amount = $this->extractAmount($data);
        if ($amount !== null) {
            return $amount >= 0 ? 'in' : 'out';
        }

        return null;
    }

    /**
     * Extract amount from transaction data.
     */
    protected function extractAmount(array $data): ?float
    {
        $amount = $data['amount'] ?? $data['value'] ?? $data['sum'] ?? null;
        return $amount !== null ? (float) $amount : null;
    }

    /**
     * Extract currency from transaction data.
     */
    protected function extractCurrency(array $data): ?string
    {
        $currency = $data['currency'] ?? $data['currency_code'] ?? $data['ccy'] ?? null;
        return $currency ? strtoupper(substr($currency, 0, 3)) : null;
    }

    /**
     * Extract booked_at timestamp from transaction data.
     */
    protected function extractBookedAt(array $data): ?Carbon
    {
        $date = $data['booked_at'] ?? $data['date'] ?? $data['transaction_date'] ?? $data['value_date'] ?? null;
        
        if (!$date) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Extract description from transaction data.
     */
    protected function extractDescription(array $data): ?string
    {
        return $data['description'] ?? $data['details'] ?? $data['purpose'] ?? $data['narration'] ?? null;
    }

    /**
     * Extract counterparty from transaction data.
     */
    protected function extractCounterparty(array $data): ?string
    {
        return $data['counterparty'] 
            ?? $data['counterparty_name'] 
            ?? $data['beneficiary'] 
            ?? $data['payer'] 
            ?? null;
    }
}
