<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Bonuses & Commissions</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Earning Types" subtitle="Bonus, commission, and allowance templates applied to payslips" action-label="Add Earning Type" :action-route="route('earning-types.create')" />
            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Calculation</th>
                            <th>Default</th>
                            <th>Assigned</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($earningTypes as $type)
                            <tr>
                                <td class="font-medium">{{ $type->name }}</td>
                                <td>{{ $type->code }}</td>
                                <td><x-status-badge :status="$type->category->value" /></td>
                                <td>{{ ucfirst($type->calculation_type->value) }}</td>
                                <td>
                                    @if ($type->default_amount) {{ number_format($type->default_amount, 2) }}
                                    @elseif ($type->default_rate) {{ $type->default_rate }}%
                                    @else — @endif
                                </td>
                                <td>{{ $type->employee_earnings_count }}</td>
                                <td><x-status-badge :status="$type->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right space-x-3">
                                    <a href="{{ route('earning-types.edit', $type) }}" class="link-primary">Edit</a>
                                    @if ($type->employee_earnings_count === 0)
                                        <form method="POST" action="{{ route('earning-types.destroy', $type) }}" class="inline" onsubmit="return confirm('Delete this earning type?')">
                                            @csrf @method('DELETE')
                                            <button class="text-error hover:underline">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-8 text-on-surface-variant">No earning types configured. Add bonus or commission templates to get started.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $earningTypes->links() }}</div>
        </div>
    </div>
</x-app-layout>
