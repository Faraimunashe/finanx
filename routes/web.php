<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Treasurer\AccountController;
use App\Http\Controllers\Treasurer\CurrenciesController;
use App\Http\Controllers\Treasurer\ReportsController;
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
    // Dashboard
    Route::get('/dashboard', function () {
        return redirect()->route('organizations.index');
    })->name('dashboard.index');

    // Organizations
    Route::get('/organizations', [OrganisationController::class, 'index'])->name('organizations.index');
    Route::post('/organizations', [OrganisationController::class, 'store'])->name('organizations.store');
    Route::get('/organizations/{id}', [OrganisationController::class, 'show'])->name('organizations.show');
    Route::put('/organizations/{id}', [OrganisationController::class, 'update'])->name('organizations.update');
    Route::delete('/organizations/{id}', [OrganisationController::class, 'destroy'])->name('organizations.destroy');

    // Accounts
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('/accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::get('/accounts/{id}', [AccountController::class, 'show'])->name('accounts.show');
    Route::put('/accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    // Currencies
    Route::get('/currencies', [CurrenciesController::class, 'index'])->name('currencies.index');
    Route::post('/currencies', [CurrenciesController::class, 'store'])->name('currencies.store');
    Route::delete('/currencies/{currencyId}', [CurrenciesController::class, 'destroy'])->name('currencies.destroy');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/balance-sheet', [ReportsController::class, 'balanceSheet'])->name('reports.balance-sheet');
    Route::get('/reports/income-statement', [ReportsController::class, 'incomeStatement'])->name('reports.income-statement');
    Route::get('/reports/cash-flow', [ReportsController::class, 'cashFlow'])->name('reports.cash-flow');
    Route::get('/reports/trial-balance', [ReportsController::class, 'trialBalance'])->name('reports.trial-balance');
    Route::get('/reports/general-ledger', [ReportsController::class, 'generalLedger'])->name('reports.general-ledger');
    Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');

    // Users
    Route::get('/users', [App\Http\Controllers\Treasurer\UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [App\Http\Controllers\Treasurer\UserController::class, 'create'])->name('users.create');
    Route::post('/users', [App\Http\Controllers\Treasurer\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Treasurer\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Treasurer\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Treasurer\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/confirm-add-existing', [App\Http\Controllers\Treasurer\UserController::class, 'confirmAddExisting'])->name('users.confirm-add-existing');
});

// User routes
Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/select-organization/{organizationId}', [App\Http\Controllers\User\DashboardController::class, 'selectOrganization'])->name('select-organization');

    // Accounts (view only)
    Route::get('/accounts', [App\Http\Controllers\User\AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/{id}', [App\Http\Controllers\User\AccountController::class, 'show'])->name('accounts.show');

    // Transactions (view, create, update)
    Route::get('/transactions', [App\Http\Controllers\User\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/create', [App\Http\Controllers\User\TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions', [App\Http\Controllers\User\TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transactions/{id}/edit', [App\Http\Controllers\User\TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{id}', [App\Http\Controllers\User\TransactionController::class, 'update'])->name('transactions.update');
});
