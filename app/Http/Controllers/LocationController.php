<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(): View
    {
        $locations = Location::withCount('employees')->orderBy('name')->paginate(20);

        return view('locations.index', compact('locations'));
    }

    public function create(): View
    {
        return view('locations.create');
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        Location::create($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('locations.index')
            ->with('success', 'Location created successfully.');
    }

    public function edit(Location $location): View
    {
        return view('locations.edit', compact('location'));
    }

    public function update(StoreLocationRequest $request, Location $location): RedirectResponse
    {
        $location->update($request->safe()->merge(['is_active' => $request->boolean('is_active', true)])->all());

        return redirect()->route('locations.index')
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        if ($location->employees()->exists()) {
            return back()->with('error', 'Cannot delete a location that has employees assigned.');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Location deleted successfully.');
    }
}
