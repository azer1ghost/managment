<?php

use App\Http\Controllers\Modules\InquiryController;

Route::post('/inquiry/version/{inquiry}', [InquiryController::class, 'versionRestore'])->name('inquiry.versionRestore');
Route::post('/inquiry/restore/{inquiry}', [InquiryController::class, 'restore'])->name('inquiry.restore');
Route::delete('/inquiry/force-delete/{inquiry}', [InquiryController::class, 'forceDelete'])->name('inquiry.forceDelete');
Route::resource('/inquiry', InquiryController::class);