<?php

use App\Http\Controllers\Modules\InquiryController;
use App\Http\Controllers\Modules\SalesInquiryController;

Route::post('/inquiry/version/{inquiry}', [InquiryController::class, 'versionRestore'])->name('inquiry.versionRestore');
Route::get('/inquiry/restore/{inquiry}', [InquiryController::class, 'restore'])->name('inquiry.restore');
Route::get('/inquiry/access/{inquiry}', [InquiryController::class, 'editAccessToUser'])->name('inquiry.access');
Route::post('/inquiry/access/{inquiry}', [InquiryController::class, 'updateAccessToUser']);
Route::post('/inquiry/editable-mass-access', [InquiryController::class, 'editableMassAccessUpdate'])->name('inquiry.editable-mass-access-update');
Route::get('/inquiry/logs/{inquiry}', [InquiryController::class, 'logs'])->name('inquiry.logs');
Route::get('/inquiry/task/{inquiry}', [InquiryController::class, 'createTask'])->name('inquiry.task');
Route::delete('/inquiry/force-delete/{inquiry}', [InquiryController::class, 'forceDelete'])->name('inquiry.forceDelete');
Route::put('/inquiry/status-update', [InquiryController::class, 'updateStatus'])->name('inquiry.update-status');
Route::get('/inquiry/sales', SalesInquiryController::class)->name('inquiry.sales');
Route::resource('/inquiry', InquiryController::class);