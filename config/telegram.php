<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Telegram Bot Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Telegram Bot integration.
    |
    */

    'bot_token' => env('TELEGRAM_BOT_TOKEN', ''),
    
    'webhook_url' => env('TELEGRAM_WEBHOOK_URL', ''),
];
