<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\ContractController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// ═══════════════════════════════════════════════════════════
//  ADMIN ROUTES — routes/admin.php
//  Guard: auth:admin
//  URL pattern: /{locale}/admin/...
//  e.g. /en/admin/login  |  /ar/admin/dashboard
// ═══════════════════════════════════════════════════════════

Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localeViewPath'],
], function () {

    Route::prefix('admin')->name('admin.')->group(function () {

        // ── ROOT redirect: /en/admin  →  /en/admin/login or dashboard ──
        Route::get('/', function () {
            if (auth()->guard('admin')->check()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('admin.showlogin');
        })->name('root');

        // ── Guest only (not logged in as admin) ────────────
        Route::middleware('guest:admin')->group(function () {
            Route::get(
                'login',
                [\App\Http\Controllers\Admin\LoginController::class, 'show_login_view']
            )->name('showlogin');

            Route::post(
                'login',
                [\App\Http\Controllers\Admin\LoginController::class, 'login']
            )->name('login');
        });

        // ── Authenticated admin ────────────────────────────
        Route::middleware('auth:admin')->group(function () {

            Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

            // Dashboard
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            // Employees
            Route::resource('employees', EmployeeController::class);

            // Users (app users)
            Route::resource('users', UserController::class);

            // ── Clients ────────────────────────────────────────────────────────
            Route::resource('clients', ClientController::class);

            // ── Services ───────────────────────────────────────────────────────
            Route::get('services',               [ServiceController::class, 'index'])->name('services.index');
            Route::post('services',              [ServiceController::class, 'store'])->name('services.store');
            Route::put('services/{service}',     [ServiceController::class, 'update'])->name('services.update');
            Route::delete('services/{service}',  [ServiceController::class, 'destroy'])->name('services.destroy');

            // ── Tasks ──────────────────────────────────────────────────────────
            Route::resource('tasks', TaskController::class);
            Route::post('tasks/{task}/complete', [TaskController::class, 'markComplete'])->name('tasks.complete');
            Route::post('tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');

            // ── العقود ───────────────────────────────────────────────────────
            Route::resource('contracts', ContractController::class);

            // تسجيل الدفع (ينشئ سند قبض تلقائياً)
            Route::post('contracts/payments/{payment}/pay', [ContractController::class, 'markPaymentPaid'])
                ->name('contracts.payments.pay');

            // طباعة سند القبض
            Route::get('receipts/{receipt}/print', [ContractController::class, 'printReceipt'])
                ->name('receipts.print');

            // ── Commissions ────────────────────────────────────────────────────
            Route::get('commissions',                                  [CommissionController::class, 'index'])->name('commissions.index');
            Route::post('commissions/{commission}/pay',                [CommissionController::class, 'paySingle'])->name('commissions.pay-single');
            Route::post('commissions/employees/{employee}/pay-all',    [CommissionController::class, 'pay'])->name('commissions.pay-all');
        });
    }); // end admin prefix

}); // end localization group