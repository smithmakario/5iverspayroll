<?php

namespace App\Http\Controllers;

use App\Enums\AttendanceStatus;
use App\Http\Requests\StoreAttendanceRequest;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $employees = Employee::orderBy('last_name')->get();

        $query = Attendance::with('employee')
            ->orderByDesc('date')
            ->orderByDesc('clock_in');

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('month')) {
            $query->whereRaw('DATE_FORMAT(date, "%Y-%m") = ?', [$request->month]);
        }

        if ($request->boolean('today')) {
            $query->whereDate('date', today());
        }

        if ($request->boolean('on_shift')) {
            $query->whereDate('date', today())
                ->whereNotNull('clock_in')
                ->whereNull('clock_out');
        }

        $attendances = $query->paginate(30)->withQueryString();
        $statuses    = AttendanceStatus::cases();

        $stats = [
            'on_shift' => Attendance::whereDate('date', today())
                ->whereNotNull('clock_in')
                ->whereNull('clock_out')
                ->count(),
            'completed_today' => Attendance::whereDate('date', today())
                ->whereNotNull('clock_out')
                ->count(),
            'pending_approval' => Attendance::where('is_approved', false)
                ->whereNotNull('clock_out')
                ->count(),
        ];

        $clockedInNow = Attendance::with('employee')
            ->whereDate('date', today())
            ->whereNotNull('clock_in')
            ->whereNull('clock_out')
            ->orderBy('clock_in')
            ->get();

        return view('attendance.index', compact('attendances', 'employees', 'statuses', 'stats', 'clockedInNow'));
    }

    public function create(): View
    {
        $employees = Employee::where('employment_status', 'active')->orderBy('last_name')->get();
        $statuses  = AttendanceStatus::cases();

        return view('attendance.create', compact('employees', 'statuses'));
    }

    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['clock_in']) && !empty($data['clock_out'])) {
            $in    = strtotime($data['clock_in']);
            $out   = strtotime($data['clock_out']);
            $break = (int) ($data['break_minutes'] ?? 0);

            $data['hours_worked'] = round(($out - $in) / 3600 - $break / 60, 2);
        }

        Attendance::create($data);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record saved.');
    }

    public function edit(Attendance $attendance): View
    {
        $employees = Employee::where('employment_status', 'active')->orderBy('last_name')->get();
        $statuses  = AttendanceStatus::cases();

        return view('attendance.edit', compact('attendance', 'employees', 'statuses'));
    }

    public function update(StoreAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $data = $request->validated();

        if (!empty($data['clock_in']) && !empty($data['clock_out'])) {
            $in    = strtotime($data['clock_in']);
            $out   = strtotime($data['clock_out']);
            $break = (int) ($data['break_minutes'] ?? 0);

            $data['hours_worked'] = round(($out - $in) / 3600 - $break / 60, 2);
        }

        $attendance->update($data);

        return redirect()->route('attendance.index')
            ->with('success', 'Attendance record updated.');
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return back()->with('success', 'Attendance record deleted.');
    }

    public function approve(Attendance $attendance): RedirectResponse
    {
        if ($attendance->is_approved) {
            return back()->with('error', 'Timesheet already approved.');
        }

        $attendance->update([
            'is_approved' => true,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        \App\Services\PayrollAuditLogger::log('attendance.approved', $attendance);

        return back()->with('success', 'Timesheet approved for payroll.');
    }
}
