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
    
    /*
    |--------------------------------------------------------------------------
    | Transit Bot (müştərilər üçün ayrı bot)
    |--------------------------------------------------------------------------
    */
    
    'transit_bot_token' => env('TELEGRAM_TRANSIT_BOT_TOKEN', ''),
    
    'transit_webhook_url' => env('TELEGRAM_TRANSIT_WEBHOOK_URL', ''),
    
    /*
    |--------------------------------------------------------------------------
    | Allowed User IDs (yalnız Works bot üçün)
    |--------------------------------------------------------------------------
    */
    
    'allowed_user_ids' => array_filter(
        array_map('trim', explode(',', env('TELEGRAM_ALLOWED_USER_IDS', '')))
    ),
];
