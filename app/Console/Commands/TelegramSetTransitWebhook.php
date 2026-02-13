<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;

class TelegramSetTransitWebhook extends Command
{
    protected $signature = 'telegram:set-transit-webhook {url?}';
    protected $description = 'Set Transit müştəri Telegram bot webhook URL';

    public function handle()
    {
        $url = $this->argument('url') ?? config('telegram.transit_webhook_url');

        if (empty($url)) {
            $appUrl = config('app.url');
            if ($appUrl) {
                $url = rtrim($appUrl, '/') . '/api/telegram/transit-webhook';
                $this->info("Auto-detected URL from APP_URL: {$url}");
            } else {
                $this->error('Webhook URL is required. Set TELEGRAM_TRANSIT_WEBHOOK_URL in .env or pass as argument.');
                return 1;
            }
        }

        $this->info("Setting Transit bot webhook: {$url}");

        try {
            $telegram = TelegramBotService::forTransit();
        } catch (\RuntimeException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $result = $telegram->setWebhook($url);

        if ($result && ($result['ok'] ?? false)) {
            $this->info('✅ Transit bot webhook set successfully!');
            return 0;
        }

        $this->error('❌ Failed to set webhook');
        $this->line('Response: ' . json_encode($result ?? [], JSON_PRETTY_PRINT));
        return 1;
    }
}
