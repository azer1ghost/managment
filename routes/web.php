<?php

use App\Http\Controllers\Platform\{Main\AccountController,
    Main\PlatformController,
    Modules\CallCenterController,
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


Route::prefix('module')->group(function () {

    Route::get('/customer-services', [PlatformController::class, 'customerServices'])->name('customer-services');

    Route::get('/call-center/table', [CallCenterController::class, 'table'])->name('call-center.table');
    Route::resource('/call-center', CallCenterController::class);

    Route::get('mobex-call-center/table', [CallCenterController::class, 'table'])->name('mobex-call-center.table');
    Route::resource('mobex-call-center', CallCenterController::class);

    Route::get('/signature/select-company', [SignatureController::class, 'selectCompany'])->name('signature-select-company');
    Route::get('/signature/{company}', [SignatureController::class, 'signature'])->name('signature');

    Route::resource('/companies', CompanyController::class);
});
