<?php

use App\Models\Document;
use App\Http\Controllers\{Auth\LoginController,
    Auth\PhoneVerifycationController,
    Auth\RegisterController,
    BarcodeController,
    ClientAuthController,
    GlobalSearchController,
    Main\AccountController,
    Main\PlatformController,
    Modules\AccessRateController,
    Modules\AdvertisingController,
    Modules\AnnouncementController,
    Modules\AsanImzaController,
    Modules\BankController,
    Modules\CalendarController,
    Modules\CertificateController,
    Modules\ChangeController,
    Modules\ClientController,
    Modules\CommandController,
    Modules\CompanyController,
    Modules\ConferenceController,
    Modules\CreditorController,
    Modules\CustomerEngagementController,
    Modules\CustomerSatisfactionController,
    Modules\DailyReportController,
    Modules\DatabaseNotificationController,
    Modules\DepartmentController,
    Modules\DocumentController,
    Modules\EmailTemplateController,
    Modules\EmployeeRegistrationController,
    Modules\EmployeeSatisfactionController,
    Modules\FinanceClientController,
    Modules\FolderController,
    Modules\FundController,
    Modules\InquiryController,
    Modules\InternalDocumentController,
    Modules\InternalNumberController,
    Modules\InternalRelationController,
    Modules\IsoDocumentController,
    Modules\JobInstructionController,
    Modules\LogisticsClientController,
    Modules\LogisticsController,
    Modules\MeetingController,
    Modules\NecessaryController,
    Modules\NoteController,
    Modules\OptionController,
    Modules\OrderController,
    Modules\OrganizationController,
    Modules\ParameterController,
    Modules\PositionController,
    Modules\PartnerController,
    Modules\ProtocolController,
    Modules\QuestionnaireController,
    Modules\ReferralBonusController,
    Modules\ReferralController,
    Modules\RegistrationLogController,
    Modules\ReportController,
    Modules\ResultController,
    Modules\ReturnWorkController,
    Modules\RoleController,
    Modules\RoomController,
    Modules\SalaryController,
    Modules\SalaryReportController,
    Modules\SalesActivityController,
    Modules\SalesActivityTypeController,
    Modules\SalesInquiryController,
    Modules\SatisfactionController,
    Modules\SentDocumentController,
    Modules\ServiceController,
    Modules\SignatureController,
    Modules\StatementController,
    Modules\SummitController,
    Modules\SupplierController,
    Modules\SupportController,
    Modules\TaskController,
    Modules\TaskListController,
    Modules\TransactionController,
    Modules\TransitController,
    Modules\UpdateController,
    Modules\UserController,
    Modules\WidgetController,
    Modules\ChatController,
    Modules\WorkController};
use App\Http\Middleware\Localization;
use App\Services\FirebaseApi;
use Illuminate\Support\Facades\{Auth, Route};
Route::get('send-email', [App\Http\Controllers\EmailController::class, 'sendEmail']);
Route::get('send-info', [App\Http\Controllers\EmailController::class, 'sendInfo']);


Route::group([
    'prefix' => 'transit',
], function () {
    Route::get('/transit-login', [TransitController::class, 'login'])->name('transit-login');
    Route::get('/service', [TransitController::class, 'service'])->name('service');
    Route::get('/payment/{order}', [TransitController::class, 'payment'])->name('payment');
    Route::post('/payFromBalance/', [OrderController::class, 'payFromBalance'])->name('payFromBalance');
    Route::resource('/profile', TransitController::class);
    Route::resource('/order', OrderController::class)->only(['store']);
});

Route::get('firebase-messaging-sw.js', [PlatformController::class, 'firebase']);
Route::post('/store-fcm-token', [PlatformController::class, 'storeFcmToken'])->name('store.fcm-token');
Route::post('/set-location', [PlatformController::class, 'setLocation'])->name('set-location');

// deactivated user
Route::get('/deactivated', [PlatformController::class, 'deactivated'])->name('deactivated');

Route::get('/close-notify/{announcement}', [PlatformController::class, 'closeNotify'])->name('closeNotify');

Route::redirect('/','/dashboard')->name('home');
Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');
Route::get('/dashboard', [PlatformController::class, 'dashboard'])->middleware(['verified_phone', 'deactivated','is_transit_customer'])->name('dashboard');
Route::post('global-search', GlobalSearchController::class)->name('global-search');

Route::get('/account', [AccountController::class, 'account'])->middleware(['verified_phone', 'deactivated'])->name('account');
Route::post('/account/{user}', [AccountController::class, 'save'])->middleware(['verified_phone', 'deactivated'])->name('account.save');
Route::get('/security', [AccountController::class, 'security'])->middleware(['verified_phone', 'deactivated'])->name('account.security');

Route::group([
    'prefix' => 'module',
    'middleware' => ['verified_phone', 'deactivated','is_transit_customer' ]
], function () {
    Route::get('/bonuses', [ReferralBonusController::class, 'index'])->name('bonuses');
    Route::post('/bonuses', [ReferralBonusController::class, 'refresh']);
    Route::post('/bonuses/referral', [ReferralBonusController::class, 'refreshReferral'])->name('bonuses.referral');
    Route::post('/bonuses/generate-referral-link', [ReferralBonusController::class, 'generate'])->name('bonuses.generate-referral-link');
    Route::resource('/rooms', RoomController::class);


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
    Route::get('/salary-reports/export', [SalaryReportController::class, 'export'])->name('salary-report.export');

    Route::get('/signature/select-company', [SignatureController::class, 'selectCompany'])->name('signature-select-company');
    Route::get('/signature/{company}', [SignatureController::class, 'signature'])->name('signature');

    Route::resource('/sales-activities-types', SalesActivityTypeController::class);
    Route::resource('/sales-activities', SalesActivityController::class);
    Route::resource('/announcements', AnnouncementController::class);
    Route::resource('/suppliers', SupplierController::class);
    Route::resource('/salaries', SalaryController::class);
    Route::resource('/salary-reports', SalaryReportController::class);
    Route::resource('/certificates', CertificateController::class);
    Route::resource('/companies', CompanyController::class);
    Route::resource('/employee-registrations', EmployeeRegistrationController::class);
    Route::post('/employee-registrations', [EmployeeRegistrationController::class, 'store'])->name('employee-registrations.store');
    Route::get('employee-registrations/get-status', [EmployeeRegistrationController::class, 'getStatus'])->name('employee-registrations.getStatus');
    Route::get('/reports/company-payments-last-year', [WorkController::class, 'companyPaymentsLastYear'])
        ->name('reports.company_payments_last_year');
    Route::resource('/asan-imza', AsanImzaController::class);
    Route::resource('/widgets', WidgetController::class);
    Route::resource('/calendars', CalendarController::class)->except('show', 'create', 'edit');
    Route::resource('/parameters', ParameterController::class);
    Route::resource('/options', OptionController::class);
    Route::post('/sortable-user', [UserController::class, 'sortable'])->name('user.sortable');
    Route::resource('/users', UserController::class);
    Route::post('/update-monthly-values', [UserController::class, 'updateMonthlyValues'])->name('updateMonthlyValues');
    Route::resource('/roles', RoleController::class);
    Route::resource('/departments', DepartmentController::class);
    Route::resource('/positions', PositionController::class);
    Route::resource('/tasks', TaskController::class);
    Route::get('/task/export', [TaskController::class, 'export'])->name('tasks.export');
    Route::resource('/task-lists', TaskListController::class)->only('store', 'update', 'destroy');
    Route::resource('/notifications', DatabaseNotificationController::class);
    Route::resource('/barcode', BarcodeController::class);
    Route::post('/tasks/redirect/{task}', [TaskController::class, 'redirect'])->name('task.redirect');
    Route::post('/clients/sum/assign-sales', [ClientController::class, 'sumAssignSales'])->name('clients.sum.assign-sales');
    Route::post('/clients/sum/assign-companies', [ClientController::class, 'sumAssignCompanies'])->name('clients.sum.assign-companies');
    Route::post('/clients/sum/assign-coordinators', [ClientController::class, 'sumAssignCoordinators'])->name('clients.sum.assign-coordinators');
    Route::post('/clients/sum/assign-sales', [ClientController::class, 'sumAssignSales'])->name('clients.sum.assign-sales');
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
    Route::any('/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::any('/supplier/search', [SupplierController::class, 'search'])->name('suppliers.search');
    Route::resource('/clients', ClientController::class);
    Route::get('/protocol-download/{client}', [ClientController::class, 'download'])->name('protocol.download');

    Route::resource('/referrals', ReferralController::class)->except('create');
    Route::resource('/updates', UpdateController::class);
    Route::resource('/services', ServiceController::class);
    Route::resource('/satisfactions', SatisfactionController::class);
    Route::resource('/logistics', LogisticsController::class);
    Route::post('/logisticsEditable', [LogisticsController::class, 'editable'])->name('logistics-editable');
    Route::any('/logisticsClients/search', [LogisticsClientController::class, 'search'])->name('logisticsClients.search');

    Route::put('/works/sum/verify', [WorkController::class, 'sumVerify'])->name('works.sum.verify');
    Route::put('/works/{work}/verify', [WorkController::class, 'verify'])->name('works.verify');
    Route::get('/worksFinance/{work}/verify', [WorkController::class, 'verifyFinance'])->name('works.verifyFinance');
    Route::put('/works/{work}/paid', [WorkController::class, 'paid'])->name('works.paid');
    Route::put('/works/{work}/vatPaid', [WorkController::class, 'vatPaid'])->name('works.vatPaid');
    Route::put('/works/{work}/invoice', [WorkController::class, 'invoice'])->name('works.invoice');
    Route::put('/works/{work}/changeCreate', [WorkController::class, 'changeCreate'])->name('works.changeCreate');
    Route::post('/works/paymentMethod', [WorkController::class, 'paymentMethod'])->name('works.paymentMethod');
    Route::post('/works/invoice/fetch', [WorkController::class, 'fetchInvoiceWorks'])->name('works.invoice.fetch');
    Route::post('/works/payment/complete', [WorkController::class, 'completePayment'])->name('works.payment.complete');
    Route::post('/works/update-invoice-parameter', [WorkController::class, 'updateInvoiceParameter'])->name('works.update-invoice-parameter');
    Route::post('/works/update-invoice-date', [WorkController::class, 'updateInvoiceDate'])->name('works.update-invoice-date');
    Route::post('/works/apply-unified-payment', [WorkController::class, 'applyUnifiedPayment'])->name('works.apply-unified-payment');
    Route::get('/works/report', [WorkController::class, 'report'])->name('works.report');
    Route::get('/works/export', [WorkController::class, 'export'])->name('works.export');
    Route::resource('/works', WorkController::class);
    Route::get('/plannedWorks', [WorkController::class, 'plannedWorks'])->name('planned-works');
    Route::get('/pendingWorks', [WorkController::class, 'pendingWorks'])->name('pending-works');
    Route::get('/returnedWorks', [WorkController::class, 'returnedWorks'])->name('returned-works');
    Route::get('/financeWorks', [WorkController::class, 'financeWorks'])->name('finance-works');
    Route::get('/incompleteWorks', [WorkController::class, 'incompleteWorks'])->name('incomplete-works');
    Route::get('/total', [WorkController::class, 'showTotal'])->name('total');
    Route::get('/information', [WorkController::class, 'showInformation'])->name('information');
    Route::post('/test', [WorkController::class, 'editable'])->name('editable');
    Route::post('/code', [WorkController::class, 'code'])->name('work.code');
    Route::post('/declaration', [WorkController::class, 'declaration'])->name('work.declaration');
    Route::post('/works/update-status', [WorkController::class, 'updateStatus'])->name('works.update-status');
    Route::post('/works/updateColor', [WorkController::class, 'updateColor']);
    Route::post('/works/updateDoc', [WorkController::class, 'updateDoc']);
    Route::put('/plannedWorks/update-mark', [WorkController::class, 'updateMark'])->name('works.update-mark');

    Route::resource('/supports', SupportController::class);
    Route::resource('/commands', CommandController::class);
    Route::resource('/summits', SummitController::class);
    Route::resource('/funds', FundController::class);
    Route::post('/sortable-summits', [SummitController::class, 'sortable'])->name('summits.sortable');
    Route::post('/sortable-commands', [CommandController::class, 'sortable'])->name('commands.sortable');
    Route::resource('/meetings', MeetingController::class);
    Route::resource('/job-instructions', JobInstructionController::class);
    Route::resource('/internal-numbers', InternalNumberController::class);
    Route::resource('/changes', ChangeController::class);
    Route::put('/registration-logs/{registrationLog}/accepted', [RegistrationLogController::class, 'accepted'])->name('registration-logs.accepted');
    Route::resource('/registration-logs', RegistrationLogController::class);
    Route::get('/cooperative-numbers', [InternalNumberController::class, 'cooperative'])->name('cooperative-numbers');
    Route::resource('/internal-relations', InternalRelationController::class);
    Route::get('/internal-relation/export', [InternalRelationController::class, 'export'])->name('internal-relations.export');
    Route::resource('/logistic-clients', LogisticsClientController::class);
    Route::resource('/internal-documents', InternalDocumentController::class);
    Route::resource('/iso-documents', IsoDocumentController::class);
    Route::resource('/rules', \App\Http\Controllers\Modules\RuleController::class);
    Route::resource('/sent-documents', SentDocumentController::class);
    Route::resource('/protocols', ProtocolController::class);
    Route::resource('/folders', FolderController::class);
    Route::resource('/access-rates', AccessRateController::class);
    Route::resource('/questionnaires', QuestionnaireController::class);
    Route::get('/foreign', [InternalRelationController::class, 'foreign'])->name('foreign');
    Route::post('/sortable-relation', [InternalRelationController::class, 'sortable'])->name('internal-relation.sortable');
    Route::post('/sortable-internal', [InternalDocumentController::class, 'sortable'])->name('internal-document.sortable');
    Route::resource('/banks', BankController::class);
    Route::post('/banks/updateBankAmount', [BankController::class, 'updateBankAmount']);
    Route::post('/sortable', [BankController::class, 'sortable'])->name('bank.sortable');

    Route::get('/creditors/export', [CreditorController::class, 'export'])->name('creditors.export');
    Route::post('/creditors/payment', [CreditorController::class, 'payment'])->name('creditor.payment');
    Route::resource('/creditors', CreditorController::class);
    Route::post('/creditors/updateAmount', [CreditorController::class, 'updateAmount']);
    Route::post('/creditors/updateColor', [CreditorController::class, 'updateColor']);
    Route::post('/creditors/updateVat', [CreditorController::class, 'updateVat']);
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
    Route::get('/download', [ReportController::class, 'download'])->name('report.download');
    Route::resource('/customer-engagement', CustomerEngagementController::class);
    Route::get('/customer-engagement/getAmount/{customerEngagement}',[ CustomerEngagementController::class,'getAmount'])->name('getAmount');
    Route::post('/calculate-amounts',[ CustomerEngagementController::class,'calculateAmounts'])->name('calculate-amounts');
    Route::resource('/documents', DocumentController::class)->except('store');
    Route::post('/documents/{modelId}', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/message/{id}', [ChatController::class, 'message'])->name('message');
    Route::post('/message', [ChatController::class, 'sendMessage']);
    Route::get('/account-invoice',[FinanceClientController::class, 'index'] )->name('accountInvoice');
    Route::get('/getClients',[FinanceClientController::class, 'getClients'] )->name('getClients');
    Route::get('/financeClients',[FinanceClientController::class, 'clients'] )->name('financeClients');
    Route::post('/createFinanceClient',[FinanceClientController::class, 'createFinanceClient'] )->name('createFinanceClient');
    Route::post('/createFinanceInvoice',[FinanceClientController::class, 'createFinanceInvoice'] )->name('createFinanceInvoice');
    Route::get('/financeInvoice/{invoice}',[FinanceClientController::class, 'financeInvoice'] )->name('financeInvoice');
    Route::get('/deleteInvoice/{invoice}',[FinanceClientController::class, 'deleteInvoice'] )->name('deleteInvoice');
    Route::get('/editFinanceClient/{client}',[FinanceClientController::class, 'editFinanceClient'] )->name('editFinanceClient');
    Route::put('/updateFinanceClient/{client}',[FinanceClientController::class, 'updateFinanceClient'] )->name('updateFinanceClient');
    Route::get('/deleteFinanceClient/{client}',[FinanceClientController::class, 'deleteFinanceClient'] )->name('deleteFinanceClient');
    Route::get('/invoices',[FinanceClientController::class, 'invoices'] )->name('invoices');
    Route::post('/signInvoice',[FinanceClientController::class, 'signInvoice'] )->name('signInvoice');

    Route::view('/instruction','pages.instructions.index' )->name('instruction');
    Route::view('/selectCompany-salary','pages.salaries.selectCompany' )->name('selectCompany-salary');
    Route::view('/selectCompany-salaryReport','pages.salaries.selectCompany' )->name('selectCompany-salaryReport');
    Route::view('/presentations','pages.instructions.presentations' )->name('presentations');
    Route::view('/structure','pages.instructions.structure' )->name('structure');
    Route::resource('/necessaries', NecessaryController::class);
    Route::post('/return-works/AjaxStore', [ReturnWorkController::class, 'AjaxStore' ])->name('AjaxStore');
    Route::resource('/return-works', ReturnWorkController::class );
    Route::resource('/statements', StatementController::class);
    Route::resource('/transactions', TransactionController::class);
    Route::put('employee-satisfaction/addNote', [EmployeeSatisfactionController::class, 'addNote'])->name('addNote');
    Route::resource('/employee-satisfaction', EmployeeSatisfactionController::class);
    Route::get('/employee-satisfactions/export', [EmployeeSatisfactionController::class, 'export'])->name('employee-satisfactions.export');
    Route::view('/statement','pages.statements.statements' )->name('statement');
    Route::post('/markAsRead', [StatementController::class, 'markAsRead'])->name('mark-as-read');
    Route::get('/jobInstruction/{id}', [JobInstructionController::class, 'getInstruction'])->name('getInstruction');
    Route::post('/order-download',[ OrderController::class, 'download'])->name('orders.download');
    Route::get('/result-download/{order}',[ OrderController::class, 'resultDownload'])->name('order-result.download');
    Route::resource('/orders', OrderController::class)->except('store');
    Route::post('/chatRoom', [RoomController::class, 'chatRoom'])->name('room-chat');
    Route::post('/sendMessage', [RoomController::class, 'sendMessage'])->name('sendMessage-room');
    Route::get('/getMessage',  [RoomController::class, 'getMessage']);
    Route::get('/notes', [NoteController::class, 'index'])->name('note-index');
    Route::post('/sendNote', [NoteController::class, 'sendNote'])->name('sendNote-note');
    Route::get('/getNotes',  [NoteController::class, 'getNote']);
    Route::post('/updateNote',  [NoteController::class, 'updateNote']);
    Route::post('/deleteNote',  [NoteController::class, 'deleteNote']);
    Route::post('/sendToDo', [\App\Http\Controllers\Modules\ToDoController::class, 'sendToDo'])->name('sendToDo');
    Route::get('/getToDos',  [\App\Http\Controllers\Modules\ToDoController::class, 'getToDo']);
    Route::post('/updateToDo',  [\App\Http\Controllers\Modules\ToDoController::class, 'updateToDo']);
    Route::post('/deleteToDo',  [\App\Http\Controllers\Modules\ToDoController::class, 'deleteToDo']);
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
Route::post('/partners/transit/register', [RegisterController::class, 'transitRegister'])->name('transitRegister');

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

Route::resource('/customer-satisfactions', CustomerSatisfactionController::class);
Route::get('/cs', [CustomerSatisfactionController::class, 'createSatisfaction'])->name('create-satisfaction');
Route::view('/template','email' )->name('email');
Route::view('/template2','email2' )->name('email2');
Route::get('/excel-import',[ClientController::class, 'excelImport'] )->name('excel-import');
Route::resource('/email-templates', EmailTemplateController::class );


Route::prefix('clients')->middleware('guest:clients')->group(function () {
    Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('myguard.login');
    Route::post('/login', [ClientAuthController::class, 'login'])->name('myguard.login.submit');
    Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('myguard.register');
    Route::post('/register', [ClientAuthController::class, 'register'])->name('myguard.register.submit');
});
Route::prefix('clients')->middleware('clients')->group(function () {
    Route::get('/account', [ClientAuthController::class, 'account'])->name('client-account');
    Route::put('/update/{client}', [ClientAuthController::class, 'update'])->name('client-account.update');
    Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client-logout');
    Route::post('/doc/{modelId}', [DocumentController::class, 'store'])->name('doc.store');
//    Route::resource('/order', OrderController::class)->only(['store']);
});



