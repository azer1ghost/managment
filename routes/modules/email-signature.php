<?php

use App\Http\Controllers\Modules\SignatureController;

Route::get('/signature/select-company', [SignatureController::class, 'selectCompany'])->name('signature-select-company');
Route::get('/signature/{company}', [SignatureController::class, 'signature'])->name('signature');