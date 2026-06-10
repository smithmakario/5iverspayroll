<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeEarningRequest;
use App\Models\Employee;
use App\Models\EmployeeEarning;
use App\Models\EarningType;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeEarningController extends Controller
{
    public function index(Employee $employee): View
    {
        $employee->load(['earnings.earningType']);
        $earningTypes = EarningType::where('is_active', true)->orderBy('name')->get();

        return view('employees.earnings.index', compact('employee', 'earningTypes'));
    }

    public function store(StoreEmployeeEarningRequest $request, Employee $employee): RedirectResponse
    {
        $earning = $employee->earnings()->create(array_merge($request->validated(), [
            'is_active' => true,
        ]));

        PayrollAuditLogger::log('employee_earning.created', $earning, [
            'employee_id' => $employee->id,
        ]);

        return back()->with('success', 'Bonus/commission assigned to employee.');
    }

    public function destroy(Employee $employee, EmployeeEarning $earning): RedirectResponse
    {
        abort_unless($earning->employee_id === $employee->id, 404);

        $earning->delete();
        PayrollAuditLogger::log('employee_earning.removed', $employee, ['earning_id' => $earning->id]);

        return back()->with('success', 'Bonus/commission removed.');
    }
}
