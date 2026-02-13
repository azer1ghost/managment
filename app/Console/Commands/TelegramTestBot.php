<?php

namespace App\Console\Commands;

use App\Services\TelegramBotService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class TelegramTestBot extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:test-bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Telegram bot token and API connection';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $token = config('telegram.bot_token');

        if (empty($token)) {
            $this->error('❌ TELEGRAM_BOT_TOKEN is not set in .env');
            return 1;
        }

        $this->info("Testing bot token: " . substr($token, 0, 10) . "...");

        // Test getMe endpoint
        $apiUrl = "https://api.telegram.org/bot{$token}";
        $this->info("API URL: {$apiUrl}/getMe");

        try {
            $response = Http::get("{$apiUrl}/getMe");

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['ok'] ?? false) {
                    $bot = $data['result'];
                    $this->info('✅ Bot token is valid!');
                    $this->line("Bot ID: {$bot['id']}");
                    $this->line("Bot Username: @{$bot['username']}");
                    $this->line("Bot Name: {$bot['first_name']}");
                    return 0;
                }
            }

            $errorBody = $response->body();
            $errorJson = $response->json();

            $this->error('❌ Bot token test failed!');
            $this->line("Status: {$response->status()}");
            $this->line("Error Code: " . ($errorJson['error_code'] ?? 'N/A'));
            $this->line("Description: " . ($errorJson['description'] ?? $errorBody));

            return 1;
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            return 1;
        }
    }
}
