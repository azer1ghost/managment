<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Services\Birbank\BirbankApiException;
use App\Services\Birbank\BirbankClient;
use Illuminate\Console\Command;

class BirbankTestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birbank:test-login 
                            {company_id : Company ID}
                            {--environment=test : Environment (test or prod)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Birbank OAuth client_credentials login (e-Kapital)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $companyId = (int) $this->argument('company_id');
        $env = $this->option('environment');

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

        $this->info("Testing Birbank login for Company #{$companyId} ({$company->name})");
        $this->info("Environment: {$env}");
        $this->newLine();

        try {
            $client = new BirbankClient($companyId, $env);
            
            $this->info('Attempting OAuth client_credentials login...');
            $responseData = $client->login();

            $this->newLine();
            $this->info('✅ Login successful!');
            $this->newLine();
            
            $this->line('Response data (user/client info):');
            $this->line(json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $this->newLine();
            $this->info('Credentials and tokens have been saved to database.');
            $this->info('You can now use the API endpoints or sync command.');

            return 0;

        } catch (BirbankApiException $e) {
            $this->newLine();
            $this->error('❌ Login failed!');
            $this->error('Message: ' . $e->getMessage());
            
            if ($e->getStatusCode()) {
                $this->error('Status Code: ' . $e->getStatusCode());
            }

            $responseData = $e->getResponseData();
            if (!empty($responseData)) {
                $this->newLine();
                $this->line('API Response:');
                $this->line(json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }

            return 1;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}

