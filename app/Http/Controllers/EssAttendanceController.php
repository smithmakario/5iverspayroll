<?php

namespace App\Http\Controllers;

use App\Services\AttendanceClockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EssAttendanceController extends Controller
{
    public function __construct(private AttendanceClockService $clock) {}

    public function index(): View|RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        $today = $this->clock->todayRecord($employee);
        $history = $employee->attendances()->orderByDesc('date')->paginate(20);

        return view('ess.attendance', compact('employee', 'today', 'history'));
    }

    public function clockIn(): RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        try {
            $this->clock->clockIn($employee);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first('clock'));
        }

        return back()->with('success', 'Clocked in at '.now()->format('h:i A').'.');
    }

    public function clockOut(): RedirectResponse
    {
        $employee = auth()->user()->employee;

        if (! $employee) {
            return redirect()->route('dashboard')->with('error', 'No employee profile linked.');
        }

        try {
            $record = $this->clock->clockOut($employee);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', $e->validator->errors()->first('clock'));
        }

        return back()->with('success', 'Clocked out at '.now()->format('h:i A').'. Hours worked: '.$record->hours_worked);
    }
}
