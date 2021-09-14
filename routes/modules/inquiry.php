<?php

use App\Http\Controllers\Modules\InquiryController;

Route::post('/inquiry/version/{inquiry}', [InquiryController::class, 'versionRestore'])->name('inquiry.versionRestore');
Route::get('/inquiry/restore/{inquiry}', [InquiryController::class, 'restore'])->name('inquiry.restore');
Route::get('/inquiry/access/{inquiry}', [InquiryController::class, 'editAccessToUser'])->name('inquiry.access');
Route::post('/inquiry/access/{inquiry}', [InquiryController::class, 'updateAccessToUser']);
Route::get('/inquiry/logs/{inquiry}', [InquiryController::class, 'logs'])->name('inquiry.logs');
Route::delete('/inquiry/force-delete/{inquiry}', [InquiryController::class, 'forceDelete'])->name('inquiry.forceDelete');
Route::resource('/inquiry', InquiryController::class);