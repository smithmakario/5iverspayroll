<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDeductionTypeRequest;
use App\Models\DeductionType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeductionTypeController extends Controller
{
    public function index(): View
    {
        $deductionTypes = DeductionType::orderBy('name')->paginate(20);

        return view('deduction-types.index', compact('deductionTypes'));
    }

    public function create(): View
    {
        return view('deduction-types.create');
    }

    public function store(StoreDeductionTypeRequest $request): RedirectResponse
    {
        DeductionType::create($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('deduction-types.index')
            ->with('success', 'Deduction type created.');
    }

    public function edit(DeductionType $deductionType): View
    {
        return view('deduction-types.edit', compact('deductionType'));
    }

    public function update(StoreDeductionTypeRequest $request, DeductionType $deductionType): RedirectResponse
    {
        $deductionType->update($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('deduction-types.index')
            ->with('success', 'Deduction type updated.');
    }
}
