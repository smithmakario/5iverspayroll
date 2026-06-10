<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\PayrollRun;
use App\Models\Payslip;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->hasRole(UserRole::Employee->value)) {
            return redirect()->route('ess.dashboard');
        }

        $stats = [
            'employees' => Employee::where('employment_status', 'active')->count(),
            'pending_leave' => LeaveRequest::where('status', 'pending')->count(),
            'pending_attendance' => Attendance::where('is_approved', false)->count(),
            'draft_payrolls' => PayrollRun::where('status', 'draft')->count(),
            'recent_runs' => PayrollRun::orderByDesc('period_start')->limit(5)->get(),
        ];

        return view('dashboard', compact('stats'));
    }

    public function admin(): View
    {
        $settings = \App\Models\PayPeriodSetting::current();
        $auditLogs = \App\Models\PayrollAuditLog::with('user')->latest()->limit(20)->get();

        return view('admin.dashboard', compact('settings', 'auditLogs'));
    }
}
