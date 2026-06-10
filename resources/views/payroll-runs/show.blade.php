<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Payroll Run: {{ $payrollRun->name }}</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />

            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2 text-body-md">
                    <p>Period: <strong>{{ $payrollRun->period_start->format('d M Y') }}</strong> – <strong>{{ $payrollRun->period_end->format('d M Y') }}</strong></p>
                    <p>Payment: <strong>{{ $payrollRun->payment_date?->format('d M Y') ?? '—' }}</strong></p>
                    <p>Status: <x-status-badge :status="$payrollRun->status" />
                        @if ($payrollRun->is_approved) <span class="chip-success ml-2">Approved</span> @endif
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    @if ($payrollRun->status->value === 'draft' && ! $payrollRun->is_approved)
                        <form method="POST" action="{{ route('payroll-runs.approve', $payrollRun) }}">
                            @csrf
                            <button class="btn-secondary">Approve for Processing</button>
                        </form>
                    @endif
                    @if ($payrollRun->status->value === 'draft' && $payrollRun->is_approved)
                        <form method="POST" action="{{ route('payroll-runs.process', $payrollRun) }}" onsubmit="return confirm('Process payroll for all active employees?')">
                            @csrf
                            <button class="btn-primary">Process Payroll</button>
                        </form>
                    @endif
                    @if ($payrollRun->status->value === 'completed')
                        <form method="POST" action="{{ route('payroll-runs.lock', $payrollRun) }}" onsubmit="return confirm('Lock this run permanently?')">
                            @csrf
                            <button class="btn-secondary">Lock Run</button>
                        </form>
                    @endif
                    <a href="{{ route('payroll-runs.index') }}" class="btn-secondary">Back</a>
                </div>
            </div>

            @if ($payrollRun->status->value === 'draft' && $payrollRun->payslips->isEmpty())
                <div class="rounded-lg border border-primary/20 bg-primary-container/30 p-4 text-body-sm text-on-surface">
                    @if (! $payrollRun->is_approved)
                        <strong>Next step:</strong> Click <strong>Approve for Processing</strong>, then <strong>Process Payroll</strong> to generate payslips.
                    @else
                        <strong>Ready to process:</strong> Click <strong>Process Payroll</strong> to generate payslips for all active employees.
                    @endif
                </div>
            @endif

            @if ($payrollRun->payslips->isNotEmpty())
                <div class="card overflow-hidden">
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th class="text-right">Gross</th>
                                <th class="text-right">Tax</th>
                                <th class="text-right">Deductions</th>
                                <th class="text-right">Net Pay</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payrollRun->payslips as $payslip)
                                <tr>
                                    <td class="font-medium">{{ $payslip->employee->fullName() }}</td>
                                    <td class="text-right">{{ number_format($payslip->gross_pay, 2) }}</td>
                                    <td class="text-right text-error">{{ number_format($payslip->total_tax, 2) }}</td>
                                    <td class="text-right text-error">{{ number_format($payslip->total_deductions, 2) }}</td>
                                    <td class="text-right font-semibold text-primary">{{ number_format($payslip->net_pay, 2) }}</td>
                                    <td class="text-right"><a href="{{ route('payslips.show', $payslip) }}" class="link-primary">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-surface-container-low font-semibold">
                            <tr>
                                <td>Totals</td>
                                <td class="text-right">{{ number_format($payrollRun->payslips->sum('gross_pay'), 2) }}</td>
                                <td class="text-right">{{ number_format($payrollRun->payslips->sum('total_tax'), 2) }}</td>
                                <td class="text-right">{{ number_format($payrollRun->payslips->sum('total_deductions'), 2) }}</td>
                                <td class="text-right">{{ number_format($payrollRun->payslips->sum('net_pay'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="card card-body text-center text-on-surface-variant py-12">
                    No payslips yet. Approve this run, then click <strong>Process Payroll</strong>.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
