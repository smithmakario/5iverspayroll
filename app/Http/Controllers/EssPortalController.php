<?php

namespace App\Http\Controllers;

use App\Enums\LeaveRequestStatus;
use App\Enums\GuarantorStatus;
use App\Http\Requests\StoreEmployeeGuarantorsRequest;
use App\Http\Requests\StoreLeaveRequestRequest;
use App\Http\Requests\UpdateEmployeeBankRequest;
use App\Models\LeaveRequest;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EssPortalController extends Controller
{
    public function dashboard(): View|RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')
                ->with('error', 'No employee profile is linked to your account. Contact HR.');
        }

        $latestPayslip = $employee->payslips()->with('payrollRun')->latest('id')->first();
        $ytdNetPay = $employee->payslips()
            ->whereHas('payrollRun', fn ($q) => $q->whereYear('period_end', now()->year))
            ->sum('net_pay');

        $pendingLeave = $employee->leaveRequests()->where('status', 'pending')->count();

        return view('ess.dashboard', compact('employee', 'latestPayslip', 'ytdNetPay', 'pendingLeave'));
    }

    public function payslips(): View|RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        $payslips = $employee->payslips()->with('payrollRun')->orderByDesc('id')->paginate(15);

        return view('ess.payslips', compact('employee', 'payslips'));
    }

    public function profile(): View|RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        $employee->load(['department', 'payGrade', 'guarantors']);

        return view('ess.profile', compact('employee'));
    }

    public function storeGuarantors(StoreEmployeeGuarantorsRequest $request): RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        foreach ([1, 2] as $slot) {
            $existing = $employee->guarantors()->where('slot', $slot)->first();

            if ($existing?->isConfirmed()) {
                continue;
            }

            $employee->guarantors()->updateOrCreate(
                ['slot' => $slot],
                array_merge($request->input("guarantors.{$slot}"), [
                    'status' => GuarantorStatus::Pending,
                    'confirmed_by' => null,
                    'confirmed_at' => null,
                ])
            );
        }

        PayrollAuditLogger::log('employee_guarantors.saved', $employee);

        return back()->with('success', 'Guarantor details saved.');
    }

    public function updateBank(UpdateEmployeeBankRequest $request): RedirectResponse
    {
        $employee = auth()->user()->employee;

        $employee->update($request->validated());

        return back()->with('success', 'Bank details updated successfully.');
    }

    public function confirmProfile(): RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        $employee->load('guarantors');

        if (! $employee->hasCompleteGuarantors()) {
            return back()->with('error', 'Please add two guarantors with full name, email, phone, and address before confirming your profile.');
        }

        $employee->update(['profile_confirmed_at' => now()]);

        PayrollAuditLogger::log('employee.profile_confirmed', $employee);

        return redirect()->route('ess.dashboard')
            ->with('success', 'Profile confirmed. Welcome to your employee portal!');
    }

    public function leave(): View|RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        $requests = $employee->leaveRequests()->orderByDesc('start_date')->paginate(15);

        return view('ess.leave', compact('employee', 'requests'));
    }

    public function storeLeave(StoreLeaveRequestRequest $request): RedirectResponse
    {
        $employee = auth()->user()->employee;

        LeaveRequest::create(array_merge($request->validated(), [
            'employee_id' => $employee->id,
            'status' => LeaveRequestStatus::Pending,
        ]));

        return back()->with('success', 'Leave request submitted for manager approval.');
    }
}
