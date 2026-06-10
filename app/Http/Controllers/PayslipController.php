<?php

namespace App\Http\Controllers;

use App\Models\Payslip;
use Illuminate\View\View;

class PayslipController extends Controller
{
    public function show(Payslip $payslip): View
    {
        $this->authorize('view', $payslip);

        $payslip->load(['employee.department', 'employee.payGrade', 'payrollRun', 'items']);

        return view('payslips.show', compact('payslip'));
    }

    public function pdf(Payslip $payslip): View
    {
        $this->authorize('download', $payslip);

        $payslip->load(['employee.department', 'employee.payGrade', 'payrollRun', 'items']);

        return view('payslips.pdf', compact('payslip'));
    }
}
