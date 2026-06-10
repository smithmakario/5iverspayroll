<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Attendance</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />
            <x-page-header title="Clock-In / Clock-Out Tracking" subtitle="Monitor live shifts and approve timesheets for payroll" action-label="Add Record" :action-route="route('attendance.create')" />

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="stat-card">
                    <p class="stat-label">Currently On Shift</p>
                    <p class="stat-value">{{ $stats['on_shift'] }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Completed Today</p>
                    <p class="stat-value">{{ $stats['completed_today'] }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Pending Approval</p>
                    <p class="stat-value">{{ $stats['pending_approval'] }}</p>
                </div>
            </div>

            @if ($clockedInNow->isNotEmpty())
                <div class="card card-body">
                    <h3 class="font-heading text-h3 mb-4">Live — Clocked In Now</h3>
                    <div class="flex flex-wrap gap-3">
                        @foreach ($clockedInNow as $record)
                            <div class="rounded-lg border border-outline-variant px-4 py-3 text-body-sm">
                                <span class="font-medium">{{ $record->employee->fullName() }}</span>
                                <span class="text-on-surface-variant">since {{ $record->formattedClockIn() }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="GET" class="card card-body flex flex-wrap gap-4 items-end">
                <div>
                    <label class="form-label">Employee</label>
                    <select name="employee_id" class="form-select">
                        <option value="">All employees</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->fullName() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Month</label>
                    <input type="month" name="month" value="{{ request('month') }}" class="form-input">
                </div>
                <label class="inline-flex items-center gap-2 pb-2">
                    <input type="checkbox" name="today" value="1" class="rounded border-outline-variant text-primary focus:ring-primary" @checked(request()->boolean('today'))>
                    <span class="text-body-sm">Today only</span>
                </label>
                <label class="inline-flex items-center gap-2 pb-2">
                    <input type="checkbox" name="on_shift" value="1" class="rounded border-outline-variant text-primary focus:ring-primary" @checked(request()->boolean('on_shift'))>
                    <span class="text-body-sm">On shift now</span>
                </label>
                <button type="submit" class="btn-primary">Filter</button>
                <a href="{{ route('attendance.index') }}" class="btn-secondary">Reset</a>
            </form>

            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $record)
                            <tr class="{{ $record->isOnShift() && $record->date->isToday() ? 'bg-primary-container/10' : '' }}">
                                <td class="font-medium">{{ $record->employee->fullName() }}</td>
                                <td>{{ $record->date->format('d M Y') }}</td>
                                <td>{{ $record->formattedClockIn() ?? '—' }}</td>
                                <td>
                                    @if ($record->isOnShift() && $record->date->isToday())
                                        <span class="chip-info">On shift</span>
                                    @else
                                        {{ $record->formattedClockOut() ?? '—' }}
                                    @endif
                                </td>
                                <td>{{ $record->hours_worked ?? '—' }}</td>
                                <td><x-status-badge :status="$record->status" /></td>
                                <td>
                                    @if ($record->is_approved)
                                        <span class="chip-success">Approved</span>
                                    @elseif ($record->clock_out)
                                        <span class="chip-warning">Pending</span>
                                    @else
                                        <span class="chip-neutral">In progress</span>
                                    @endif
                                </td>
                                <td class="text-right space-x-3">
                                    @if ($record->clock_out && ! $record->is_approved)
                                        <form method="POST" action="{{ route('attendance.approve', $record) }}" class="inline">
                                            @csrf
                                            <button class="link-primary">Approve</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('attendance.edit', $record) }}" class="link-primary">Edit</a>
                                    <form method="POST" action="{{ route('attendance.destroy', $record) }}" class="inline" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button class="text-error hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-8 text-on-surface-variant">No attendance records found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $attendances->links() }}</div>
        </div>
    </div>
</x-app-layout>
