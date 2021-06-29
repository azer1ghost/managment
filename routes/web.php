<?php

use App\Http\Controllers\Platform\{
    Main\AccountController,
    Main\PlatformController,
    Modules\CompanyController,
    Modules\SignatureController};
use Illuminate\Support\Facades\{Auth, Route};

Route::namespace('App\Http\Controllers\Platform')->group(function () {
    Auth::routes();
});

Route::redirect('/','/welcome')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->name('dashboard');


Route::get('/account', [AccountController::class, 'account'])->name('account');
Route::post('/account', [AccountController::class, 'save']);

Route::get('/signature/select-company', [SignatureController::class, 'selectCompany'])->name('signature-select-company');
Route::get('/signature/{company}', [SignatureController::class, 'signature'])->name('signature');


Route::resource('companies', CompanyController::class);
