<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Pay Grades</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Pay Grades" subtitle="Salary bands and compensation tiers" action-label="Add Pay Grade" :action-route="route('pay-grades.create')" />
            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Base Salary</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payGrades as $grade)
                            <tr>
                                <td class="font-mono text-on-surface-variant">{{ $grade->code }}</td>
                                <td class="font-medium">{{ $grade->name }}</td>
                                <td>{{ number_format($grade->base_salary, 2) }} {{ $grade->currency }}</td>
                                <td>{{ $grade->employees_count }}</td>
                                <td><x-status-badge :status="$grade->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right space-x-3">
                                    <a href="{{ route('pay-grades.edit', $grade) }}" class="link-primary">Edit</a>
                                    <form method="POST" action="{{ route('pay-grades.destroy', $grade) }}" class="inline" onsubmit="return confirm('Delete this pay grade?')">
                                        @csrf @method('DELETE')
                                        <button class="text-error hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-8 text-on-surface-variant">No pay grades found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $payGrades->links() }}</div>
        </div>
    </div>
</x-app-layout>
