<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramGetWebhookInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:webhook-info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Telegram bot webhook information';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TelegramBotService $telegram)
    {
        $this->info('Fetching webhook info...');

        $result = $telegram->getWebhookInfo();

        if ($result && ($result['ok'] ?? false)) {
            $webhookInfo = $result['result'] ?? [];
            
            $this->info('✅ Webhook Info:');
            $this->line('URL: ' . ($webhookInfo['url'] ?? 'Not set'));
            $this->line('Pending updates: ' . ($webhookInfo['pending_update_count'] ?? 0));
            
            if (isset($webhookInfo['last_error_date'])) {
                $this->error('Last error date: ' . date('Y-m-d H:i:s', $webhookInfo['last_error_date']));
                $this->error('Last error message: ' . ($webhookInfo['last_error_message'] ?? 'N/A'));
            }
            
            return 0;
        }

        $this->error('❌ Failed to get webhook info');
        $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
        return 1;
    }
}
