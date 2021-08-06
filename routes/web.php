<?php

use App\Http\Controllers\{Main\AccountController,
    Main\PlatformController,
    Modules\CompanyController,
    Modules\InquiryController,
    Modules\SignatureController};
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\{Auth, Route};

Route::redirect('/','/welcome')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->name('dashboard');

Route::get('/account', [AccountController::class, 'account'])->name('account');
Route::post('/account', [AccountController::class, 'save']);

Route::prefix('module')->group(function () {

    Route::get('/customer-services', [PlatformController::class, 'customerServices'])->name('customer-services');

    Route::post('/inquiry/version/{inquiry}', [InquiryController::class, 'versionRestore'])->name('inquiry.versionRestore');
    Route::post('/inquiry/restore/{inquiry}', [InquiryController::class, 'restore'])->name('inquiry.restore');
    Route::delete('/inquiry/force-delete/{inquiry}', [InquiryController::class, 'forceDelete'])->name('inquiry.forceDelete');
    Route::resource('/inquiry', InquiryController::class);

    Route::get('/signature/select-company', [SignatureController::class, 'selectCompany'])->name('signature-select-company');
    Route::get('/signature/{company}', [SignatureController::class, 'signature'])->name('signature');

    Route::resource('/companies', CompanyController::class);
});

Auth::routes();

Route::get('ip-resolver.bat', [PlatformController::class, 'downloadBat'])->name('host.bat');

Route::get('locale/{locale}', [Localization::class, 'locale'])->whereAlpha('locale')->where('locale','[A-Za-z0-9]{2}')->name('locale');
