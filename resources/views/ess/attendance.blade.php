<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Time Clock</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />

            <div class="card card-body">
                <x-page-header title="Today's Shift" subtitle="{{ now()->format('l, d M Y') }}" />

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="stat-card">
                        <p class="stat-label">Clock In</p>
                        <p class="stat-value text-h3">{{ $today?->formattedClockIn() ?? '—' }}</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-label">Clock Out</p>
                        <p class="stat-value text-h3">{{ $today?->formattedClockOut() ?? '—' }}</p>
                    </div>
                    <div class="stat-card">
                        <p class="stat-label">Hours Worked</p>
                        <p class="stat-value text-h3">
                            @if ($today?->isOnShift())
                                <span class="chip-info">On shift</span>
                            @else
                                {{ $today?->hours_worked ?? '—' }}
                            @endif
                        </p>
                    </div>
                </div>

                @if ($today?->isOnShift())
                    <div class="rounded-lg border border-primary/20 bg-primary-container/20 p-4 mb-6 text-body-sm text-on-surface">
                        You are currently <strong>clocked in</strong> since {{ $today->formattedClockIn() }}. Clock out when your shift ends.
                    </div>
                    <form method="POST" action="{{ route('ess.attendance.clock-out') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Clock Out</button>
                    </form>
                @elseif ($today?->clock_out)
                    <div class="rounded-lg border border-outline-variant bg-surface-container-low p-4 text-body-sm text-on-surface-variant">
                        Shift complete for today. Hours recorded: <strong>{{ $today->hours_worked }}</strong>
                        @unless ($today->is_approved)
                            — pending manager approval for payroll.
                        @endunless
                    </div>
                @else
                    <form method="POST" action="{{ route('ess.attendance.clock-in') }}">
                        @csrf
                        <button type="submit" class="btn-primary">Clock In</button>
                    </form>
                @endif
            </div>

            <div class="card overflow-hidden">
                <div class="card-body pb-0">
                    <x-page-header title="My Attendance History" subtitle="Clock-in and clock-out records" />
                </div>
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                            <th>Approved</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $record)
                            <tr>
                                <td>{{ $record->date->format('d M Y') }}</td>
                                <td>{{ $record->formattedClockIn() ?? '—' }}</td>
                                <td>
                                    @if ($record->isOnShift() && $record->date->isToday())
                                        <span class="chip-info">On shift</span>
                                    @else
                                        {{ $record->formattedClockOut() ?? '—' }}
                                    @endif
                                </td>
                                <td>{{ $record->hours_worked ?? ($record->isOnShift() ? '—' : '0.00') }}</td>
                                <td><x-status-badge :status="$record->status" /></td>
                                <td>
                                    @if ($record->is_approved)
                                        <span class="chip-success">Yes</span>
                                    @elseif ($record->clock_out)
                                        <span class="chip-warning">Pending</span>
                                    @else
                                        <span class="chip-neutral">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-8 text-on-surface-variant">No attendance records yet. Clock in to start tracking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="card-body pt-0">{{ $history->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
