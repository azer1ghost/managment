<?php

use App\Http\Controllers\{Main\AccountController,
    Main\PlatformController,
    Modules\CompanyController,
    Modules\ParameterController,
    Modules\UserController};
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\{Auth, Route};

Route::redirect('/','/welcome')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->name('dashboard');

Route::get('/account', [AccountController::class, 'account'])->name('account');
Route::post('/account', [AccountController::class, 'save']);

Route::prefix('module')->group(function () {

    Route::get('/customer-services', [PlatformController::class, 'customerServices'])->name('customer-services');

    include 'modules/inquiry.php';
    include 'modules/email-signature.php';

    Route::resource('/companies', CompanyController::class);
    Route::resource('/parameters', ParameterController::class);
    Route::resource('/users', UserController::class);
});

Auth::routes();

Route::get('ip-resolver.bat', [PlatformController::class, 'downloadBat'])->name('host.bat');

Route::get('locale/{locale}', [Localization::class, 'locale'])->whereAlpha('locale')->where('locale','[A-Za-z0-9]{2}')->name('locale');
