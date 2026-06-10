<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Employees</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Employee Management" subtitle="Profiles, compensation, and direct deposit" action-label="Add Employee" :action-route="route('employees.create')" />

            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Portal</th>
                            <th>Hire Date</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee_number }}</td>
                                <td class="font-medium">
                                    <a href="{{ route('employees.show', $employee) }}" class="link-primary">{{ $employee->fullName() }}</a>
                                </td>
                                <td>{{ $employee->department?->name ?? '—' }}</td>
                                <td><x-status-badge :status="$employee->employment_type ?? 'full_time'" /></td>
                                <td><x-status-badge :status="$employee->employment_status" /></td>
                                <td>
                                    @if ($employee->user)
                                        <span class="{{ $employee->profile_confirmed_at ? 'chip-success' : 'chip-warning' }}">{{ $employee->profile_confirmed_at ? 'Active' : 'Pending' }}</span>
                                    @else
                                        <span class="chip-neutral">No account</span>
                                    @endif
                                </td>
                                <td>{{ $employee->hire_date->format('d M Y') }}</td>
                                <td class="text-right space-x-3">
                                    <a href="{{ route('employees.edit', $employee) }}" class="link-primary">Edit</a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline" onsubmit="return confirm('Remove this employee?')">
                                        @csrf @method('DELETE')
                                        <button class="text-error hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-8 text-on-surface-variant">No employees found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $employees->links() }}</div>
        </div>
    </div>
</x-app-layout>
