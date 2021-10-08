<?php

use App\Http\Controllers\{Auth\LoginController,
    Auth\PhoneVerifycationController,
    Main\AccountController,
    Main\PlatformController,
    Modules\CompanyController,
    Modules\DepartmentController,
    Modules\WidgetController,
    Modules\DatabaseNotificationController,
    Modules\OptionController,
    Modules\ClientController,
    Modules\ParameterController,
    Modules\RoleController,
    Modules\UserController,
    Modules\PositionController,
    Modules\TaskController};
use App\Http\Middleware\Localization;
use Illuminate\Support\Facades\{Auth, Route};

Route::redirect('/','/welcome')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->middleware('verified_phone')->name('dashboard');

Route::get('/account', [AccountController::class, 'account'])->name('account');
Route::post('/account/{user}', [AccountController::class, 'save'])->name('account.save');

Route::group([
    'prefix' => 'module',
    'middleware' => ['verified_phone']
], function () {

    Route::get('/cabinet', [PlatformController::class, 'cabinet'])->name('cabinet');
    Route::get('/customer-services', [PlatformController::class, 'customerServices'])->name('customer-services');

    include 'modules/inquiry.php';
    include 'modules/email-signature.php';

    Route::resource('/companies', CompanyController::class);
    Route::resource('/widgets', WidgetController::class);
    Route::resource('/parameters', ParameterController::class);
    Route::resource('/options', OptionController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/departments', DepartmentController::class);
    Route::resource('/positions', PositionController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/notifications', DatabaseNotificationController::class);
    Route::resource('/clients', ClientController::class);
});

Auth::routes(['login' => false]);

PhoneVerifycationController::routes();

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

Route::post('/phone-update', [LoginController::class, 'phoneUpdate'])->name('phone.update');

Route::get('ip-resolver.bat', [PlatformController::class, 'downloadBat'])->name('host.bat');

Route::get('locale/{locale}', [Localization::class, 'locale'])->whereAlpha('locale')->where('locale','[A-Za-z0-9]{2}')->name('locale');

Route::get('/test', [PlatformController::class, 'test']);