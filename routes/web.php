<?php

use App\Http\Controllers\{CompanyController, PlatformController, HomeController, SignatureController};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::redirect('/','/welcome')->name('home');

Route::get('/welcome', [PlatformController::class, 'welcome'])->name('welcome');

//Route::get('/register', [PlatformController::class, 'register'])->name('register');

Route::get('/register', [PlatformController::class, 'register'])->name('register');
Route::post('/register', [SignatureController::class, 'registerEmployer']);


Route::get('/select-company', [PlatformController::class, 'selectCompany'])->name('selectCompany');





Route::resource('companies', CompanyController::class);
