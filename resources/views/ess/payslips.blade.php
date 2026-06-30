<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">My Payslips</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Payslip History" subtitle="Document vault for your pay records" />

            <div class="card overflow-hidden">
                {{-- Mobile: stacked cards --}}
                <div class="md:hidden divide-y divide-outline-variant">
                    @forelse ($payslips as $payslip)
                        <div class="p-4 space-y-4">
                            <div>
                                <p class="form-label mb-1">Pay Period</p>
                                <p class="font-medium text-on-surface">
                                    {{ $payslip->payrollRun->period_start->format('d M') }} – {{ $payslip->payrollRun->period_end->format('d M Y') }}
                                </p>
                            </div>
                            <dl class="grid grid-cols-3 gap-3 text-body-sm">
                                <div>
                                    <dt class="form-label mb-1">Gross</dt>
                                    <dd class="font-medium text-on-surface">{{ number_format($payslip->gross_pay, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="form-label mb-1">Deductions</dt>
                                    <dd class="font-medium text-on-surface">{{ number_format($payslip->total_tax + $payslip->total_deductions, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="form-label mb-1">Net Pay</dt>
                                    <dd class="font-semibold text-primary">{{ number_format($payslip->net_pay, 2) }}</dd>
                                </div>
                            </dl>
                            <div class="flex gap-3">
                                <a href="{{ route('payslips.show', $payslip) }}" class="btn-primary flex-1 justify-center">View</a>
                                <a href="{{ route('payslips.pdf', $payslip) }}" target="_blank" class="btn-secondary flex-1 justify-center">PDF</a>
                            </div>
                        </div>
                    @empty
                        <p class="text-center py-8 text-on-surface-variant px-4">No payslips available yet.</p>
                    @endforelse
                </div>

                {{-- Desktop: table --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Gross</th>
                                <th>Deductions</th>
                                <th>Net Pay</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payslips as $payslip)
                                <tr>
                                    <td>{{ $payslip->payrollRun->period_start->format('d M') }} – {{ $payslip->payrollRun->period_end->format('d M Y') }}</td>
                                    <td>{{ number_format($payslip->gross_pay, 2) }}</td>
                                    <td>{{ number_format($payslip->total_tax + $payslip->total_deductions, 2) }}</td>
                                    <td class="font-semibold">{{ number_format($payslip->net_pay, 2) }}</td>
                                    <td class="text-right space-x-3 whitespace-nowrap">
                                        <a href="{{ route('payslips.show', $payslip) }}" class="link-primary">View</a>
                                        <a href="{{ route('payslips.pdf', $payslip) }}" target="_blank" class="link-primary">PDF</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center py-8 text-on-surface-variant">No payslips available yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-4">{{ $payslips->links() }}</div>
        </div>
    </div>
</x-app-layout>
