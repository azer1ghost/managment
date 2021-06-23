<?php

use App\Http\Controllers\{CompanyController, PlatformController, HomeController, SignatureController};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::redirect('/signature','/signature/welcome');
Route::get('/signature/welcome', [SignatureController::class, 'welcome'])->name('signature.welcome');
Route::get('/signature/register', [SignatureController::class, 'register'])->name('signature.register');



Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('companies', CompanyController::class);
