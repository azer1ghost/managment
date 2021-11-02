<?php

use App\Http\Middleware\Localization;
use App\Http\Controllers\{Auth\LoginController,
    Auth\PhoneVerifycationController,
    Auth\RegisterController,
    Main\AccountController,
    Main\PlatformController,
    Modules\CompanyController,
    Modules\ConferenceController,
    Modules\CustomerCompanyController,
    Modules\DepartmentController,
    Modules\DocumentController,
    Modules\MeetingController,
    Modules\ReferralBonusController,
    Modules\UpdateController,
    Modules\WidgetController,
    Modules\DatabaseNotificationController,
    Modules\OptionController,
    Modules\ClientController,
    Modules\ParameterController,
    Modules\RoleController,
    Modules\UserController,
    Modules\PositionController,
    Modules\TaskController,
    Modules\ReferralController,
    Modules\ServiceController,
    Modules\WorkController,
    TaskListController};
use Illuminate\Support\Facades\{Auth, Route};


Route::get('firebase-messaging-sw.js', [PlatformController::class, 'firebase']);
Route::post('/store-fcm-token', [PlatformController::class, 'storeFcmToken'])->name('store.fcm-token');
Route::post('/set-location', [PlatformController::class, 'setLocation'])->name('set-location');

Route::redirect('/','/welcome')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->middleware('verified_phone')->name('dashboard');

Route::get('/account', [AccountController::class, 'account'])->middleware('verified_phone')->name('account');
Route::post('/account/{user}', [AccountController::class, 'save'])->middleware('verified_phone')->name('account.save');

Route::group([
    'prefix' => 'module',
    'middleware' => ['verified_phone']
], function () {
    Route::get('/bonuses', [ReferralBonusController::class, 'index'])->name('bonuses');
    Route::post('/bonuses', [ReferralBonusController::class, 'refresh']);
    Route::post('/bonuses/generate-referral-link', [ReferralBonusController::class, 'generate'])->name('bonuses.generate-referral-link');

    Route::get('/cabinet', [PlatformController::class, 'cabinet'])->name('cabinet');
    Route::get('/customer-services', [PlatformController::class, 'customerServices'])->name('customer-services');

    include 'modules/inquiry.php';
    include 'modules/email-signature.php';

    Route::resource('/companies', CompanyController::class);
    Route::resource('/customer-companies', CustomerCompanyController::class);
    Route::resource('/widgets', WidgetController::class);
    Route::resource('/parameters', ParameterController::class);
    Route::resource('/options', OptionController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/departments', DepartmentController::class);
    Route::resource('/positions', PositionController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/task-lists', TaskListController::class);
    Route::resource('/notifications', DatabaseNotificationController::class);
    Route::resource('/clients', ClientController::class);
    Route::resource('/referrals', ReferralController::class);
    Route::resource('/updates', UpdateController::class);
    Route::resource('/services', ServiceController::class);
    Route::resource('/works', WorkController::class);
    Route::resource('/meetings', MeetingController::class);
    Route::resource('/conferences', ConferenceController::class);
    Route::resource('/documents', DocumentController::class)->except('store');
    Route::get('/documents/{document}/viewer', [DocumentController::class, 'viewer'])->name('documents.viewer');
    Route::post('/documents/{modelId}', [DocumentController::class, 'store'])->name('documents.store');
});

Auth::routes();
// routes for registering partners
Route::get('/partners/register', [RegisterController::class, 'showPartnersRegistrationForm'])->name('register.partners');
Route::post('/partners/register', [RegisterController::class, 'register']);

PhoneVerifycationController::routes();

Route::post('/phone-update', [LoginController::class, 'phoneUpdate'])->name('phone.update');

// Route for register validation
Route::post('/validate-register', [RegisterController::class, 'validator'])->name('validate-register');

Localization::route();

Route::any('/test', [PlatformController::class, 'test']);