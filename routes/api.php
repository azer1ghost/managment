<?php

use App\Http\Controllers\Api\BirbankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Telegram Bot Webhooks (public, no auth/CSRF required)
Route::post('/telegram/webhook', [\App\Http\Controllers\TelegramBotController::class, 'webhook'])->name('telegram.webhook');
Route::post('/telegram/transit-webhook', [\App\Http\Controllers\TransitTelegramBotController::class, 'webhook'])->name('telegram.transit.webhook');

// Birbank B2B API routes
Route::prefix('birbank')->group(function () {
    Route::post('{company}/login', [BirbankController::class, 'login'])->name('api.birbank.login');
    Route::get('{company}/accounts', [BirbankController::class, 'getAccounts'])->name('api.birbank.accounts');
    Route::get('{company}/transactions', [BirbankController::class, 'getTransactions'])->name('api.birbank.transactions');
});
