<x-app-layout>
    <x-slot name="header"><h2 class="font-heading text-h3">Deduction Types</h2></x-slot>
    <div class="page-shell">
        <div class="container-app">
            <x-flash-messages />
            <x-page-header title="Earnings & Deductions" subtitle="Tax, statutory, and voluntary deduction templates" action-label="Add Deduction Type" :action-route="route('deduction-types.create')" />
            <div class="card overflow-hidden">
                <table class="table-list">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Calculation</th>
                            <th>Default</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($deductionTypes as $type)
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
                                <td><x-status-badge :status="$type->is_active ? 'active' : 'terminated'" /></td>
                                <td class="text-right"><a href="{{ route('deduction-types.edit', $type) }}" class="link-primary">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-8 text-on-surface-variant">No deduction types configured.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $deductionTypes->links() }}</div>
        </div>
    </div>
</x-app-layout>
