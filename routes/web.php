<?php

use App\Http\Middleware\Localization;
use App\Services\FirebaseApi;
use App\Http\Controllers\{Auth\LoginController,
    Auth\PhoneVerifycationController,
    Auth\RegisterController,
    Main\AccountController,
    Main\PlatformController,
    Modules\AnnouncementController,
    Modules\AsanImzaController,
    Modules\CalendarController,
    Modules\CertificateController,
    Modules\CompanyController,
    Modules\ConferenceController,
    Modules\CustomerEngagementController,
    Modules\AdvertisingController,
    Modules\DailyReportController,
    Modules\DepartmentController,
    Modules\DocumentController,
    Modules\MeetingController,
    Modules\OrganizationController,
    Modules\ReferralBonusController,
    Modules\ReportController,
    Modules\ResultController,
    Modules\SalesActivityController,
    Modules\SalesActivityTypeController,
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

// deactivated user
Route::get('/deactivated', [PlatformController::class, 'deactivated'])->name('deactivated');

Route::get('/close-notify/{announcement}', [PlatformController::class, 'closeNotify'])->name('closeNotify');

Route::redirect('/','/welcome')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->middleware(['verified_phone', 'deactivated'])->name('dashboard');

Route::get('/account', [AccountController::class, 'account'])->middleware(['verified_phone', 'deactivated'])->name('account');
Route::post('/account/{user}', [AccountController::class, 'save'])->middleware(['verified_phone', 'deactivated'])->name('account.save');

Route::group([
    'prefix' => 'module',
    'middleware' => ['verified_phone', 'deactivated']
], function () {
    Route::get('/bonuses', [ReferralBonusController::class, 'index'])->name('bonuses');
    Route::post('/bonuses', [ReferralBonusController::class, 'refresh']);
    Route::post('/bonuses/referral', [ReferralBonusController::class, 'refreshReferral'])->name('bonuses.referral');
    Route::post('/bonuses/generate-referral-link', [ReferralBonusController::class, 'generate'])->name('bonuses.generate-referral-link');

    Route::get('/cabinet', [PlatformController::class, 'cabinet'])->name('cabinet');
    Route::get('/customer-services', [PlatformController::class, 'customerServices'])->name('customer-services');

    include 'modules/inquiry.php';
    include 'modules/email-signature.php';

    Route::resource('/sales-activities-types', SalesActivityTypeController::class);
    Route::resource('/sales-activities', SalesActivityController::class);
    Route::resource('/announcements', AnnouncementController::class);
    Route::resource('/certificates', CertificateController::class);
    Route::resource('/companies', CompanyController::class);
    Route::resource('/asan-imza', AsanImzaController::class);
    Route::resource('/widgets', WidgetController::class);
    Route::resource('/calendars', CalendarController::class)->except('show', 'create', 'edit');
    Route::resource('/parameters', ParameterController::class);
    Route::resource('/options', OptionController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/departments', DepartmentController::class);
    Route::resource('/positions', PositionController::class);
    Route::resource('/tasks', TaskController::class);
    Route::resource('/task-lists', TaskListController::class)->only('store', 'update', 'destroy');
    Route::resource('/notifications', DatabaseNotificationController::class);
    Route::post('/clients/sum/assign-sales', [ClientController::class, 'sumAssignSales'])->name('clients.sum.assign-sales');
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
    Route::resource('/clients', ClientController::class);
    Route::resource('/referrals', ReferralController::class)->except('create');
    Route::resource('/updates', UpdateController::class);
    Route::resource('/services', ServiceController::class);
    Route::put('/works/sum/verify', [WorkController::class, 'sumVerify'])->name('works.sum.verify');
    Route::put('/works/{work}/verify', [WorkController::class, 'verify'])->name('works.verify');
    Route::get('/works/report', [WorkController::class, 'report'])->name('works.report');
    Route::get('/works/export', [WorkController::class, 'export'])->name('works.export');
    Route::resource('/works', WorkController::class);
    Route::resource('/meetings', MeetingController::class);
    Route::resource('/organizations', OrganizationController::class);
    Route::resource('/conferences', ConferenceController::class);
    Route::resource('/advertising', AdvertisingController::class);
    Route::get('/reports/{report}/sub-reports', [ReportController::class, 'showSubReports'])->name('reports.subs.show');
    Route::get('/reports/{report}/sub-report/create', [ReportController::class, 'createSubReport'])->name('reports.sub.create');
    Route::post('/reports/{report}/sub-report/generate', [ReportController::class, 'generateSubReport'])->name('reports.sub.generate');
    Route::get('/reports/sub-report/{report}', [DailyReportController::class, 'showSubReport'])->name('reports.sub.show');
    Route::get('/reports/sub-report/{report}/edit', [DailyReportController::class, 'editSubReport'])->name('reports.sub.edit');
    Route::put('/reports/sub-report/{report}', [DailyReportController::class, 'updateSubReport'])->name('reports.sub.update');
    Route::post('/reports/generate', [ReportController::class, 'generateReports'])->name('reports.generate');
    Route::resource('/reports', ReportController::class)->only('index', 'destroy');
    Route::resource('/customer-engagement', CustomerEngagementController::class);
    Route::resource('/documents', DocumentController::class)->except('store');
    Route::post('/documents/{modelId}', [DocumentController::class, 'store'])->name('documents.store');
    // resultable routes
    Route::post('/results/{modelId}', [ResultController::class, 'store'])->name('results.store');
    Route::put('/results/{result}',   [ResultController::class, 'update'])->name('results.update');
    // disable enable users
    Route::post('/users/{user}/enable', [UserController::class, 'enable'])->name('users.enable');
    Route::post('/users/{user}/disable', [UserController::class, 'disable'])->name('users.disable');
    Route::get('/users/{user}/login-as-user', [UserController::class, 'loginAsUser'])->name('users.loginAs');
});

Auth::routes();
// routes for registering partners
Route::get('/partners/register', [RegisterController::class, 'showPartnersRegistrationForm'])->name('register.partners');
Route::post('/partners/register', [RegisterController::class, 'register']);

PhoneVerifycationController::routes();

Route::post('/phone-update', [LoginController::class, 'phoneUpdate'])->middleware('deactivated')->name('phone.update');

// Route for register validation
Route::post('/validate-register', [RegisterController::class, 'validator'])->name('validate-register');

Localization::route();

Route::get('/close', function (){
    return view('close');
})->name('close');

Route::any('/test', [PlatformController::class, 'test'])->middleware('deactivated');
Route::any('/clients/search', [ClientController::class, 'search'])->name('clients.search');
Route::any('/asan-imza/user/search', [AsanImzaController::class, 'searchUser'])->name('asanImza.user.search');
Route::any('/document-temporary-url/{document}', [PlatformController::class, 'documentTemporaryUrl'])->name('document.temporaryUrl');
Route::any('/document-temporary-viewer-url/{document}', [DocumentController::class, 'temporaryViewerUrl'])->name('document.temporaryViewerUrl');
Route::get('/documents/{document}/viewer', [DocumentController::class, 'viewer'])->name('documents.viewer');
Route::get('/document/{document}', function (\Illuminate\Http\Request $request, \App\Models\Document $document) {
    abort_if(!$request->hasValidSignature(), 404);

    $url = (new FirebaseApi)->getDoc()->object("Documents/{$document->module()}/{$document->getAttribute('file')}")->signedUrl(
        new \DateTime('1 min')
    );

    return response(file_get_contents($url))
        ->withHeaders([
            'Content-Type' => $document->getAttribute('type')
        ]);

})->name('document');