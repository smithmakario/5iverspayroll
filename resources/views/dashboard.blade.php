<x-app-layout>
    <x-slot name="header">
        <h2 class="font-heading text-h3">Operations Dashboard</h2>
    </x-slot>

    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="stat-card">
                    <p class="stat-label">Active Employees</p>
                    <p class="stat-value">{{ $stats['employees'] }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Pending Leave</p>
                    <p class="stat-value">{{ $stats['pending_leave'] }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Timesheets to Approve</p>
                    <p class="stat-value">{{ $stats['pending_attendance'] }}</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Draft Payrolls</p>
                    <p class="stat-value">{{ $stats['draft_payrolls'] }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <x-page-header title="Recent Payroll Runs" subtitle="Review and process pay periods" />
                    <table class="table-list">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Period</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($stats['recent_runs'] as $run)
                                <tr>
                                    <td class="font-medium">{{ $run->name }}</td>
                                    <td>{{ $run->period_start->format('d M') }} – {{ $run->period_end->format('d M Y') }}</td>
                                    <td><x-status-badge :status="$run->status" /></td>
                                    <td class="text-right"><a href="{{ route('payroll-runs.show', $run) }}" class="link-primary">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-on-surface-variant py-8">No payroll runs yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
