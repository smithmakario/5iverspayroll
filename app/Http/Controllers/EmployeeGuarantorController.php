<?php

namespace App\Http\Controllers;

use App\Enums\GuarantorStatus;
use App\Models\Employee;
use App\Models\EmployeeGuarantor;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;

class EmployeeGuarantorController extends Controller
{
    public function confirm(Employee $employee, EmployeeGuarantor $guarantor): RedirectResponse
    {
        abort_unless($guarantor->employee_id === $employee->id, 404);

        if ($guarantor->isConfirmed()) {
            return back()->with('error', 'This guarantor has already been confirmed.');
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

        return back()->with('success', "Guarantor {$guarantor->slot} ({$guarantor->full_name}) confirmed.");
    }
}
