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
    | Allowed User IDs
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of Telegram user IDs that are allowed to use the bot.
    | Only these users will be able to interact with the bot.
    |
    */
    
    'allowed_user_ids' => array_filter(
        array_map('trim', explode(',', env('TELEGRAM_ALLOWED_USER_IDS', '')))
    ),
];
