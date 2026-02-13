<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramSetWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:set-webhook {url?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set Telegram bot webhook URL';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(TelegramBotService $telegram)
    {
        $url = $this->argument('url') ?? config('telegram.webhook_url');

        if (empty($url)) {
            // Auto-generate URL if APP_URL is set
            $appUrl = config('app.url');
            if ($appUrl) {
                $url = rtrim($appUrl, '/') . '/api/telegram/webhook';
                $this->info("Auto-detected URL from APP_URL: {$url}");
            } else {
                $this->error('Webhook URL is required. Provide it as argument or set TELEGRAM_WEBHOOK_URL in .env');
                return 1;
            }
        }

        $this->info("Setting webhook URL: {$url}");

        $result = $telegram->setWebhook($url);

        if ($result && ($result['ok'] ?? false)) {
            $this->info('✅ Webhook set successfully!');
            $this->line('Description: ' . ($result['description'] ?? 'N/A'));
            return 0;
        }

        $this->error('❌ Failed to set webhook');
        $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));
        return 1;
    }
}
