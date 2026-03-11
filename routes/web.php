<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// ═══════════════════════════════════════════════════════════
//  WEB ROUTES — routes/web.php
//  Guard: auth (web / users table)
//  Employee portal lives here
// ═══════════════════════════════════════════════════════════

Route::group([
    'prefix'     => LaravelLocalization::setLocale(),
    'middleware' => ['localize', 'localeSessionRedirect', 'localeViewPath'],
], function () {

    // ── Root redirect ──────────────────────────────────────
    Route::get('/', function () {
        if (auth()->check()) {
            return redirect()->route('employee.dashboard');
        }
        return redirect()->route('employee.showlogin');
    });

    // ── Employee Auth (guest only) ─────────────────────────
    Route::prefix('employee')->name('employee.')->group(function () {

        Route::middleware('guest')->group(function () {
            Route::get('login',  [\App\Http\Controllers\Employee\LoginController::class, 'showLogin'])->name('showlogin');
            Route::post('login', [\App\Http\Controllers\Employee\LoginController::class, 'login'])->name('login');
        });

        // ── Protected (authenticated employee) ────────────
        Route::middleware('auth')->group(function () {

            // Logout
            Route::post('logout', [\App\Http\Controllers\Employee\LoginController::class, 'logout'])->name('logout');

            // ── Dashboard ──────────────────────────────────
            Route::get('dashboard', [\App\Http\Controllers\Employee\DashboardController::class, 'index'])->name('dashboard');

            // ── Profile ────────────────────────────────────
            Route::get('profile',           [\App\Http\Controllers\Employee\ProfileController::class, 'show'])->name('profile');
            Route::get('profile/edit',      [\App\Http\Controllers\Employee\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile',           [\App\Http\Controllers\Employee\ProfileController::class, 'update'])->name('profile.update');
            Route::put('profile/password',  [\App\Http\Controllers\Employee\ProfileController::class, 'updatePassword'])->name('profile.password');
            Route::post('profile/avatar',   [\App\Http\Controllers\Employee\ProfileController::class, 'updateAvatar'])->name('profile.avatar');

            // ── My Tasks ───────────────────────────────────
            Route::get('tasks',                         [\App\Http\Controllers\Employee\TaskController::class, 'index'])->name('tasks.index');
            Route::get('tasks/{task}',                  [\App\Http\Controllers\Employee\TaskController::class, 'show'])->name('tasks.show');
            Route::patch('tasks/{task}/complete',       [\App\Http\Controllers\Employee\TaskController::class, 'markComplete'])->name('tasks.complete');
            Route::patch('tasks/{task}/progress',       [\App\Http\Controllers\Employee\TaskController::class, 'updateProgress'])->name('tasks.progress');

            // ── Attendance ─────────────────────────────────
            Route::get('attendance',                    [\App\Http\Controllers\Employee\AttendanceController::class, 'index'])->name('attendance.index');
            Route::post('attendance/check-in',          [\App\Http\Controllers\Employee\AttendanceController::class, 'checkIn'])->name('attendance.checkin');
            Route::post('attendance/check-out',         [\App\Http\Controllers\Employee\AttendanceController::class, 'checkOut'])->name('attendance.checkout');

            // ── Schedule ───────────────────────────────────
            Route::get('schedule',                      [\App\Http\Controllers\Employee\ScheduleController::class, 'index'])->name('schedule.index');

            // ── Leave Requests ─────────────────────────────
            Route::get('leave',                         [\App\Http\Controllers\Employee\LeaveController::class, 'index'])->name('leave.index');
            Route::get('leave/create',                  [\App\Http\Controllers\Employee\LeaveController::class, 'create'])->name('leave.create');
            Route::post('leave',                        [\App\Http\Controllers\Employee\LeaveController::class, 'store'])->name('leave.store');
            Route::get('leave/{leave}',                 [\App\Http\Controllers\Employee\LeaveController::class, 'show'])->name('leave.show');
            Route::delete('leave/{leave}',              [\App\Http\Controllers\Employee\LeaveController::class, 'destroy'])->name('leave.destroy');

            // ── Salary / Payslips ──────────────────────────
            Route::get('salary',                        [\App\Http\Controllers\Employee\SalaryController::class, 'index'])->name('salary.index');
            Route::get('salary/{payroll}/slip',         [\App\Http\Controllers\Employee\SalaryController::class, 'slip'])->name('salary.slip');

            // ── Sales employees only ───────────────────────
            Route::middleware('employee.sales')->group(function () {
                Route::get('deals',                     [\App\Http\Controllers\Employee\DealController::class, 'index'])->name('deals.index');
                Route::get('deals/create',              [\App\Http\Controllers\Employee\DealController::class, 'create'])->name('deals.create');
                Route::post('deals',                    [\App\Http\Controllers\Employee\DealController::class, 'store'])->name('deals.store');
                Route::get('deals/{deal}',              [\App\Http\Controllers\Employee\DealController::class, 'show'])->name('deals.show');
                Route::get('deals/{deal}/edit',         [\App\Http\Controllers\Employee\DealController::class, 'edit'])->name('deals.edit');
                Route::put('deals/{deal}',              [\App\Http\Controllers\Employee\DealController::class, 'update'])->name('deals.update');
                Route::patch('deals/{deal}/status',     [\App\Http\Controllers\Employee\DealController::class, 'updateStatus'])->name('deals.status');

                Route::get('commissions',               [\App\Http\Controllers\Employee\CommissionController::class, 'index'])->name('commissions.index');
                Route::get('clients',                   [\App\Http\Controllers\Employee\ClientController::class, 'index'])->name('clients.index');
                Route::get('clients/{client}',          [\App\Http\Controllers\Employee\ClientController::class, 'show'])->name('clients.show');
            });

            // ── Notifications ──────────────────────────────
            Route::get('notifications',                 [\App\Http\Controllers\Employee\NotificationController::class, 'index'])->name('notifications.index');
            Route::patch('notifications/{id}/read',     [\App\Http\Controllers\Employee\NotificationController::class, 'markRead'])->name('notifications.read');
            Route::patch('notifications/read-all',      [\App\Http\Controllers\Employee\NotificationController::class, 'markAllRead'])->name('notifications.readAll');

        }); // end auth middleware

    }); // end employee prefix

}); // end localization group


// ── Bootstrap routes (load both route files) ──────────────
// In app/Providers/RouteServiceProvider.php, add:
//
//   Route::middleware('web')
//       ->namespace($this->namespace)
//       ->group(base_path('routes/admin.php'));
//
//   Route::middleware('web')
//       ->namespace($this->namespace)
//       ->group(base_path('routes/web.php'));