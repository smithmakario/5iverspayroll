<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Payslip</h2></x-slot>
    <div class="page-shell">
        <div class="container-app max-w-3xl">
            <div class="card card-body print:shadow-none print:border-0" id="payslip">
                <div class="flex justify-between items-start mb-6 border-b border-outline-variant pb-6">
                    <div>
                        <h1 class="font-heading text-h3 text-primary">5ivers Payroll</h1>
                        <p class="text-body-sm text-on-surface-variant">Period ending {{ $payslip->payrollRun->period_end->format('d M Y') }}</p>
                    </div>
                    <div class="text-right text-body-sm text-on-surface-variant">
                        <p>Payment: <strong class="text-on-surface">{{ $payslip->payrollRun->payment_date?->format('d M Y') ?? '—' }}</strong></p>
                        <p>{{ $payslip->payrollRun->name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6 bg-surface-container-low rounded-lg p-4 text-body-sm">
                    <div>
                        <p class="form-label mb-2">Employee</p>
                        <p class="font-medium text-on-surface">{{ $payslip->employee->fullName() }}</p>
                        <p class="text-on-surface-variant">{{ $payslip->employee->employee_number }}</p>
                        <p class="text-on-surface-variant">{{ $payslip->employee->job_title }}</p>
                        <p class="text-on-surface-variant">{{ $payslip->employee->department?->name }}</p>
                    </div>
                    <div>
                        <p class="form-label mb-2">Pay Grade</p>
                        <p class="font-medium">{{ $payslip->payGrade?->name ?? '—' }}</p>
                        <p class="text-on-surface-variant">{{ $payslip->payGrade ? number_format($payslip->payGrade->base_salary, 2).' '.$payslip->payGrade->currency : '' }}</p>
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
                        @foreach ($payslip->items as $item)
                            <tr>
                                <td>{{ $item->description }}</td>
                                <td class="text-right capitalize text-on-surface-variant">{{ $item->type->value }}</td>
                                <td class="text-right {{ $item->type->value === 'deduction' ? 'text-error' : 'text-on-surface' }}">
                                    {{ $item->type->value === 'deduction' ? '-' : '' }}{{ number_format($item->amount, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="border-t-2 border-on-background pt-4 space-y-2 text-body-md">
                    <div class="flex justify-between"><span class="text-on-surface-variant">Gross Pay</span><span class="font-medium">{{ number_format($payslip->gross_pay, 2) }}</span></div>
                    <div class="flex justify-between text-error"><span>Tax</span><span>-{{ number_format($payslip->total_tax, 2) }}</span></div>
                    <div class="flex justify-between text-error"><span>Deductions</span><span>-{{ number_format($payslip->total_deductions, 2) }}</span></div>
                    <div class="flex justify-between text-h3 font-heading text-primary border-t border-outline-variant pt-3 mt-3">
                        <span>Net Pay</span><span>{{ number_format($payslip->net_pay, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex gap-3 print:hidden">
                <a href="{{ route('payslips.pdf', $payslip) }}" target="_blank" class="btn-primary">Download PDF</a>
                <button onclick="window.print()" class="btn-secondary">Print</button>
                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Admin->value, \App\Enums\UserRole::Accountant->value, \App\Enums\UserRole::HrManager->value]))
                    <a href="{{ route('payroll-runs.show', $payslip->payrollRun) }}" class="btn-secondary">Back to Run</a>
                @else
                    <a href="{{ route('ess.payslips') }}" class="btn-secondary">Back to Payslips</a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
