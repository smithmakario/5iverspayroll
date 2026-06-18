<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Payroll Preview: {{ $employee->fullName() }}</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-3xl space-y-6">
            <x-flash-messages />

            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="text-body-sm text-on-surface-variant">
                    <p>{{ $payrollRun->name }} · {{ $payrollRun->period_start->format('d M') }} – {{ $payrollRun->period_end->format('d M Y') }}</p>
                    @if ($preview['is_prorated'])
                        <p class="mt-1 text-amber-700">{{ $preview['proration_note'] }}</p>
                    @endif
                </div>
                <div class="flex flex-wrap gap-3">
                    @if ($payrollRun->status->value !== 'locked')
                        <form method="POST" action="{{ route('payroll-runs.process', $payrollRun) }}" onsubmit="return confirm('Process payroll for {{ $employee->fullName() }} only?')">
                            @csrf
                            <input type="hidden" name="employee_ids[]" value="{{ $employee->id }}">
                            <button type="submit" class="btn-primary">
                                {{ $existingPayslip ? 'Regenerate Payslip' : 'Process This Employee' }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('payroll-runs.preview', $payrollRun) }}" class="btn-secondary">Back to Preview</a>
                </div>
            </div>

            <div class="card card-body">
                <div class="grid grid-cols-2 gap-6 mb-6 bg-surface-container-low rounded-lg p-4 text-body-sm">
                    <div>
                        <p class="form-label mb-2">Employee</p>
                        <p class="font-medium text-on-surface">{{ $employee->fullName() }}</p>
                        <p class="text-on-surface-variant">{{ $employee->employee_number }}</p>
                        <p class="text-on-surface-variant">{{ $employee->job_title }}</p>
                        <p class="text-on-surface-variant"><x-status-badge :status="$employee->employment_status" /></p>
                    </div>
                    <div>
                        <p class="form-label mb-2">Attendance</p>
                        <p class="text-on-surface-variant">Days worked: <strong class="text-on-surface">{{ $preview['days_worked'] }}</strong></p>
                        <p class="text-on-surface-variant">Hours worked: <strong class="text-on-surface">{{ number_format($preview['hours_worked'], 2) }}</strong></p>
                    </div>
                </div>

                <table class="table-list mb-6">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-right">Type</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($preview['items'] as $item)
                            <tr>
                                <td>{{ $item['description'] }}</td>
                                <td class="text-right capitalize text-on-surface-variant">{{ $item['type'] }}</td>
                                <td class="text-right {{ $item['type'] === 'deduction' ? 'text-error' : 'text-on-surface' }}">
                                    {{ $item['type'] === 'deduction' ? '-' : '' }}{{ number_format($item['amount'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="border-t-2 border-on-background pt-4 space-y-2 text-body-md">
                    <div class="flex justify-between"><span class="text-on-surface-variant">Gross Pay</span><span class="font-medium">{{ number_format($preview['gross_pay'], 2) }}</span></div>
                    <div class="flex justify-between text-error"><span>Tax</span><span>-{{ number_format($preview['total_tax'], 2) }}</span></div>
                    <div class="flex justify-between text-error"><span>Deductions</span><span>-{{ number_format($preview['total_deductions'], 2) }}</span></div>
                    <div class="flex justify-between text-h3 font-heading text-primary border-t border-outline-variant pt-3 mt-3">
                        <span>Net Pay</span><span>{{ number_format($preview['net_pay'], 2) }}</span>
                    </div>
                </div>
            </div>

            @if ($existingPayslip)
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-body-sm text-amber-900">
                    A payslip already exists for this employee in this run.
                    Processing again will replace it with the recalculated amounts.
                    <a href="{{ route('payslips.show', $existingPayslip) }}" class="link-primary ml-1">View current payslip</a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
