<?php

namespace App\Http\Controllers;

use App\Models\Work;
use App\Models\User;
use App\Models\Company;
use App\Services\TelegramBotService;
use App\Exports\TelegramWorksExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            $userId = $message['from']['id'] ?? null;
            $text = $message['text'] ?? '';

            // Access control: yalnız icazə verilən istifadəçilər
            if (!$this->isUserAllowed($userId)) {
                Log::warning('Telegram access denied', [
                    'user_id' => $userId,
                    'chat_id' => $chatId,
                ]);
                $this->telegram->sendMessage($chatId, "❌ Sizə bu botdan istifadə etmək üçün icazə verilməyib.");
                return;
            }

            Log::info('Telegram message received', [
                'chat_id' => $chatId,
                'user_id' => $userId,
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

            case '/search':
            case '/find':
            case '/client':
                $searchTerm = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : null;
                if ($searchTerm) {
                    $this->searchWorksByClient($chatId, $searchTerm);
                } else {
                    $this->telegram->sendMessage($chatId, "❌ İstifadə: /search {müştəri_adı}\nMəsələn: /search Əli Vəliyev");
                }
                break;

            case '/export':
            case '/excel':
                // Format: /export YYYY-MM-DD YYYY-MM-DD
                $fromDate = isset($parts[1]) ? $parts[1] : null;
                $toDate = isset($parts[2]) ? $parts[2] : null;
                if ($fromDate && $toDate) {
                    $this->exportWorksToExcel($chatId, $fromDate, $toDate);
                } else {
                    $this->telegram->sendMessage($chatId, "❌ İstifadə: /export {başlanğıc_tarix} {son_tarix}\nFormat: YYYY-MM-DD\nMəsələn: /export 2025-01-01 2025-12-31");
                }
                break;

            case '/stats':
            case '/statistics':
            case '/dovriyye':
                // Format: /stats YYYY-MM-DD YYYY-MM-DD [company_id]
                $fromDate = isset($parts[1]) ? $parts[1] : null;
                $toDate = isset($parts[2]) ? $parts[2] : null;
                $companyId = isset($parts[3]) ? (int) $parts[3] : null;
                if ($fromDate && $toDate) {
                    $this->sendMonthlyStatistics($chatId, $fromDate, $toDate, $companyId);
                } else {
                    $this->telegram->sendMessage($chatId, "❌ İstifadə: /stats {başlanğıc_tarix} {son_tarix} [şirkət_id]\nFormat: YYYY-MM-DD\nMəsələn: /stats 2025-01-01 2025-12-31\nvə ya: /stats 2025-01-01 2025-12-31 1");
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
        try {
            $chatId = $callbackQuery['message']['chat']['id'];
            $userId = $callbackQuery['from']['id'] ?? null;
            $callbackQueryId = $callbackQuery['id'];
            $data = $callbackQuery['data'] ?? '';

            // Access control: yalnız icazə verilən istifadəçilər
            if (!$this->isUserAllowed($userId)) {
                Log::warning('Telegram callback access denied', [
                    'user_id' => $userId,
                    'chat_id' => $chatId,
                ]);
                $this->telegram->answerCallbackQuery($callbackQueryId, "❌ Sizə bu botdan istifadə etmək üçün icazə verilməyib.", true);
                return;
            }

            Log::info('Telegram callback query received', [
                'chat_id' => $chatId,
                'user_id' => $userId,
                'callback_data' => $data,
            ]);

            // Answer callback query first
            $this->telegram->answerCallbackQuery($callbackQueryId, null, false);

            // Handle callback data
            if (strpos($data, 'work_') === 0) {
                $workId = (int) str_replace('work_', '', $data);
                $this->sendWorkDetails($chatId, $workId);
            } else {
                $this->telegram->sendMessage($chatId, "❌ Naməlum əmr.");
            }
        } catch (\Exception $e) {
            Log::error('Telegram handleCallbackQuery exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
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
        $message .= "/work {id} - İşin detalları\n";
        $message .= "/search {müştəri_adı} - Müştəri adına görə axtarış\n";
        $message .= "/export {başlanğıc} {son} - Excel export\n";
        $message .= "/stats {başlanğıc} {son} [şirkət_id] - Dövriyyə statistikaları\n\n";
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
        $message .= "/work {id} - İşin detalları\n";
        $message .= "/search {müştəri_adı} - Müştəri adına görə axtarış\n";
        $message .= "/export {başlanğıc} {son} - Excel export\n";
        $message .= "/stats {başlanğıc} {son} [şirkət_id] - Dövriyyə statistikaları\n\n";
        $message .= "<b>Nümunələr:</b>\n";
        $message .= "/work 123\n";
        $message .= "/search Əli Vəliyev\n";
        $message .= "/export 2025-01-01 2025-12-31\n";
        $message .= "/stats 2025-01-01 2025-12-31\n";
        $message .= "/stats 2025-01-01 2025-12-31 1";

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
            
            // Service name və detail
            $serviceName = 'Xidmət yox';
            $serviceDetail = null;
            if ($work->service) {
                $serviceName = mb_substr($work->service->getTranslation('name', app()->getLocale()), 0, 25);
                $serviceDetail = $work->service->detail;
            }
            
            $statusName = $this->getStatusName($work->status);
            $code = $work->code ?: "#{$work->id}";

            $message .= "🔹 <b>{$code}</b>\n";
            $message .= "👤 {$clientName}\n";
            $message .= "🛠 {$serviceName}\n";
            
            // Service detail varsa əlavə et (qısa)
            if ($serviceDetail) {
                $serviceDetailShort = mb_substr($serviceDetail, 0, 50);
                $message .= "📄 {$serviceDetailShort}\n";
            }
            
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
     * Search works by client name
     */
    protected function searchWorksByClient(int $chatId, string $searchTerm): void
    {
        try {
            $works = Work::with(['client', 'service', 'department', 'user'])
                ->whereHas('client', function ($query) use ($searchTerm) {
                    $query->where('fullname', 'like', "%{$searchTerm}%");
                })
                ->whereNotIn('status', [Work::PLANNED, Work::PENDING])
                ->latest('created_at')
                ->limit(20)
                ->get();

            if ($works->isEmpty()) {
                $this->telegram->sendMessage($chatId, "❌ '{$searchTerm}' adlı müştəri üçün iş tapılmadı.");
                return;
            }

            $message = "🔍 <b>Axtarış nəticələri:</b> '{$searchTerm}'\n";
            $message .= "📊 Tapılan işlərin sayı: " . $works->count() . "\n\n";

            foreach ($works as $work) {
                $clientName = $work->client ? mb_substr($work->client->fullname, 0, 30) : 'Müştəri yox';
                
                // Service name və detail
                $serviceName = 'Xidmət yox';
                $serviceDetail = null;
                if ($work->service) {
                    $serviceName = mb_substr($work->service->getTranslation('name', app()->getLocale()), 0, 25);
                    $serviceDetail = $work->service->detail;
                }
                
                $statusName = $this->getStatusName($work->status);
                $code = $work->code ?: "#{$work->id}";

                $message .= "🔹 <b>{$code}</b>\n";
                $message .= "👤 {$clientName}\n";
                $message .= "🛠 {$serviceName}\n";
                
                // Service detail varsa əlavə et (qısa)
                if ($serviceDetail) {
                    $serviceDetailShort = mb_substr($serviceDetail, 0, 50);
                    $message .= "📄 {$serviceDetailShort}\n";
                }
                
                $message .= "📊 {$statusName}\n";
                $message .= "📅 " . ($work->created_at ? $work->created_at->format('d.m.Y') : '-') . "\n";
                $message .= "💡 Detallar: /work {$work->id}\n\n";

                // Telegram mesaj limiti 4096 simvoldur, buna görə böyük siyahıları bölmək lazımdır
                if (mb_strlen($message) > 3500) {
                    $this->telegram->sendMessage($chatId, $message);
                    $message = "🔍 <b>Davam...</b>\n\n";
                }
            }

            if (mb_strlen($message) > 10) {
                $this->telegram->sendMessage($chatId, $message);
            }

            $this->telegram->sendMessage($chatId, "💡 Daha çox məlumat üçün: /work {id}");
        } catch (\Exception $e) {
            Log::error('Telegram searchWorksByClient exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->telegram->sendMessage($chatId, "❌ Xəta baş verdi. Zəhmət olmasa yenidən cəhd edin.");
        }
    }

    /**
     * Export works to Excel and send via Telegram
     */
    protected function exportWorksToExcel(int $chatId, string $fromDate, string $toDate): void
    {
        try {
            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);

            $this->telegram->sendMessage($chatId, "⏳ Excel faylı hazırlanır...");

            $filename = 'works_export_' . $from->format('Y-m-d') . '_' . $to->format('Y-m-d') . '_' . time() . '.xlsx';
            $filePath = storage_path('app/temp/' . $filename);

            // Temp directory yarat
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            Excel::store(new TelegramWorksExport($from, $to), 'temp/' . $filename, 'local');

            $caption = "📊 İşlərin Excel export-u\n";
            $caption .= "📅 Tarix aralığı: {$from->format('d.m.Y')} - {$to->format('d.m.Y')}";

            $result = $this->telegram->sendDocument($chatId, $filePath, $caption);

            // Temp faylı sil
            @unlink($filePath);

            if ($result) {
                Log::info('Telegram Excel export sent', ['chat_id' => $chatId, 'from' => $fromDate, 'to' => $toDate]);
            } else {
                $this->telegram->sendMessage($chatId, "❌ Excel faylı göndərilə bilmədi. Zəhmət olmasa yenidən cəhd edin.");
            }
        } catch (\Exception $e) {
            Log::error('Telegram exportWorksToExcel exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->telegram->sendMessage($chatId, "❌ Xəta: " . $e->getMessage());
        }
    }

    /**
     * Send monthly turnover statistics
     */
    protected function sendMonthlyStatistics(int $chatId, string $fromDate, string $toDate, ?int $companyId = null): void
    {
        try {
            $from = Carbon::parse($fromDate);
            $to = Carbon::parse($toDate);

            $this->telegram->sendMessage($chatId, "⏳ Statistikalar hesablanır...");

            // SQL sorğusu - SQL-dən birbaşa adaptasiya
            $sql = "
                SELECT
                    w.id AS work_id,
                    w.client_id,
                    ANY_VALUE(c.fullname) AS client_name,
                    ANY_VALUE(w.created_at) AS created_at,
                    ANY_VALUE(ai.company_id) AS asan_company_id,
                    MAX(CASE WHEN wp.parameter_id = 33 THEN CAST(wp.value AS DECIMAL(15,2)) END) AS mebleg,
                    MAX(CASE WHEN wp.parameter_id = 34 THEN CAST(wp.value AS DECIMAL(15,2)) END) AS edv,
                    MAX(CASE WHEN wp.parameter_id = 35 THEN CAST(wp.value AS DECIMAL(15,2)) END) AS esas_mebleg_odenilen,
                    MAX(CASE WHEN wp.parameter_id = 36 THEN CAST(wp.value AS DECIMAL(15,2)) END) AS edv_odenilen,
                    MAX(CASE WHEN wp.parameter_id = 55 THEN CAST(wp.value AS DECIMAL(15,2)) END) AS umumi_odenis
                FROM works w
                JOIN clients c ON c.id = w.client_id
                LEFT JOIN work_parameter wp ON wp.work_id = w.id
                LEFT JOIN asan_imzalar ai ON ai.id = w.asan_imza_id
                WHERE w.deleted_at IS NULL
                  AND c.deleted_at IS NULL
                  AND w.created_at >= ?
                  AND w.created_at < ?
            ";

            $params = [$from->startOfDay(), $to->endOfDay()];

            // Şirkət filteri
            if ($companyId) {
                $sql .= " AND ai.company_id = ?";
                $params[] = $companyId;
            }

            $sql .= " GROUP BY w.id ORDER BY w.created_at DESC";

            $works = collect(DB::select($sql, $params));

            // Aylıq qruplaşdırma
            $monthlyStats = [];
            $companyStats = [];

            foreach ($works as $work) {
                $month = Carbon::parse($work->created_at)->format('Y-m');
                $asanCompanyId = $work->asan_company_id;

                // Aylıq statistikalar
                if (!isset($monthlyStats[$month])) {
                    $monthlyStats[$month] = [
                        'count' => 0,
                        'total_mebleg' => 0,
                        'total_edv' => 0,
                        'total_odenilen' => 0,
                        'total_umumi_odenis' => 0,
                    ];
                }

                $monthlyStats[$month]['count']++;
                $monthlyStats[$month]['total_mebleg'] += ($work->mebleg ?? 0);
                $monthlyStats[$month]['total_edv'] += ($work->edv ?? 0);
                $monthlyStats[$month]['total_odenilen'] += ($work->esas_mebleg_odenilen ?? 0) + ($work->edv_odenilen ?? 0);
                $monthlyStats[$month]['total_umumi_odenis'] += ($work->umumi_odenis ?? 0);

                // Şirkət statistikaları
                if ($asanCompanyId) {
                    if (!isset($companyStats[$asanCompanyId])) {
                        $company = Company::find($asanCompanyId);
                        $companyStats[$asanCompanyId] = [
                            'name' => $company ? $company->name : "Şirkət #{$asanCompanyId}",
                            'count' => 0,
                            'total_mebleg' => 0,
                            'total_edv' => 0,
                            'total_odenilen' => 0,
                            'total_umumi_odenis' => 0,
                        ];
                    }

                    $companyStats[$asanCompanyId]['count']++;
                    $companyStats[$asanCompanyId]['total_mebleg'] += ($work->mebleg ?? 0);
                    $companyStats[$asanCompanyId]['total_edv'] += ($work->edv ?? 0);
                    $companyStats[$asanCompanyId]['total_odenilen'] += ($work->esas_mebleg_odenilen ?? 0) + ($work->edv_odenilen ?? 0);
                    $companyStats[$asanCompanyId]['total_umumi_odenis'] += ($work->umumi_odenis ?? 0);
                }
            }

            // Mesaj formatlaşdırma
            $message = "📊 <b>Dövriyyə Statistikaları</b>\n\n";
            $message .= "📅 Tarix aralığı: {$from->format('d.m.Y')} - {$to->format('d.m.Y')}\n";
            $message .= "📋 Ümumi iş sayı: " . $works->count() . "\n\n";

            // Ümumi cəmi
            $totalMeb = $works->sum(function($w) { return $w->mebleg ?? 0; });
            $totalEdv = $works->sum(function($w) { return $w->edv ?? 0; });
            $totalOdenilen = $works->sum(function($w) { return ($w->esas_mebleg_odenilen ?? 0) + ($w->edv_odenilen ?? 0); });
            $totalUmumiOdenis = $works->sum(function($w) { return $w->umumi_odenis ?? 0; });

            $message .= "💰 <b>Ümumi məbləğlər:</b>\n";
            $message .= "💵 Məbləğ: " . number_format($totalMeb, 2) . " AZN\n";
            $message .= "📄 ƏDV: " . number_format($totalEdv, 2) . " AZN\n";
            $message .= "✅ Ödənilmiş: " . number_format($totalOdenilen, 2) . " AZN\n";
            $message .= "💳 Ümumi ödəniş: " . number_format($totalUmumiOdenis, 2) . " AZN\n\n";

            // Aylıq statistikalar
            if (!empty($monthlyStats)) {
                $message .= "📅 <b>Aylıq dövriyyə:</b>\n";
                ksort($monthlyStats);
                foreach ($monthlyStats as $month => $stats) {
                    $monthName = Carbon::parse($month . '-01')->locale('az')->translatedFormat('F Y');
                    $message .= "\n📆 <b>{$monthName}</b>\n";
                    $message .= "   İş sayı: {$stats['count']}\n";
                    $message .= "   Məbləğ: " . number_format($stats['total_mebleg'], 2) . " AZN\n";
                    $message .= "   Ödənilmiş: " . number_format($stats['total_odenilen'], 2) . " AZN\n";

                    if (mb_strlen($message) > 3500) {
                        $this->telegram->sendMessage($chatId, $message);
                        $message = "📅 <b>Aylıq dövriyyə (davam):</b>\n";
                    }
                }
            }

            // Şirkət statistikaları (yalnız ümumi sorğuda)
            if (empty($companyId) && !empty($companyStats)) {
                $message .= "\n\n🏢 <b>Şirkətlərə görə:</b>\n";
                foreach ($companyStats as $companyId => $stats) {
                    $message .= "\n🏛 <b>{$stats['name']}</b>\n";
                    $message .= "   İş sayı: {$stats['count']}\n";
                    $message .= "   Məbləğ: " . number_format($stats['total_mebleg'], 2) . " AZN\n";
                    $message .= "   Ödənilmiş: " . number_format($stats['total_odenilen'], 2) . " AZN\n";

                    if (mb_strlen($message) > 3500) {
                        $this->telegram->sendMessage($chatId, $message);
                        $message = "🏢 <b>Şirkətlərə görə (davam):</b>\n";
                    }
                }
            }

            $this->telegram->sendMessage($chatId, $message);

        } catch (\Exception $e) {
            Log::error('Telegram sendMonthlyStatistics exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->telegram->sendMessage($chatId, "❌ Xəta: " . $e->getMessage());
        }
    }

    /**
     * Check if user is allowed to use the bot
     */
    protected function isUserAllowed(?int $userId): bool
    {
        if ($userId === null) {
            return false;
        }

        $allowedUserIds = config('telegram.allowed_user_ids', []);

        // Əgər allowed_user_ids boşdursa, hər kəsə icazə ver (backward compatibility)
        if (empty($allowedUserIds)) {
            return true;
        }

        return in_array((string) $userId, $allowedUserIds, true);
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
