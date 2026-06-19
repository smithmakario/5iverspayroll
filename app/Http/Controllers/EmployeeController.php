<?php

namespace App\Http\Controllers;

use App\Enums\EmploymentStatus;
use App\Enums\EmploymentType;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use App\Models\PayGrade;
use App\Services\EmployeeNumberGenerator;
use App\Services\EmployeeProvisioningService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeProvisioningService $provisioning,
        private EmployeeNumberGenerator $employeeNumbers,
    ) {}

    public function index(Request $request): View
    {
        $allowedSorts = [
            'employee_number',
            'name',
            'department',
            'location',
            'employment_type',
            'employment_status',
            'portal',
            'hire_date',
        ];

        $sort = in_array($request->string('sort')->toString(), $allowedSorts, true)
            ? $request->string('sort')->toString()
            : 'name';

        $direction = $request->string('direction')->lower()->toString() === 'desc' ? 'desc' : 'asc';

        $query = Employee::with(['department', 'location', 'payGrade', 'user']);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('employment_status')) {
            $query->where('employment_status', $request->employment_status);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        match ($sort) {
            'employee_number' => $query->orderBy('employee_number', $direction),
            'name' => $query->orderBy('last_name', $direction)->orderBy('first_name', $direction),
            'department' => $query->orderBy(
                Department::select('name')->whereColumn('departments.id', 'employees.department_id'),
                $direction
            ),
            'location' => $query->orderBy(
                Location::select('name')->whereColumn('locations.id', 'employees.location_id'),
                $direction
            ),
            'employment_type' => $query->orderBy('employment_type', $direction),
            'employment_status' => $query->orderBy('employment_status', $direction),
            'portal' => $query->orderBy('profile_confirmed_at', $direction),
            'hire_date' => $query->orderBy('hire_date', $direction),
        };

        $employees = $query->paginate(20)->withQueryString();

        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $locations = Location::where('is_active', true)->orderBy('name')->get();
        $employmentTypes = EmploymentType::cases();
        $employmentStatuses = EmploymentStatus::cases();

        return view('employees.index', compact(
            'employees',
            'departments',
            'locations',
            'employmentTypes',
            'employmentStatuses',
            'sort',
            'direction',
        ));
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $locations   = Location::where('is_active', true)->orderBy('name')->get();
        $payGrades   = PayGrade::where('is_active', true)->orderBy('name')->get();

        return view('employees.create', compact('departments', 'locations', 'payGrades'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $employee = DB::transaction(function () use ($request) {
            return Employee::create(array_merge($request->validated(), [
                'employee_number' => $this->employeeNumbers->next(),
            ]));
        });

        $message = 'Employee created successfully. Employee #'.$employee->employee_number.' assigned.';

        try {
            $this->provisioning->provision($employee);
            $message .= ' Onboarding email sent.';
        } catch (Throwable $e) {
            report($e);

            return redirect()->route('employees.show', $employee)
                ->with('error', 'Employee saved but onboarding email failed: '.$e->getMessage());
        }

        return redirect()->route('employees.show', $employee)->with('success', $message);
    }

    public function show(Employee $employee): View
    {
        $employee->load(['department', 'location', 'payGrade', 'deductions.deductionType', 'earnings.earningType', 'guarantors.confirmer', 'user']);

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $locations   = Location::where('is_active', true)->orderBy('name')->get();
        $payGrades   = PayGrade::where('is_active', true)->orderBy('name')->get();

        return view('employees.edit', compact('employee', 'departments', 'locations', 'payGrades'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        if ($employee->user) {
            $this->provisioning->syncUserFromEmployee($employee);
        } else {
            try {
                $this->provisioning->provision($employee);
            } catch (Throwable $e) {
                report($e);
            }
        }

        return redirect()->route('employees.show', $employee)
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee removed successfully.');
    }

    public function resendOnboarding(Employee $employee): RedirectResponse
    {
        try {
            if (! $employee->user_id) {
                $this->provisioning->provision($employee);
            } else {
                $this->provisioning->sendOnboardingEmail($employee->user, $employee);
            }

            return back()->with('success', 'Onboarding email sent to '.$employee->email.'.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Could not send onboarding email: '.$e->getMessage());
        }
    }
}
