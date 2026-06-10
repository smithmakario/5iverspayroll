<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayGradeRequest;
use App\Http\Requests\UpdatePayGradeRequest;
use App\Models\PayGrade;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PayGradeController extends Controller
{
    public function index(): View
    {
        $payGrades = PayGrade::withCount('employees')->orderBy('name')->paginate(20);

        return view('pay-grades.index', compact('payGrades'));
    }

    public function create(): View
    {
        return view('pay-grades.create');
    }

    public function store(StorePayGradeRequest $request): RedirectResponse
    {
        PayGrade::create($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('pay-grades.index')
            ->with('success', 'Pay grade created successfully.');
    }

    public function edit(PayGrade $payGrade): View
    {
        return view('pay-grades.edit', compact('payGrade'));
    }

    public function update(UpdatePayGradeRequest $request, PayGrade $payGrade): RedirectResponse
    {
        $payGrade->update($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('pay-grades.index')
            ->with('success', 'Pay grade updated successfully.');
    }

    public function destroy(PayGrade $payGrade): RedirectResponse
    {
        if ($payGrade->employees()->exists()) {
            return back()->with('error', 'Cannot delete a pay grade that has employees assigned.');
        }

        $payGrade->delete();

        return redirect()->route('pay-grades.index')
            ->with('success', 'Pay grade deleted successfully.');
    }
}
