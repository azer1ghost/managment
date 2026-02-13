<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TransitCustomer;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Transit müştərilər üçün ayrı Telegram bot (yalnız /link, /profile, /orders, /beyanname).
 */
class TransitTelegramBotController extends Controller
{
    protected TelegramBotService $telegram;

    public function __construct()
    {
        $this->telegram = TelegramBotService::forTransit();
    }

    public function webhook(Request $request)
    {
        try {
            $update = $request->all();

            Log::info('Telegram Transit webhook received', [
                'update_id' => $update['update_id'] ?? null,
                'has_message' => isset($update['message']),
            ]);

            if (isset($update['message'])) {
                $this->handleMessage($update['message']);
            }

            return response()->json(['ok' => true]);
        } catch (\Exception $e) {
            Log::error('Telegram Transit webhook exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
        }
    }

    protected function handleMessage(array $message): void
    {
        $chatId = (int) $message['chat']['id'];
        $text = trim($message['text'] ?? '');

        Log::info('Telegram Transit message', ['chat_id' => $chatId, 'text' => $text]);

        // /link <6 rəqəm>
        if (preg_match('#^/link\s+(\d{6})$#', $text, $m)) {
            $this->handleLink($chatId, $m[1]);
            return;
        }

        $customer = $this->getTransitCustomerByChatId($chatId);
        if ($customer) {
            if (strpos($text, '/') === 0) {
                $this->handleCommand($chatId, $text, $customer);
            } else {
                $this->sendHelp($chatId);
            }
            return;
        }

        // Qoşulmayıb — yalnız /start və /help-də təlimat
        if (in_array(explode(' ', $text)[0] ?? '', ['/start', '/help'], true)) {
            $this->sendLinkInstruction($chatId);
        } else {
            $this->telegram->sendMessage($chatId, "❌ Əvvəlcə saytda Profil → Telegram bölməsindən kod yaradıb burada /link KOD yazmalısınız.");
        }
    }

    protected function handleLink(int $chatId, string $code): void
    {
        $customer = TransitCustomer::where('telegram_link_code', $code)
            ->whereNotNull('telegram_link_code_expires_at')
            ->where('telegram_link_code_expires_at', '>', now())
            ->first();

        if (!$customer) {
            $this->telegram->sendMessage($chatId, "❌ Kod etibarsızdır və ya vaxtı keçib. Saytda Profil → Telegram bölməsindən yeni kod yaradın.");
            return;
        }

        $customer->update([
            'telegram_chat_id' => $chatId,
            'telegram_link_code' => null,
            'telegram_link_code_expires_at' => null,
        ]);

        $this->telegram->sendMessage($chatId, "✅ Hesabınız Telegram-a qoşuldu. Artıq /profile, /orders və /beyanname əmrlərindən istifadə edə bilərsiniz.");
        $this->sendHelp($chatId);
    }

    protected function getTransitCustomerByChatId(int $chatId): ?TransitCustomer
    {
        return TransitCustomer::where('telegram_chat_id', $chatId)->first();
    }

    protected function handleCommand(int $chatId, string $command, TransitCustomer $customer): void
    {
        $parts = explode(' ', $command);
        $cmd = $parts[0];

        switch ($cmd) {
            case '/start':
                $this->sendWelcome($chatId, $customer);
                break;
            case '/help':
                $this->sendHelp($chatId);
                break;
            case '/profile':
            case '/melumat':
                $this->sendProfile($chatId, $customer);
                break;
            case '/orders':
            case '/sifarisler':
                $this->sendOrders($chatId, $customer);
                break;
            case '/beyanname':
            case '/declaration':
                $orderId = $parts[1] ?? null;
                if ($orderId) {
                    $this->sendDeclaration($chatId, (int) $orderId, $customer);
                } else {
                    $this->telegram->sendMessage($chatId, "❌ İstifadə: /beyanname {sifariş_id}\nSifariş ID-ni /orders ilə görə bilərsiniz.");
                }
                break;
            default:
                $this->sendHelp($chatId);
        }
    }

    protected function sendWelcome(int $chatId, TransitCustomer $customer): void
    {
        $msg = "👋 <b>Xoş gəlmisiniz, {$customer->name}!</b>\n\n";
        $msg .= "Transit müştəri botundan istifadə edə bilərsiniz.\n\n";
        $this->telegram->sendMessage($chatId, $msg);
        $this->sendHelp($chatId);
    }

    protected function sendHelp(int $chatId): void
    {
        $msg = "📋 <b>Transit əmrləri</b>\n\n";
        $msg .= "/profile — Mənim məlumatlarım\n";
        $msg .= "/orders — Sifarişlərim\n";
        $msg .= "/beyanname {id} — Sifarişin bəyannaməsini götür (sifariş ID-ni /orders-dan götürün)";
        $this->telegram->sendMessage($chatId, $msg);
    }

    protected function sendProfile(int $chatId, TransitCustomer $customer): void
    {
        $msg = "👤 <b>Hesab məlumatları</b>\n\n";
        $msg .= "📌 Ad: {$customer->name}\n";
        $msg .= "📧 Email: " . ($customer->email ?? '-') . "\n";
        $msg .= "📱 Tel: " . ($customer->phone ?? '-') . "\n";
        $msg .= "🆔 VÖEN: " . ($customer->voen ?? '-') . "\n";
        $msg .= "💰 Balans: " . number_format((float) $customer->balance, 2) . " AZN";
        $this->telegram->sendMessage($chatId, $msg);
    }

    protected function sendOrders(int $chatId, TransitCustomer $customer): void
    {
        $orders = Order::where('transit_customer_id', $customer->id)->latest()->limit(20)->get();

        if ($orders->isEmpty()) {
            $this->telegram->sendMessage($chatId, "📭 Sifarişiniz yoxdur.");
            return;
        }

        $msg = "📦 <b>Sifarişlərim</b>\n\n";
        foreach ($orders as $order) {
            $date = $order->created_at ? $order->created_at->format('d.m.Y') : '-';
            $hasDecl = $order->declaration ? '✅' : '—';
            $msg .= "🔹 <b>#{$order->id}</b> | {$order->code} | {$date} | Bəyannamə: {$hasDecl}\n";
            $msg .= "   Bəyannamə üçün: /beyanname {$order->id}\n\n";
        }
        $this->telegram->sendMessage($chatId, $msg);
    }

    protected function sendDeclaration(int $chatId, int $orderId, TransitCustomer $customer): void
    {
        $order = Order::where('id', $orderId)->where('transit_customer_id', $customer->id)->first();

        if (!$order) {
            $this->telegram->sendMessage($chatId, "❌ Sifariş tapılmadı və ya sizə aid deyil.");
            return;
        }

        if (empty($order->declaration)) {
            $this->telegram->sendMessage($chatId, "❌ Bu sifariş üçün bəyannamə hələ yoxdur.");
            return;
        }

        $path = Storage::path($order->declaration);
        if (!is_file($path)) {
            $this->telegram->sendMessage($chatId, "❌ Bəyannamə faylı tapılmadı.");
            return;
        }

        $caption = "📄 Bəyannamə — Sifariş #{$order->id} ({$order->code})";
        $result = $this->telegram->sendDocument($chatId, $path, $caption);

        if (!$result) {
            $this->telegram->sendMessage($chatId, "❌ Fayl göndərilə bilmədi.");
        }
    }

    protected function sendLinkInstruction(int $chatId): void
    {
        $msg = "👋 <b>Transit müştəri botu</b>\n\n";
        $msg .= "1️⃣ Saytda daxil olun (Transit / Profil)\n";
        $msg .= "2️⃣ Profil → <b>Telegram</b> bölməsində «Kod yarat» düyməsinə basın\n";
        $msg .= "3️⃣ Çıxan 6 rəqəmli kodu burada yazın: <code>/link 123456</code>\n\n";
        $msg .= "Sonra /profile, /orders və /beyanname əmrlərindən istifadə edə bilərsiniz.";
        $this->telegram->sendMessage($chatId, $msg);
    }
}
