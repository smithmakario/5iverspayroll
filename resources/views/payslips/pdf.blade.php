<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payslip — {{ $payslip->employee->fullName() }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            font-family: 'Work Sans', Arial, sans-serif;
            color: #0b1c30;
            background: #f8f9ff;
            font-size: 14px;
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            border: 1px solid #e5beb2;
            border-radius: 8px;
            overflow: hidden;
        }

        .no-print {
            max-width: 800px;
            margin: 24px auto 16px;
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
        }

        .btn-primary { background: #a83300; color: #ffffff; }
        .btn-secondary { background: #e2e2e5; color: #0b1c30; }

        .payslip-body { padding: 32px; }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2px solid #a83300;
            margin-bottom: 24px;
            padding-bottom: 20px;
        }

        .header-table td { vertical-align: top; padding-bottom: 20px; }

        .logo {
            width: 40px;
            height: 40px;
            margin-bottom: 8px;
        }

        .company-name {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #a83300;
            margin-bottom: 4px;
        }

        .meta { color: #5c4037; font-size: 13px; }

        .payslip-label {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #5c4037;
            margin-bottom: 6px;
        }

        .header-right { text-align: right; }

        .info-panel {
            width: 100%;
            border-collapse: collapse;
            background: #eff4ff;
            border-radius: 8px;
            margin-bottom: 24px;
        }

        .info-panel td {
            padding: 20px;
            vertical-align: top;
            width: 50%;
        }

        .info-panel td + td {
            border-left: 1px solid #e5beb2;
        }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #5c4037;
            margin-bottom: 10px;
        }

        .employee-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .employee-detail {
            color: #5c4037;
            font-size: 13px;
            margin-bottom: 2px;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .summary-row span:first-child { display: table-cell; color: #5c4037; }
        .summary-row span:last-child { display: table-cell; text-align: right; font-weight: 500; }

        .summary-row.deduction span:last-child { color: #ba1a1a; }

        .summary-row.net {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e5beb2;
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #a83300;
        }

        .line-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .line-items > tbody > tr > td {
            vertical-align: top;
            width: 50%;
            padding: 0;
        }

        .line-items > tbody > tr > td + td {
            padding-left: 16px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
        }

        .items-table th {
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #5c4037;
            padding: 8px 12px;
            border-bottom: 1px solid #e5beb2;
            background: #f8f9ff;
        }

        .items-table th.amount { text-align: right; }

        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e5beb2;
            font-size: 13px;
        }

        .items-table td.amount {
            text-align: right;
            white-space: nowrap;
        }

        .items-table td.deduction { color: #ba1a1a; }

        .items-heading {
            font-family: 'Plus Jakarta Sans', Arial, sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #0b1c30;
            margin-bottom: 8px;
        }

        .empty-row td {
            color: #5c4037;
            font-style: italic;
            padding: 12px;
        }

        .footer {
            border-top: 1px solid #e5beb2;
            padding-top: 16px;
            display: table;
            width: 100%;
            font-size: 12px;
            color: #5c4037;
        }

        .footer span { display: table-cell; }
        .footer span:last-child { text-align: right; }

        .confidential {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #907065;
            letter-spacing: 0.04em;
        }

        @media print {
            body { background: #ffffff; }
            .no-print { display: none !important; }
            .page { border: none; border-radius: 0; max-width: none; }
            .payslip-body { padding: 0; }
            .info-panel, .items-table th { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }

        @media screen {
            body { padding: 0 16px 32px; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" onclick="window.print()" class="btn btn-primary">Print / Save as PDF</button>
        <a href="{{ route('payslips.show', $payslip) }}" class="btn btn-secondary">Back to Payslip</a>
    </div>

    <div class="page">
        <div class="payslip-body">
            <table class="header-table">
                <tr>
                    <td>
                        <img src="{{ asset('asset/logo/logo.png') }}" alt="5ivers" class="logo">
                        <div class="company-name">5ivers Payroll</div>
                        <p class="meta">
                            Pay period: {{ $payslip->payrollRun->period_start->format('d M Y') }}
                            – {{ $payslip->payrollRun->period_end->format('d M Y') }}
                        </p>
                    </td>
                    <td class="header-right">
                        <div class="payslip-label">Payslip</div>
                        <p class="meta">
                            Payment date:
                            <strong style="color:#0b1c30;">{{ $payslip->payrollRun->payment_date?->format('d M Y') ?? '—' }}</strong>
                        </p>
                        <p class="meta">{{ $payslip->payrollRun->name }}</p>
                    </td>
                </tr>
            </table>

            @php
                $currency = $payslip->payGrade?->currency ?? $payslip->employee->payGrade?->currency ?? 'NGN';
            @endphp

            <table class="info-panel">
                <tr>
                    <td>
                        <div class="section-label">Employee</div>
                        <div class="employee-name">{{ $payslip->employee->fullName() }}</div>
                        <div class="employee-detail">{{ $payslip->employee->employee_number }}</div>
                        @if ($payslip->employee->job_title)
                            <div class="employee-detail">{{ $payslip->employee->job_title }}</div>
                        @endif
                        @if ($payslip->employee->department)
                            <div class="employee-detail">{{ $payslip->employee->department->name }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="section-label">Pay Summary ({{ $currency }})</div>
                        <div class="summary-row">
                            <span>Gross Pay</span>
                            <span>{{ number_format($payslip->gross_pay, 2) }}</span>
                        </div>
                        <div class="summary-row deduction">
                            <span>Tax</span>
                            <span>-{{ number_format($payslip->total_tax, 2) }}</span>
                        </div>
                        <div class="summary-row deduction">
                            <span>Deductions</span>
                            <span>-{{ number_format($payslip->total_deductions, 2) }}</span>
                        </div>
                        <div class="summary-row net">
                            <span>Net Pay</span>
                            <span>{{ number_format($payslip->net_pay, 2) }}</span>
                        </div>
                    </td>
                </tr>
            </table>

            <table class="line-items">
                <tbody>
                    <tr>
                        <td>
                            <div class="items-heading">Earnings</div>
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th class="amount">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payslip->items->where('type', 'earning') as $item)
                                        <tr>
                                            <td>{{ $item->description }}</td>
                                            <td class="amount">{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr class="empty-row"><td colspan="2">No earnings recorded.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                        <td>
                            <div class="items-heading">Deductions</div>
                            <table class="items-table">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th class="amount">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($payslip->items->where('type', 'deduction') as $item)
                                        <tr>
                                            <td>{{ $item->description }}</td>
                                            <td class="amount deduction">-{{ number_format($item->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr class="empty-row"><td colspan="2">No deductions recorded.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="footer">
                <span>
                    Days worked: {{ $payslip->days_worked }}
                    &nbsp;|&nbsp;
                    Hours: {{ number_format($payslip->hours_worked, 1) }}
                </span>
                <span>Generated {{ now()->format('d M Y') }}</span>
            </div>

            <p class="confidential">CONFIDENTIAL — FOR EMPLOYEE USE ONLY</p>
        </div>
    </div>
</body>
</html>
