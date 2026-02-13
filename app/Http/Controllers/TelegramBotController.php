<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\User;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramBotController extends Controller
{
    protected TelegramBotService $telegram;

    public function __construct(TelegramBotService $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Handle webhook from Telegram
     */
    public function webhook(Request $request)
    {
        try {
            $update = $request->all();

            // Log every webhook request for debugging
            Log::info('Telegram webhook received', [
                'update_id' => $update['update_id'] ?? null,
                'has_message' => isset($update['message']),
                'has_callback_query' => isset($update['callback_query']),
                'raw_update' => $update,
            ]);

            // Handle message
            if (isset($update['message'])) {
                $this->handleMessage($update['message']);
            }

            // Handle callback query (button clicks)
            if (isset($update['callback_query'])) {
                $this->handleCallbackQuery($update['callback_query']);
            }

            // If no message or callback, log it
            if (!isset($update['message']) && !isset($update['callback_query'])) {
                Log::warning('Telegram webhook: unknown update type', ['update' => $update]);
            }

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            Log::error('Telegram webhook exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle incoming message
     */
    protected function handleMessage(array $message): void
    {
        try {
            $chatId = $message['chat']['id'];
            $text = $message['text'] ?? '';

            Log::info('Telegram message received', [
                'chat_id' => $chatId,
                'text' => $text,
            ]);

            // Handle commands
            if (strpos($text, '/') === 0) {
                $this->handleCommand($chatId, $text);
                return;
            }

            // Default: show help
            $this->sendHelp($chatId);
        } catch (\Exception $e) {
            Log::error('Telegram handleMessage exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Handle command
     */
    protected function handleCommand(int $chatId, string $command): void
    {
        $parts = explode(' ', $command);
        $commandName = $parts[0];

        switch ($commandName) {
            case '/start':
                $this->sendWelcome($chatId);
                break;

            case '/help':
                $this->sendHelp($chatId);
                break;

            case '/works':
            case '/works_list':
                $this->sendWorksList($chatId, $parts[1] ?? null);
                break;

            case '/work':
                $workId = $parts[1] ?? null;
                if ($workId) {
                    $this->sendWorkDetails($chatId, $workId);
                } else {
                    $this->telegram->sendMessage($chatId, "❌ İstifadə: /work {iş_id}\nMəsələn: /work 123");
                }
                break;

            default:
                $this->sendHelp($chatId);
        }
    }

    /**
     * Handle callback query (button clicks)
     */
    protected function handleCallbackQuery(array $callbackQuery): void
    {
        $chatId = $callbackQuery['message']['chat']['id'];
        $callbackQueryId = $callbackQuery['id'];
        $data = $callbackQuery['data'];

        // Answer callback query first
        $this->telegram->answerCallbackQuery($callbackQueryId, 'Yüklənir...');

        // Handle callback data
        if (strpos($data, 'work_') === 0) {
            $workId = str_replace('work_', '', $data);
            $this->sendWorkDetails($chatId, $workId);
        }
    }

    /**
     * Send welcome message
     */
    protected function sendWelcome(int $chatId): void
    {
        $message = "👋 <b>Xoş gəlmisiniz!</b>\n\n";
        $message .= "Bu bot Works modulu ilə inteqrasiya olunub.\n\n";
        $message .= "📋 <b>Mövcud əmrlər:</b>\n";
        $message .= "/start - Başlanğıc mesajı\n";
        $message .= "/help - Kömək\n";
        $message .= "/works - İşlərin siyahısı\n";
        $message .= "/work {id} - İşin detalları\n\n";
        $message .= "İstifadə üçün /help yazın.";

        $this->telegram->sendMessage($chatId, $message);
    }

    /**
     * Send help message
     */
    protected function sendHelp(int $chatId): void
    {
        $message = "📚 <b>Kömək</b>\n\n";
        $message .= "<b>Əmrlər:</b>\n";
        $message .= "/start - Başlanğıc\n";
        $message .= "/help - Bu mesaj\n";
        $message .= "/works - Son 10 işin siyahısı\n";
        $message .= "/work {id} - İşin detalları\n\n";
        $message .= "<b>Nümunə:</b>\n";
        $message .= "/work 123";

        $this->telegram->sendMessage($chatId, $message);
    }

    /**
     * Send works list
     */
    protected function sendWorksList(int $chatId, ?string $limit = null): void
    {
        $limit = $limit ? (int) $limit : 10;
        $limit = min($limit, 50); // Max 50

        $works = Work::with(['client', 'service', 'department', 'user'])
            ->whereNotIn('status', [Work::PLANNED, Work::PENDING])
            ->latest('created_at')
            ->limit($limit)
            ->get();

        if ($works->isEmpty()) {
            $this->telegram->sendMessage($chatId, "❌ İş tapılmadı.");
            return;
        }

        $message = "📋 <b>İşlərin siyahısı</b> (Son {$limit})\n\n";

        foreach ($works as $work) {
            $clientName = $work->client ? mb_substr($work->client->fullname, 0, 30) : 'Müştəri yox';
            $serviceName = $work->service ? mb_substr($work->service->getTranslation('name', app()->getLocale()), 0, 20) : 'Xidmət yox';
            $statusName = $this->getStatusName($work->status);
            $code = $work->code ?: "#{$work->id}";

            $message .= "🔹 <b>{$code}</b>\n";
            $message .= "👤 {$clientName}\n";
            $message .= "🛠 {$serviceName}\n";
            $message .= "📊 {$statusName}\n";
            $message .= "📅 " . ($work->created_at ? $work->created_at->format('d.m.Y') : '-') . "\n";
            $message .= "💡 Detallar: /work {$work->id}\n\n";
        }

        $message .= "\n💡 Detallar üçün: /work {id}";

        $this->telegram->sendMessage($chatId, $message);
    }

    /**
     * Send work details
     */
    protected function sendWorkDetails(int $chatId, $workId): void
    {
        $work = Work::with(['client', 'service', 'department', 'user'])
            ->find($workId);

        if (!$work) {
            $this->telegram->sendMessage($chatId, "❌ İş tapılmadı (ID: {$workId})");
            return;
        }

        $message = $this->telegram->formatWorkMessage($work);
        $this->telegram->sendMessage($chatId, $message);
    }

    /**
     * Get status name (helper)
     */
    protected function getStatusName(?int $status): string
    {
        $statuses = [
            Work::PLANNED => 'Planlaşdırılmış',
            Work::PENDING => 'Gözləyən',
            Work::STARTED => 'Başlanmış',
            Work::INJECTED => 'Təsdiqlənmiş',
            Work::RETURNED => 'Qaytarılmış',
            Work::ARCHIVE => 'Arxiv',
            Work::DONE => 'Tamamlanmış',
            Work::REJECTED => 'Rədd edilmiş',
        ];

        return $statuses[$status] ?? 'Naməlum';
    }
}
