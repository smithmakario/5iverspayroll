<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payslip — {{ $payslip->employee->fullName() }}</title>
    <style>
        body { font-family: 'Work Sans', Arial, sans-serif; color: #0b1c30; margin: 40px; }
        h1 { font-size: 24px; margin-bottom: 4px; }
        .meta { color: #5c4037; font-size: 14px; margin-bottom: 24px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5beb2; font-size: 14px; }
        th { text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; color: #5c4037; }
        .amount { text-align: right; }
        .total { font-weight: 700; font-size: 18px; color: #a83300; }
        .header { display: flex; justify-content: space-between; align-items: start; border-bottom: 2px solid #a83300; padding-bottom: 16px; }
        @media print { body { margin: 20px; } .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="background:#a83300;color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;">Print / Save as PDF</button>
    </div>

    <div class="header">
        <div>
            <h1>5ivers Payroll</h1>
            <p class="meta">Payslip for pay period {{ $payslip->payrollRun->period_start->format('d M Y') }} – {{ $payslip->payrollRun->period_end->format('d M Y') }}</p>
        </div>
        <div style="text-align:right;">
            <strong>{{ $payslip->employee->fullName() }}</strong><br>
            <span class="meta">#{{ $payslip->employee->employee_number }}</span>
        </div>
    </div>

    <table>
        <tr><th>Description</th><th class="amount">Amount</th></tr>
        @foreach ($payslip->items->where('type', 'earning') as $item)
            <tr><td>{{ $item->description }}</td><td class="amount">{{ number_format($item->amount, 2) }}</td></tr>
        @endforeach
        <tr><td colspan="2" style="padding-top:20px;"><strong>Deductions</strong></td></tr>
        @foreach ($payslip->items->where('type', 'deduction') as $item)
            <tr><td>{{ $item->description }}</td><td class="amount">-{{ number_format($item->amount, 2) }}</td></tr>
        @endforeach
        <tr>
            <td><strong>Net Pay</strong></td>
            <td class="amount total">{{ number_format($payslip->net_pay, 2) }}</td>
        </tr>
    </table>

    <p class="meta" style="margin-top:24px;">
        Days worked: {{ $payslip->days_worked }} | Hours: {{ number_format($payslip->hours_worked, 1) }}
    </p>
</body>
</html>
