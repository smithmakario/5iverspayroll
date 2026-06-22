<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeDeductionRequest;
use App\Models\DeductionType;
use App\Models\Employee;
use App\Models\EmployeeDeduction;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeDeductionController extends Controller
{
    public function index(Employee $employee): View
    {
        $employee->load(['deductions.deductionType']);
        $deductionTypes = DeductionType::where('is_active', true)->orderBy('name')->get();

        return view('employees.deductions.index', compact('employee', 'deductionTypes'));
    }

    public function store(StoreEmployeeDeductionRequest $request, Employee $employee): RedirectResponse
    {
        $deduction = $employee->deductions()->create(array_merge($request->validated(), [
            'is_active' => true,
        ]));

        PayrollAuditLogger::log('employee_deduction.created', $deduction, [
            'employee_id' => $employee->id,
        ]);

        return back()->with('success', 'Deduction assigned to employee.');
    }

    public function destroy(Employee $employee, EmployeeDeduction $employeeDeduction): RedirectResponse
    {
        abort_unless($employeeDeduction->employee_id === $employee->id, 404);

        $employeeDeduction->delete();
        PayrollAuditLogger::log('employee_deduction.removed', $employee, ['deduction_id' => $employeeDeduction->id]);

        return back()->with('success', 'Deduction removed.');
    }
}
