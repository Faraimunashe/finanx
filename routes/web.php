<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Treasurer\AccountController;
use App\Http\Controllers\Treasurer\CurrenciesController;
use App\Http\Controllers\Treasurer\DashboardController;
use App\Http\Controllers\Treasurer\OrganisationController;
use App\Http\Controllers\Treasurer\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return inertia('WelcomePage');
});

Route::get('/home', function () {
    return inertia('HomePage');
});


Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login');


Route::get('/authentication', [AuthenticationController::class, 'index'])->middleware('auth')->name('dashboard');

Route::group(['middleware' => ['auth', 'role:treasurer']], function () {
    Route::resource('organizations', OrganisationController::class);
    Route::resource('accounts', AccountController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::resource('transactions', TransactionController::class);
    Route::resource('currencies', CurrenciesController::class);
});
