<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">My Payslips</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Payslip History" subtitle="Document vault for your pay records" />
            <div class="card overflow-hidden">
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
                                <td class="text-right space-x-3">
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
            <div class="mt-4">{{ $payslips->links() }}</div>
        </div>
    </div>
</x-app-layout>
