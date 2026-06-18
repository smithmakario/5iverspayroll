<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Locations</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Locations" subtitle="Work sites and offices where staff are assigned" action-label="Add Location" :action-route="route('locations.create')" />
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
                        @forelse ($locations as $location)
                            <tr>
                                <td class="font-mono text-on-surface-variant">{{ $location->code }}</td>
                                <td class="font-medium">{{ $location->name }}</td>
                                <td>{{ $location->employees_count }}</td>
                                <td><x-status-badge :status="$location->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right space-x-3">
                                    <a href="{{ route('locations.edit', $location) }}" class="link-primary">Edit</a>
                                    <form method="POST" action="{{ route('locations.destroy', $location) }}" class="inline" onsubmit="return confirm('Delete this location?')">
                                        @csrf @method('DELETE')
                                        <button class="text-error hover:underline">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-8 text-on-surface-variant">No locations found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $locations->links() }}</div>
        </div>
    </div>
</x-app-layout>
