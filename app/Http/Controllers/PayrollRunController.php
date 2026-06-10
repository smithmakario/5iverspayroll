<?php

namespace App\Http\Controllers;

use App\Enums\EmploymentStatus;
use App\Enums\PayrollRunStatus;
use App\Http\Requests\StorePayrollRunRequest;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Services\PayrollAuditLogger;
use App\Services\PayrollCalculationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PayrollRunController extends Controller
{
    public function __construct(private PayrollCalculationService $calculator) {}

    public function index(): View
    {
        $runs = PayrollRun::withCount('payslips')
            ->orderByDesc('period_start')
            ->paginate(20);

        return view('payroll-runs.index', compact('runs'));
    }

    public function create(): View
    {
        $activeEmployeeCount = Employee::where('employment_status', EmploymentStatus::Active)->count();

        return view('payroll-runs.create', compact('activeEmployeeCount'));
    }

    public function store(StorePayrollRunRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('process_now');

        $run = PayrollRun::create(array_merge($data, [
            'status' => PayrollRunStatus::Draft,
        ]));

        PayrollAuditLogger::log('payroll_run.created', $run, $data);

        if ($request->boolean('process_now')) {
            $activeCount = Employee::where('employment_status', EmploymentStatus::Active)->count();

            if ($activeCount === 0) {
                return redirect()->route('payroll-runs.show', $run)
                    ->with('error', 'Payroll run saved, but there are no active employees to process.');
            }

            $this->approveRun($run);
            $this->executeProcessing($run);

            return redirect()->route('payroll-runs.show', $run)
                ->with('success', 'Payroll processed. '.$run->payslips()->count().' payslips generated.');
        }

        return redirect()->route('payroll-runs.show', $run)
            ->with('success', 'Payroll run created. Click Approve for Processing, then Process Payroll to generate payslips.');
    }

    public function show(PayrollRun $payrollRun): View
    {
        $payrollRun->load(['payslips.employee', 'processor', 'approver']);

        return view('payroll-runs.show', compact('payrollRun'));
    }

    public function approve(PayrollRun $payrollRun): RedirectResponse
    {
        if ($payrollRun->status !== PayrollRunStatus::Draft) {
            return back()->with('error', 'Only draft payroll runs can be approved.');
        }

        $this->approveRun($payrollRun);

        return back()->with('success', 'Payroll run approved. You may now process it.');
    }

    public function process(PayrollRun $payrollRun): RedirectResponse
    {
        if ($payrollRun->status !== PayrollRunStatus::Draft) {
            return back()->with('error', 'Only draft payroll runs can be processed.');
        }

        if (! $payrollRun->is_approved) {
            return back()->with('error', 'Approve the payroll run before processing.');
        }

        $activeCount = Employee::where('employment_status', EmploymentStatus::Active)->count();

        if ($activeCount === 0) {
            return back()->with('error', 'No active employees found. Add or activate employees before processing.');
        }

        try {
            $this->executeProcessing($payrollRun);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Payroll processing failed: '.$e->getMessage());
        }

        return redirect()->route('payroll-runs.show', $payrollRun)
            ->with('success', 'Payroll processed. '.$payrollRun->payslips()->count().' payslips generated.');
    }

    private function approveRun(PayrollRun $payrollRun): void
    {
        $payrollRun->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        PayrollAuditLogger::log('payroll_run.approved', $payrollRun);
    }

    private function executeProcessing(PayrollRun $payrollRun): void
    {
        DB::transaction(function () use ($payrollRun) {
            $payrollRun->update([
                'status' => PayrollRunStatus::Processing,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            $employees = Employee::with(['payGrade', 'deductions.deductionType', 'earnings.earningType'])
                ->where('employment_status', EmploymentStatus::Active)
                ->get();

            foreach ($employees as $employee) {
                $this->calculator->generatePayslip($employee, $payrollRun);
            }

            $payrollRun->update(['status' => PayrollRunStatus::Completed]);
            PayrollAuditLogger::log('payroll_run.processed', $payrollRun, [
                'payslip_count' => $payrollRun->payslips()->count(),
            ]);
        });
    }

    public function lock(PayrollRun $payrollRun): RedirectResponse
    {
        if ($payrollRun->status !== PayrollRunStatus::Completed) {
            return back()->with('error', 'Only completed runs can be locked.');
        }

        $payrollRun->update(['status' => PayrollRunStatus::Locked]);
        PayrollAuditLogger::log('payroll_run.locked', $payrollRun);

        return back()->with('success', 'Payroll run locked.');
    }
}
