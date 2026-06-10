<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEarningTypeRequest;
use App\Models\EarningType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EarningTypeController extends Controller
{
    public function index(): View
    {
        $earningTypes = EarningType::withCount('employeeEarnings')
            ->orderBy('name')
            ->paginate(20);

        return view('earning-types.index', compact('earningTypes'));
    }

    public function create(): View
    {
        return view('earning-types.create');
    }

    public function store(StoreEarningTypeRequest $request): RedirectResponse
    {
        EarningType::create($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('earning-types.index')
            ->with('success', 'Earning type created.');
    }

    public function edit(EarningType $earningType): View
    {
        return view('earning-types.edit', compact('earningType'));
    }

    public function update(StoreEarningTypeRequest $request, EarningType $earningType): RedirectResponse
    {
        $earningType->update($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('earning-types.index')
            ->with('success', 'Earning type updated.');
    }

    public function destroy(EarningType $earningType): RedirectResponse
    {
        if ($earningType->employeeEarnings()->exists()) {
            return back()->with('error', 'Cannot delete an earning type that is assigned to employees. Deactivate it instead.');
        }

        $earningType->delete();

        return redirect()->route('earning-types.index')
            ->with('success', 'Earning type deleted.');
    }
}
