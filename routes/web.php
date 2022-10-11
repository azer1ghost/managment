<?php

use App\Models\Document;
use App\Http\Controllers\{Auth\LoginController,
    Auth\PhoneVerifycationController,
    Auth\RegisterController,
    BarcodeController,
    Main\AccountController,
    Main\PlatformController,
    Modules\AdvertisingController,
    Modules\AnnouncementController,
    Modules\AsanImzaController,
    Modules\CalendarController,
    Modules\CertificateController,
    Modules\ClientController,
    Modules\CompanyController,
    Modules\ConferenceController,
    Modules\CustomerEngagementController,
    Modules\DailyReportController,
    Modules\DatabaseNotificationController,
    Modules\DepartmentController,
    Modules\DocumentController,
    Modules\InquiryController,
    Modules\InternalNumberController,
    Modules\MeetingController,
    Modules\OptionController,
    Modules\OrganizationController,
    Modules\ParameterController,
    Modules\PositionController,
    Modules\PartnerController,
    Modules\ReferralBonusController,
    Modules\ReferralController,
    Modules\ReportController,
    Modules\ResultController,
    Modules\RoleController,
    Modules\SalesActivityController,
    Modules\SalesActivityTypeController,
    Modules\SalesClientController,
    Modules\SalesInquiryController,
    Modules\ServiceController,
    Modules\SignatureController,
    Modules\TaskController,
    Modules\TaskListController,
    Modules\UpdateController,
    Modules\UserController,
    Modules\WidgetController,
    Modules\ChatController,
    Modules\WorkController};
use App\Http\Middleware\Localization;
use App\Services\FirebaseApi;
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
Route::get('/security', [AccountController::class, 'security'])->middleware(['verified_phone', 'deactivated'])->name('account.security');

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

    Route::post('/inquiry/version/{inquiry}', [InquiryController::class, 'versionRestore'])->name('inquiry.versionRestore');
    Route::get('/inquiry/restore/{inquiry}', [InquiryController::class, 'restore'])->name('inquiry.restore');
    Route::get('/inquiry/access/{inquiry}', [InquiryController::class, 'editAccessToUser'])->name('inquiry.access');
    Route::post('/inquiry/access/{inquiry}', [InquiryController::class, 'updateAccessToUser']);
    Route::post('/inquiry/editable-mass-access', [InquiryController::class, 'editableMassAccessUpdate'])->name('inquiry.editable-mass-access-update');
    Route::get('/inquiry/logs/{inquiry}', [InquiryController::class, 'logs'])->name('inquiry.logs');
    Route::get('/inquiry/task/{inquiry}', [InquiryController::class, 'createTask'])->name('inquiry.task');
    Route::delete('/inquiry/force-delete/{inquiry}', [InquiryController::class, 'forceDelete'])->name('inquiry.forceDelete');
    Route::put('/inquiry/status-update', [InquiryController::class, 'updateStatus'])->name('inquiry.update-status');
    Route::get('/inquiries-sales', [SalesInquiryController::class, 'index'])->name('inquiry.sales');
    Route::get('/potential-customers', [SalesInquiryController::class, 'potentialCustomers'])->name('inquiry.potential-customers');
    Route::resource('/inquiry', InquiryController::class);
    Route::get('/inquiries/export', [InquiryController::class, 'export'])->name('inquiry.export');


    Route::get('/signature/select-company', [SignatureController::class, 'selectCompany'])->name('signature-select-company');
    Route::get('/signature/{company}', [SignatureController::class, 'signature'])->name('signature');

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
    Route::resource('/barcode', BarcodeController::class);


    Route::post('/tasks/redirect/{task}', [TaskController::class, 'redirect'])->name('task.redirect');

    Route::post('/clients/sum/assign-sales', [ClientController::class, 'sumAssignSales'])->name('clients.sum.assign-sales');
    Route::post('/clients/sum/assign-companies', [ClientController::class, 'sumAssignCompanies'])->name('clients.sum.assign-companies');
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
    Route::any('/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::resource('/clients', ClientController::class);
    Route::any('/sales-client/search', [SalesClientController::class, 'search'])->name('sales-client.search');
    Route::resource('/sales-client', SalesClientController::class);
    Route::get('/sales-clients/export', [SalesClientController::class, 'export'])->name('sales-clients.export');


    Route::resource('/referrals', ReferralController::class)->except('create');
    Route::resource('/updates', UpdateController::class);
    Route::resource('/services', ServiceController::class);

    Route::put('/works/sum/verify', [WorkController::class, 'sumVerify'])->name('works.sum.verify');
    Route::put('/works/{work}/verify', [WorkController::class, 'verify'])->name('works.verify');
    Route::get('/works/report', [WorkController::class, 'report'])->name('works.report');
    Route::get('/works/export', [WorkController::class, 'export'])->name('works.export');
    Route::resource('/works', WorkController::class);
    Route::post('/test', [WorkController::class, 'editable'])->name('editable');
    Route::post('/code', [WorkController::class, 'code'])->name('work.code');

    Route::resource('/meetings', MeetingController::class);
    Route::resource('/internal-numbers', InternalNumberController::class);
    Route::resource('/organizations', OrganizationController::class);
    Route::resource('/conferences', ConferenceController::class);
    Route::resource('/advertising', AdvertisingController::class);
    Route::resource('/partners', PartnerController::class);
    Route::get('/reports/{report}/sub-reports', [ReportController::class, 'showSubReports'])->name('reports.subs.show');
    Route::get('/reports/{report}/sub-report/create', [ReportController::class, 'createSubReport'])->name('reports.sub.create');
    Route::post('/reports/{report}/sub-report/generate', [ReportController::class, 'generateSubReport'])->name('reports.sub.generate');
    Route::get('/reports/sub-report/{report}', [DailyReportController::class, 'showSubReport'])->name('reports.sub.show');
    Route::get('/reports/sub-report/{report}/edit', [DailyReportController::class, 'editSubReport'])->name('reports.sub.edit');
    Route::put('/reports/sub-report/{report}', [DailyReportController::class, 'updateSubReport'])->name('reports.sub.update');
    Route::post('/reports/generate', [ReportController::class, 'generateReports'])->name('reports.generate');
    Route::resource('/reports', ReportController::class)->only('index', 'destroy');
    Route::resource('/customer-engagement', CustomerEngagementController::class);
    Route::get('/customer-engagement/getAmount/{customerEngagement}',[ CustomerEngagementController::class,'getAmount'])->name('getAmount');
    Route::resource('/documents', DocumentController::class)->except('store');
    Route::post('/documents/{modelId}', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/message/{id}', [ChatController::class, 'message'])->name('message');
    Route::post('/message', [ChatController::class, 'sendMessage']);


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

Route::any('/close', fn() => "<html><script>window.close()</script></html>" )->name('close');

Route::any('/test', [PlatformController::class, 'test'])->middleware('deactivated');
Route::any('/asan-imza/user/search', [AsanImzaController::class, 'searchUser'])->name('asanImza.user.search');
Route::any('/document-temporary-url/{document}', [PlatformController::class, 'documentTemporaryUrl'])->name('document.temporaryUrl');
Route::any('/document-temporary-viewer-url/{document}', [DocumentController::class, 'temporaryViewerUrl'])->name('document.temporaryViewerUrl');
Route::get('/documents/{document}/viewer', [DocumentController::class, 'viewer'])->name('documents.viewer');
Route::get('/document/{document}', function (\Illuminate\Http\Request $request, Document $document) {
    abort_if(!$request->hasValidSignature(), 404);

    $url = (new FirebaseApi)->getDoc()->object("Documents/{$document->module()}/{$document->getAttribute('file')}")->signedUrl(
        new DateTime('1 min')
    );

    return response(file_get_contents($url))
        ->withHeaders([
            'Content-Type' => $document->getAttribute('type')
        ]);

})->name('document');