<?php

namespace App\Console\Commands;

use App\Models\BirbankCredential;
use App\Models\BirbankTransaction;
use App\Models\Company;
use Illuminate\Console\Command;

class BirbankCheckStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birbank:check-status {company_id?} {--environment=test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Birbank integration status for a company';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyId = $this->argument('company_id');
        $env = $this->option('environment');

        if ($companyId) {
            $this->showCompanyStatus((int) $companyId, $env);
        } else {
            $this->showAllStatuses($env);
        }

        return 0;
    }

    protected function showCompanyStatus(int $companyId, string $env)
    {
        $company = Company::find($companyId);
        if (!$company) {
            $this->error("Company #{$companyId} not found.");
            return;
        }

        $this->info("=== Birbank Status for Company #{$companyId} ({$company->name}) ===");
        $this->newLine();

        $credential = BirbankCredential::where('company_id', $companyId)
            ->where('env', $env)
            ->first();

        if (!$credential) {
            $this->warn("❌ No credentials found for Company #{$companyId} in {$env} environment.");
            $this->line("   Run: php artisan birbank:test-login {$companyId} --environment={$env}");
            return;
        }

        $this->line("✅ Credentials found:");
        $this->line("   Username: " . $credential->username);
        $this->line("   Environment: {$credential->env}");
        $this->line("   Auth Type: " . ($credential->auth_type ?? 'N/A'));
        $this->line("   Last Login: " . ($credential->last_login_at ? $credential->last_login_at->format('Y-m-d H:i:s') : 'Never'));
        
        if ($credential->access_token) {
            $tokenPreview = substr($credential->access_token, 0, 20) . '...';
            $this->line("   Access Token: {$tokenPreview}");
            $this->line("   Token Valid: " . ($credential->hasValidToken() ? '✅ Yes' : '❌ No/Expired'));
        } else {
            $this->warn("   ⚠️  No access token stored");
        }

        if ($credential->token_expires_at) {
            $this->line("   Token Expires: " . $credential->token_expires_at->format('Y-m-d H:i:s'));
        }

        $this->newLine();

        // Show transaction count
        $transactionCount = BirbankTransaction::where('company_id', $companyId)
            ->where('env', $env)
            ->count();

        $this->line("📊 Transactions synced: {$transactionCount}");
        
        if ($transactionCount > 0) {
            $latestTransaction = BirbankTransaction::where('company_id', $companyId)
                ->where('env', $env)
                ->latest('created_at')
                ->first();
            
            $this->line("   Latest sync: " . $latestTransaction->created_at->format('Y-m-d H:i:s'));
        }
    }

    protected function showAllStatuses(string $env)
    {
        $this->info("=== All Birbank Credentials ({$env} environment) ===");
        $this->newLine();

        $credentials = BirbankCredential::where('env', $env)
            ->with('company')
            ->get();

        if ($credentials->isEmpty()) {
            $this->warn("No credentials found for {$env} environment.");
            return;
        }

        $headers = ['Company ID', 'Company Name', 'Username', 'Has Token', 'Last Login', 'Transactions'];
        $rows = [];

        foreach ($credentials as $credential) {
            $companyName = $credential->company ? $credential->company->name : 'N/A';
            $hasToken = $credential->hasValidToken() ? '✅' : '❌';
            $lastLogin = $credential->last_login_at 
                ? $credential->last_login_at->format('Y-m-d H:i') 
                : 'Never';
            
            $transactionCount = BirbankTransaction::where('company_id', $credential->company_id)
                ->where('env', $env)
                ->count();

            $rows[] = [
                $credential->company_id,
                $companyName,
                $credential->username,
                $hasToken,
                $lastLogin,
                $transactionCount,
            ];
        }

        $this->table($headers, $rows);
    }
}

