<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Leave Management</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="PTO Requests" subtitle="Manager approval workflow" />
            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td class="font-medium">{{ $req->employee->fullName() }}</td>
                                <td>{{ $req->leave_type->label() }}</td>
                                <td>{{ $req->start_date->format('d M') }} – {{ $req->end_date->format('d M Y') }}</td>
                                <td>{{ $req->days_requested }}</td>
                                <td><x-status-badge :status="$req->status" /></td>
                                <td class="text-right">
                                    @if ($req->status->value === 'pending')
                                        <form method="POST" action="{{ route('leave-requests.approve', $req) }}" class="inline">
                                            @csrf
                                            <button class="link-primary">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('leave-requests.reject', $req) }}" class="inline ml-3">
                                            @csrf
                                            <button class="text-error hover:underline">Reject</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-8 text-on-surface-variant">No leave requests.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $requests->links() }}</div>
        </div>
    </div>
</x-app-layout>
