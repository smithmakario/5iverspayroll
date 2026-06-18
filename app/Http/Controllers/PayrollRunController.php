<?php

namespace App\Http\Controllers;

use App\Enums\PayrollRunStatus;
use App\Http\Requests\ProcessPayrollRunRequest;
use App\Http\Requests\StorePayrollRunRequest;
use App\Models\Employee;
use App\Models\PayrollRun;
use App\Models\Payslip;
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
        $run = new PayrollRun([
            'period_start' => now()->startOfMonth(),
            'period_end' => now()->endOfMonth(),
        ]);
        $eligibleEmployees = Employee::eligibleForPayrollRun($run);

        return view('payroll-runs.create', [
            'eligibleEmployeeCount' => $eligibleEmployees->count(),
        ]);
    }

    public function store(StorePayrollRunRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('process_now');

        $run = PayrollRun::create(array_merge($data, [
            'status' => PayrollRunStatus::Draft,
        ]));

        PayrollAuditLogger::log('payroll_run.created', $run, $data);

        if ($request->boolean('process_now')) {
            $eligible = Employee::eligibleForPayrollRun($run);

            if ($eligible->isEmpty()) {
                return redirect()->route('payroll-runs.show', $run)
                    ->with('error', 'Payroll run saved, but there are no eligible employees to process.');
            }

            $this->approveRun($run);

            return redirect()->route('payroll-runs.preview', $run)
                ->with('success', 'Payroll run created and approved. Review the preview, then process when ready.');
        }

        return redirect()->route('payroll-runs.show', $run)
            ->with('success', 'Payroll run created. Approve it, preview calculations, then process payroll.');
    }

    public function show(PayrollRun $payrollRun): View
    {
        $payrollRun->load(['payslips.employee', 'processor', 'approver']);

        $eligibleEmployees = Employee::eligibleForPayrollRun($payrollRun);
        $processedEmployeeIds = $payrollRun->payslips->pluck('employee_id');
        $pendingEmployees = $eligibleEmployees->whereNotIn('id', $processedEmployeeIds);

        return view('payroll-runs.show', compact('payrollRun', 'pendingEmployees'));
    }

    public function preview(PayrollRun $payrollRun): View|RedirectResponse
    {
        if ($payrollRun->status === PayrollRunStatus::Locked) {
            return redirect()->route('payroll-runs.show', $payrollRun)
                ->with('error', 'Locked payroll runs cannot be previewed.');
        }

        if ($payrollRun->status === PayrollRunStatus::Draft && ! $payrollRun->is_approved) {
            return redirect()->route('payroll-runs.show', $payrollRun)
                ->with('error', 'Approve the payroll run before previewing.');
        }

        $previews = $this->calculator->previewRun($payrollRun);
        $processedEmployeeIds = $payrollRun->payslips->pluck('employee_id');

        return view('payroll-runs.preview', compact('payrollRun', 'previews', 'processedEmployeeIds'));
    }

    public function previewEmployee(PayrollRun $payrollRun, Employee $employee): View|RedirectResponse
    {
        if ($payrollRun->status === PayrollRunStatus::Locked) {
            return redirect()->route('payroll-runs.show', $payrollRun)
                ->with('error', 'Locked payroll runs cannot be previewed.');
        }

        if (! $employee->isEligibleForPayrollPeriod($payrollRun->period_start, $payrollRun->period_end)) {
            return redirect()->route('payroll-runs.preview', $payrollRun)
                ->with('error', 'This employee is not eligible for the selected pay period.');
        }

        $preview = $this->calculator->calculatePayslip($employee, $payrollRun);
        $existingPayslip = $payrollRun->payslips()->where('employee_id', $employee->id)->first();

        return view('payroll-runs.preview-employee', compact('payrollRun', 'preview', 'employee', 'existingPayslip'));
    }

    public function approve(PayrollRun $payrollRun): RedirectResponse
    {
        if ($payrollRun->status !== PayrollRunStatus::Draft) {
            return back()->with('error', 'Only draft payroll runs can be approved.');
        }

        $this->approveRun($payrollRun);

        return redirect()->route('payroll-runs.preview', $payrollRun)
            ->with('success', 'Payroll run approved. Review the preview before processing.');
    }

    public function process(ProcessPayrollRunRequest $request, PayrollRun $payrollRun): RedirectResponse
    {
        if ($payrollRun->status === PayrollRunStatus::Locked) {
            return back()->with('error', 'Locked payroll runs cannot be processed.');
        }

        if ($payrollRun->status === PayrollRunStatus::Draft && ! $payrollRun->is_approved) {
            return back()->with('error', 'Approve the payroll run before processing.');
        }

        $employeeIds = $request->input('employee_ids');

        if ($request->boolean('require_selection') && empty($employeeIds)) {
            return back()->with('error', 'Select at least one employee to process.');
        }

        $eligible = Employee::eligibleForPayrollRun($payrollRun);

        if ($eligible->isEmpty()) {
            return back()->with('error', 'No eligible employees found for this pay period.');
        }

        if ($employeeIds !== null && $employeeIds !== []) {
            $employees = $eligible->whereIn('id', $employeeIds);

            if ($employees->isEmpty()) {
                return back()->with('error', 'None of the selected employees are eligible for this pay period.');
            }
        } else {
            $employees = $eligible;
        }

        try {
            $count = $this->executeProcessing($payrollRun, $employees->pluck('id')->all());
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'Payroll processing failed: '.$e->getMessage());
        }

        return redirect()->route('payroll-runs.show', $payrollRun)
            ->with('success', "Payroll processed. {$count} payslip(s) generated.");
    }

    public function reprocess(PayrollRun $payrollRun): RedirectResponse
    {
        if ($payrollRun->status !== PayrollRunStatus::Completed) {
            return back()->with('error', 'Only completed runs can be reprocessed.');
        }

        DB::transaction(function () use ($payrollRun) {
            Payslip::where('payroll_run_id', $payrollRun->id)->delete();

            $payrollRun->update([
                'status' => PayrollRunStatus::Draft,
                'processed_by' => null,
                'processed_at' => null,
            ]);

            PayrollAuditLogger::log('payroll_run.reprocessed', $payrollRun);
        });

        return redirect()->route('payroll-runs.preview', $payrollRun)
            ->with('success', 'Payslips cleared. Review the preview and process again when ready.');
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

    private function executeProcessing(PayrollRun $payrollRun, ?array $employeeIds = null): int
    {
        return DB::transaction(function () use ($payrollRun, $employeeIds) {
            $payrollRun->update([
                'status' => PayrollRunStatus::Processing,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            $employees = Employee::eligibleForPayrollRun($payrollRun);

            if ($employeeIds !== null) {
                $employees = $employees->whereIn('id', $employeeIds);
            }

            foreach ($employees as $employee) {
                $this->calculator->generatePayslip($employee, $payrollRun);
            }

            $payrollRun->update(['status' => PayrollRunStatus::Completed]);
            PayrollAuditLogger::log('payroll_run.processed', $payrollRun, [
                'payslip_count' => $payrollRun->payslips()->count(),
                'employee_ids' => $employeeIds,
            ]);

            return $employees->count();
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
