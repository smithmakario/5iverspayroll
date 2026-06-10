<?php

namespace App\Http\Controllers;

use App\Enums\GuarantorStatus;
use App\Models\Employee;
use App\Models\EmployeeGuarantor;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeGuarantorController extends Controller
{
    public function showConfirm(Employee $employee, EmployeeGuarantor $guarantor): View|RedirectResponse
    {
        if ($guarantor->isConfirmed()) {
            return redirect()
                ->route('employees.show', $employee)
                ->with('error', 'This guarantor has already been confirmed.');
        }

        return view('employees.guarantors.confirm', compact('employee', 'guarantor'));
    }

    public function confirm(Employee $employee, EmployeeGuarantor $guarantor): RedirectResponse
    {
        if ($guarantor->isConfirmed()) {
            return redirect()
                ->route('employees.show', $employee)
                ->with('error', 'This guarantor has already been confirmed.');
        }

        $guarantor->update([
            'status' => GuarantorStatus::Confirmed,
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ]);

        PayrollAuditLogger::log('employee_guarantor.confirmed', $guarantor, [
            'employee_id' => $employee->id,
            'slot' => $guarantor->slot,
        ]);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', "Guarantor {$guarantor->slot} ({$guarantor->full_name}) confirmed.");
    }
}
