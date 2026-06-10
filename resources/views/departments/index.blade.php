<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Departments</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Departments" subtitle="Organizational units and cost centers" action-label="Add Department" :action-route="route('departments.create')" />
            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Employees</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($departments as $dept)
                            <tr>
                                <td class="font-mono text-on-surface-variant">{{ $dept->code }}</td>
                                <td class="font-medium">{{ $dept->name }}</td>
                                <td>{{ $dept->employees_count }}</td>
                                <td><x-status-badge :status="$dept->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right space-x-3">
                                    <a href="{{ route('departments.edit', $dept) }}" class="link-primary">Edit</a>
                                    <form method="POST" action="{{ route('departments.destroy', $dept) }}" class="inline" onsubmit="return confirm('Delete this department?')">
                                        @csrf @method('DELETE')
                                        <button class="text-error hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-on-surface-variant">No departments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $departments->links() }}</div>
        </div>
    </div>
</x-app-layout>
