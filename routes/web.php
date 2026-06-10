<?php

use App\Enums\UserRole;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeductionTypeController;
use App\Http\Controllers\EarningTypeController;
use App\Http\Controllers\EmployeeEarningController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeDeductionController;
use App\Http\Controllers\EmployeeGuarantorController;
use App\Http\Controllers\EssAttendanceController;
use App\Http\Controllers\EssPortalController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayGradeController;
use App\Http\Controllers\PayPeriodSettingController;
use App\Http\Controllers\PayrollRunController;
use App\Http\Controllers\PayslipController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Employee Self-Service Portal
    Route::middleware('role:'.UserRole::Employee->value)
        ->prefix('ess')
        ->name('ess.')
        ->group(function () {
            Route::get('/', [EssPortalController::class, 'dashboard'])->name('dashboard');
            Route::get('/payslips', [EssPortalController::class, 'payslips'])->name('payslips');
            Route::get('/profile', [EssPortalController::class, 'profile'])->name('profile');
            Route::patch('/profile/bank', [EssPortalController::class, 'updateBank'])->name('profile.bank');
            Route::post('/profile/confirm', [EssPortalController::class, 'confirmProfile'])->name('profile.confirm');
            Route::post('/profile/guarantors', [EssPortalController::class, 'storeGuarantors'])->name('profile.guarantors');
            Route::get('/leave', [EssPortalController::class, 'leave'])->name('leave');
            Route::post('/leave', [EssPortalController::class, 'storeLeave'])->name('leave.store');
            Route::get('/attendance', [EssAttendanceController::class, 'index'])->name('attendance');
            Route::post('/attendance/clock-in', [EssAttendanceController::class, 'clockIn'])->name('attendance.clock-in');
            Route::post('/attendance/clock-out', [EssAttendanceController::class, 'clockOut'])->name('attendance.clock-out');
        });

    // Employees — HR Manager + Admin
    Route::middleware('role:'.UserRole::Admin->value.'|'.UserRole::HrManager->value)
        ->group(function () {
            Route::resource('employees', EmployeeController::class);
            Route::post('employees/{employee}/resend-onboarding', [EmployeeController::class, 'resendOnboarding'])->name('employees.resend-onboarding');
            Route::resource('departments', DepartmentController::class);
            Route::get('employees/{employee}/deductions', [EmployeeDeductionController::class, 'index'])->name('employees.deductions.index');
            Route::post('employees/{employee}/deductions', [EmployeeDeductionController::class, 'store'])->name('employees.deductions.store');
            Route::delete('employees/{employee}/deductions/{deduction}', [EmployeeDeductionController::class, 'destroy'])->name('employees.deductions.destroy');
            Route::get('employees/{employee}/earnings', [EmployeeEarningController::class, 'index'])->name('employees.earnings.index');
            Route::post('employees/{employee}/earnings', [EmployeeEarningController::class, 'store'])->name('employees.earnings.store');
            Route::delete('employees/{employee}/earnings/{earning}', [EmployeeEarningController::class, 'destroy'])->name('employees.earnings.destroy');
        });

    // Pay grades & deduction types — Admin + Accountant
    Route::middleware('role:'.UserRole::Admin->value.'|'.UserRole::Accountant->value)
        ->group(function () {
            Route::resource('pay-grades', PayGradeController::class)->except('show');
            Route::resource('deduction-types', DeductionTypeController::class)->except(['show', 'destroy']);
        });

    // Attendance & Leave — HR Manager + Admin
    Route::middleware('role:'.UserRole::Admin->value.'|'.UserRole::HrManager->value)
        ->group(function () {
            Route::resource('attendance', AttendanceController::class)->except('show');
            Route::post('attendance/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
            Route::get('leave-requests', [LeaveRequestController::class, 'index'])->name('leave-requests.index');
            Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
            Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
        });

    // Payroll runs — Accountant + Admin
    Route::middleware('role:'.UserRole::Admin->value.'|'.UserRole::Accountant->value)
        ->group(function () {
            Route::resource('payroll-runs', PayrollRunController::class)->except(['edit', 'update', 'destroy']);
            Route::post('payroll-runs/{payrollRun}/approve', [PayrollRunController::class, 'approve'])->name('payroll-runs.approve');
            Route::post('payroll-runs/{payrollRun}/process', [PayrollRunController::class, 'process'])->name('payroll-runs.process');
            Route::post('payroll-runs/{payrollRun}/lock', [PayrollRunController::class, 'lock'])->name('payroll-runs.lock');
        });

    // Payslips — authorized via policy
    Route::get('payslips/{payslip}', [PayslipController::class, 'show'])->name('payslips.show');
    Route::get('payslips/{payslip}/pdf', [PayslipController::class, 'pdf'])->name('payslips.pdf');
});

// Admin panel
Route::middleware(['auth', 'verified', 'role:'.UserRole::Admin->value])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::get('/pay-period-settings', [PayPeriodSettingController::class, 'edit'])->name('pay-period-settings.edit');
        Route::put('/pay-period-settings', [PayPeriodSettingController::class, 'update'])->name('pay-period-settings.update');
    });

Route::middleware(['auth', 'verified', 'role:'.UserRole::Admin->value])
    ->post('employees/{employee}/guarantors/{guarantor}/confirm', [EmployeeGuarantorController::class, 'confirm'])
    ->name('employees.guarantors.confirm');

// Bonuses & commissions — Admin only
Route::middleware(['auth', 'verified', 'role:'.UserRole::Admin->value])
    ->group(function () {
        Route::resource('earning-types', EarningTypeController::class)->except('show');
    });

require __DIR__.'/auth.php';
