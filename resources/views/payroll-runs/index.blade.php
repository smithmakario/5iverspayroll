<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Payroll Runs</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Payroll Processing" subtitle="Create, approve, and finalize pay periods" action-label="New Payroll Run" :action-route="route('payroll-runs.create')" />
            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Period</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                            <th>Approved</th>
                            <th>Payslips</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($runs as $run)
                            <tr>
                                <td class="font-medium">
                                    <a href="{{ route('payroll-runs.show', $run) }}" class="link-primary">{{ $run->name }}</a>
                                </td>
                                <td>{{ $run->period_start->format('d M Y') }} – {{ $run->period_end->format('d M Y') }}</td>
                                <td>{{ $run->payment_date?->format('d M Y') ?? '—' }}</td>
                                <td><x-status-badge :status="$run->status" /></td>
                                <td>
                                    @if ($run->is_approved)
                                        <span class="chip-success">Yes</span>
                                    @else
                                        <span class="chip-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $run->payslips_count }}</td>
                                <td class="text-right"><a href="{{ route('payroll-runs.show', $run) }}" class="link-primary">View</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-8 text-on-surface-variant">No payroll runs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $runs->links() }}</div>
        </div>
    </div>
</x-app-layout>
