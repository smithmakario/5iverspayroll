<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\Employee;
use App\Services\PayrollAuditLogger;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class AttendanceClockService
{
    private const LATE_AFTER = '09:00:00';

    public function todayRecord(Employee $employee): ?Attendance
    {
        return Attendance::where('employee_id', $employee->id)
            ->whereDate('date', today())
            ->first();
    }

    public function clockIn(Employee $employee): Attendance
    {
        $record = $this->todayRecord($employee);

        if ($record?->isOnShift()) {
            throw ValidationException::withMessages([
                'clock' => 'You are already clocked in for today.',
            ]);
        }

        if ($record?->clock_out) {
            throw ValidationException::withMessages([
                'clock' => 'You have already completed your shift for today.',
            ]);
        }

        $now = now();
        $clockIn = $now->format('H:i:s');

        if ($record) {
            $record->update([
                'clock_in' => $clockIn,
                'clock_out' => null,
                'hours_worked' => null,
                'status' => $this->resolveStatus($clockIn),
                'is_approved' => false,
                'approved_by' => null,
                'approved_at' => null,
            ]);
        } else {
            $record = Attendance::create([
                'employee_id' => $employee->id,
                'date' => today(),
                'clock_in' => $clockIn,
                'status' => $this->resolveStatus($clockIn),
                'is_approved' => false,
            ]);
        }

        PayrollAuditLogger::log('attendance.clock_in', $record, [
            'employee_id' => $employee->id,
            'clock_in' => $clockIn,
        ]);

        return $record->fresh();
    }

    public function clockOut(Employee $employee): Attendance
    {
        $record = $this->todayRecord($employee);

        if (! $record || ! $record->clock_in) {
            throw ValidationException::withMessages([
                'clock' => 'You must clock in before clocking out.',
            ]);
        }

        if ($record->clock_out) {
            throw ValidationException::withMessages([
                'clock' => 'You have already clocked out for today.',
            ]);
        }

        $clockOut = now()->format('H:i:s');
        $hoursWorked = $this->calculateHoursWorked($record->clock_in, $clockOut, $record->break_minutes);

        $record->update([
            'clock_out' => $clockOut,
            'hours_worked' => $hoursWorked,
        ]);

        PayrollAuditLogger::log('attendance.clock_out', $record, [
            'employee_id' => $employee->id,
            'clock_out' => $clockOut,
            'hours_worked' => $hoursWorked,
        ]);

        return $record->fresh();
    }

    public function calculateHoursWorked(string $clockIn, string $clockOut, int $breakMinutes = 0): float
    {
        $in = Carbon::createFromFormat('H:i:s', strlen($clockIn) === 5 ? $clockIn.':00' : $clockIn);
        $out = Carbon::createFromFormat('H:i:s', strlen($clockOut) === 5 ? $clockOut.':00' : $clockOut);

        if ($out->lessThan($in)) {
            $out->addDay();
        }

        $minutes = $in->diffInMinutes($out) - $breakMinutes;

        return round(max(0, $minutes) / 60, 2);
    }

    private function resolveStatus(string $clockIn): AttendanceStatus
    {
        $normalized = strlen($clockIn) === 5 ? $clockIn.':00' : $clockIn;

        return $normalized > self::LATE_AFTER
            ? AttendanceStatus::Late
            : AttendanceStatus::Present;
    }
}
