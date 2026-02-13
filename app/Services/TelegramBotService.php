<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    protected string $token;
    protected string $apiUrl;

    /**
     * @param string|null $token Bot token; if null, uses config('telegram.bot_token')
     */
    public function __construct(?string $token = null)
    {
        $this->token = $token ?? config('telegram.bot_token');

        if (empty($this->token)) {
            Log::error('Telegram bot token is empty! Check TELEGRAM_BOT_TOKEN or pass token.');
            throw new \RuntimeException('Telegram bot token is not configured');
        }

        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";

        Log::debug('TelegramBotService initialized', [
            'token_preview' => substr($this->token, 0, 10) . '...',
            'api_url' => $this->apiUrl,
        ]);
    }

    /**
     * Instance for Transit bot (müştəri botu)
     */
    public static function forTransit(): self
    {
        $token = config('telegram.transit_bot_token');
        if (empty($token)) {
            throw new \RuntimeException('Transit bot token is not configured. Set TELEGRAM_TRANSIT_BOT_TOKEN in .env');
        }
        return new self($token);
    }

    /**
     * Send message to Telegram chat
     */
    public function sendMessage(int $chatId, string $message, ?string $parseMode = 'HTML'): ?array
    {
        try {
            $url = "{$this->apiUrl}/sendMessage";
            $payload = [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ];

            Log::debug('Telegram sendMessage request', [
                'url' => $url,
                'chat_id' => $chatId,
                'message_length' => strlen($message),
            ]);

            $response = Http::post($url, $payload);

            if ($response->successful()) {
                Log::debug('Telegram sendMessage success', [
                    'chat_id' => $chatId,
                ]);
                return $response->json();
            }

            $errorBody = $response->body();
            $errorJson = $response->json();

            Log::error('Telegram sendMessage failed', [
                'chat_id' => $chatId,
                'status' => $response->status(),
                'response_body' => $errorBody,
                'error_code' => $errorJson['error_code'] ?? null,
                'description' => $errorJson['description'] ?? null,
                'api_url' => $url,
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Telegram sendMessage exception', [
                'chat_id' => $chatId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Set webhook URL
     */
    public function setWebhook(string $url): ?array
    {
        try {
            $response = Http::post("{$this->apiUrl}/setWebhook", [
                'url' => $url,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram setWebhook exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Get webhook info
     */
    public function getWebhookInfo(): ?array
    {
        try {
            $response = Http::get("{$this->apiUrl}/getWebhookInfo");

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram getWebhookInfo exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Answer callback query (for button clicks)
     */
    public function answerCallbackQuery(string $callbackQueryId, ?string $text = null, bool $showAlert = false): ?array
    {
        try {
            $response = Http::post("{$this->apiUrl}/answerCallbackQuery", [
                'callback_query_id' => $callbackQueryId,
                'text' => $text,
                'show_alert' => $showAlert,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Telegram answerCallbackQuery exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Send document (Excel file) to Telegram chat
     */
    public function sendDocument(int $chatId, string $filePath, ?string $caption = null): ?array
    {
        try {
            $response = Http::attach('document', file_get_contents($filePath), basename($filePath))
                ->post("{$this->apiUrl}/sendDocument", [
                    'chat_id' => $chatId,
                    'caption' => $caption,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Telegram sendDocument failed', [
                'chat_id' => $chatId,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Telegram sendDocument exception', [
                'chat_id' => $chatId,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Format work information for Telegram (without financial data)
     */
    public function formatWorkMessage(\App\Models\Work $work): string
    {
        $clientName = $work->client ? $work->client->fullname : 'Müştəri tapılmadı';
        
        // Service name və detail
        $serviceName = 'Xidmət tapılmadı';
        $serviceDetail = null;
        if ($work->service) {
            $serviceName = $work->service->getTranslation('name', app()->getLocale());
            $serviceDetail = $work->service->detail;
        }
        
        $status = $this->getStatusName($work->status);
        $code = $work->code ?: 'Kod yoxdur';
        $department = $work->department ? $work->department->name : '-';
        $user = $work->user ? ($work->user->name . ' ' . $work->user->surname) : '-';
        $datetime = $work->datetime ? $work->datetime->format('d.m.Y H:i') : '-';

        $message = "📋 <b>İş Məlumatı</b>\n\n";
        $message .= "🔹 <b>Kod:</b> {$code}\n";
        $message .= "👤 <b>Müştəri:</b> {$clientName}\n";
        $message .= "🛠 <b>Xidmət:</b> {$serviceName}\n";
        
        // Service detail varsa əlavə et
        if ($serviceDetail) {
            $serviceDetailShort = mb_substr($serviceDetail, 0, 150);
            $message .= "📄 <b>Xidmət detalları:</b> {$serviceDetailShort}\n";
        }
        
        $message .= "📊 <b>Status:</b> {$status}\n";
        $message .= "🏢 <b>Şöbə:</b> {$department}\n";
        $message .= "👨‍💼 <b>İstifadəçi:</b> {$user}\n";
        $message .= "📅 <b>Tarix:</b> {$datetime}\n";

        if ($work->detail) {
            $detail = mb_substr($work->detail, 0, 200);
            $message .= "\n📝 <b>İşin ətraflı məlumatı:</b> {$detail}";
        }

        return $message;
    }

    /**
     * Get status name in Azerbaijani
     */
    protected function getStatusName(?int $status): string
    {
        $statuses = [
            \App\Models\Work::PLANNED => 'Planlaşdırılmış',
            \App\Models\Work::PENDING => 'Gözləyən',
            \App\Models\Work::STARTED => 'Başlanmış',
            \App\Models\Work::INJECTED => 'Təsdiqlənmiş',
            \App\Models\Work::RETURNED => 'Qaytarılmış',
            \App\Models\Work::ARCHIVE => 'Arxiv',
            \App\Models\Work::DONE => 'Tamamlanmış',
            \App\Models\Work::REJECTED => 'Rədd edilmiş',
        ];

        return $statuses[$status] ?? 'Naməlum';
    }
}
