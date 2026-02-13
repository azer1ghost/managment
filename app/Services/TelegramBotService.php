<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramBotService
{
    protected string $token;
    protected string $apiUrl;

    public function __construct()
    {
        $this->token = config('telegram.bot_token');
        $this->apiUrl = "https://api.telegram.org/bot{$this->token}";
    }

    /**
     * Send message to Telegram chat
     */
    public function sendMessage(int $chatId, string $message, ?string $parseMode = 'HTML'): ?array
    {
        try {
            $response = Http::post("{$this->apiUrl}/sendMessage", [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => $parseMode,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Telegram sendMessage failed', [
                'chat_id' => $chatId,
                'status' => $response->status(),
                'response' => $response->body(),
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
     * Format work information for Telegram (without financial data)
     */
    public function formatWorkMessage(\App\Models\Work $work): string
    {
        $clientName = $work->client ? $work->client->fullname : 'Müştəri tapılmadı';
        $serviceName = $work->service ? $work->service->name : 'Xidmət tapılmadı';
        $status = $this->getStatusName($work->status);
        $code = $work->code ?: 'Kod yoxdur';
        $department = $work->department ? $work->department->name : '-';
        $user = $work->user ? ($work->user->name . ' ' . $work->user->surname) : '-';
        $datetime = $work->datetime ? $work->datetime->format('d.m.Y H:i') : '-';

        $message = "📋 <b>İş Məlumatı</b>\n\n";
        $message .= "🔹 <b>Kod:</b> {$code}\n";
        $message .= "👤 <b>Müştəri:</b> {$clientName}\n";
        $message .= "🛠 <b>Xidmət:</b> {$serviceName}\n";
        $message .= "📊 <b>Status:</b> {$status}\n";
        $message .= "🏢 <b>Şöbə:</b> {$department}\n";
        $message .= "👨‍💼 <b>İstifadəçi:</b> {$user}\n";
        $message .= "📅 <b>Tarix:</b> {$datetime}\n";

        if ($work->detail) {
            $detail = mb_substr($work->detail, 0, 200);
            $message .= "\n📝 <b>Ətraflı:</b> {$detail}";
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
