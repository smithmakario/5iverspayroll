<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Employees</h2></x-slot>
    <div class="page-shell">
        <div class="container-app space-y-6">
            <x-flash-messages />
            <x-page-header title="Employee Management" subtitle="Profiles, compensation, and direct deposit" action-label="Add Employee" :action-route="route('employees.create')" />

            <form method="GET" class="card card-body flex flex-wrap gap-4 items-end">
                <div>
                    <label class="form-label">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Name, email, or ID" class="form-input">
                </div>
                <div>
                    <label class="form-label">Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">All departments</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Location</label>
                    <select name="location_id" class="form-select">
                        <option value="">All locations</option>
                        @foreach ($locations as $location)
                            <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Employment Type</label>
                    <select name="employment_type" class="form-select">
                        <option value="">All types</option>
                        @foreach ($employmentTypes as $type)
                            <option value="{{ $type->value }}" {{ request('employment_type') === $type->value ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $type->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Status</label>
                    <select name="employment_status" class="form-select">
                        <option value="">All statuses</option>
                        @foreach ($employmentStatuses as $status)
                            <option value="{{ $status->value }}" {{ request('employment_status') === $status->value ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status->value)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Filter</button>
                <a href="{{ route('employees.index') }}" class="btn-secondary">Reset</a>
            </form>

            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <x-sortable-th column="employee_number" label="ID" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="name" label="Name" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="department" label="Department" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="location" label="Location" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="employment_type" label="Type" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="employment_status" label="Status" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="portal" label="Portal" :sort="$sort" :direction="$direction" />
                            <x-sortable-th column="hire_date" label="Hire Date" :sort="$sort" :direction="$direction" />
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
                                <td>{{ $employee->location?->name ?? '—' }}</td>
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
                            <tr><td colspan="9" class="text-center py-8 text-on-surface-variant">No employees found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $employees->links() }}</div>
        </div>
    </div>
</x-app-layout>
