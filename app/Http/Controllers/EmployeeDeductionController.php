<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeDeductionRequest;
use App\Models\DeductionType;
use App\Models\Employee;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function destroy(Request $request, Employee $employee): RedirectResponse
    {
        $validated = $request->validate([
            'deduction_id' => ['required', 'integer'],
        ]);

        $deduction = $employee->deductions()->findOrFail($validated['deduction_id']);
        $deductionId = $deduction->id;

        $deduction->delete();
        PayrollAuditLogger::log('employee_deduction.removed', $employee, ['deduction_id' => $deductionId]);

        return back()->with('success', 'Deduction removed.');
    }
}
