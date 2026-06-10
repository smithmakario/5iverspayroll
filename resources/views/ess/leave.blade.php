<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Time Off</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />

            <div class="card card-body max-w-2xl">
                <x-page-header title="Request Time Off" subtitle="PTO balance: {{ number_format($employee->pto_balance, 1) }} days" />
                <form method="POST" action="{{ route('ess.leave.store') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="form-label" for="leave_type">Leave Type</label>
                            <select name="leave_type" id="leave_type" class="form-select" required>
                                @foreach (\App\Enums\LeaveType::cases() as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label" for="days_requested">Days Requested</label>
                            <input type="number" step="0.5" name="days_requested" id="days_requested" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label" for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-input" required>
                        </div>
                        <div>
                            <label class="form-label" for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-input" required>
                        </div>
                    </div>
                    <div>
                        <label class="form-label" for="reason">Reason</label>
                        <textarea name="reason" id="reason" rows="3" class="form-input"></textarea>
                    </div>
                    <button type="submit" class="btn-primary">Submit Request</button>
                </form>
            </div>

            <div class="card overflow-hidden">
                <div class="card-body pb-0"><x-page-header title="My Requests" /></div>
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Days</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($requests as $req)
                            <tr>
                                <td>{{ $req->leave_type->label() }}</td>
                                <td>{{ $req->start_date->format('d M') }} – {{ $req->end_date->format('d M Y') }}</td>
                                <td>{{ $req->days_requested }}</td>
                                <td><x-status-badge :status="$req->status" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-8 text-on-surface-variant">No leave requests yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $requests->links() }}</div>
        </div>
    </div>
</x-app-layout>
