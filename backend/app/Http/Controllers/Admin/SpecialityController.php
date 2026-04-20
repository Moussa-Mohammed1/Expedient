<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Speciality;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SpecialityController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $editingSpecialityId = $request->integer('edit');

        $specialitiesQuery = Speciality::query()
            ->withCount('coaches')
            ->latest();

        if ($search !== '') {
            $specialitiesQuery->where('title', 'ilike', "%{$search}%");
        }

        $specialities = $specialitiesQuery
            ->paginate(10)
            ->withQueryString();

        $editingSpeciality = $editingSpecialityId
            ? Speciality::query()->withCount('coaches')->find($editingSpecialityId)
            : null;

        return view('admin.management.specialities.index', [
            'specialities' => $specialities,
            'search' => $search,
            'editingSpeciality' => $editingSpeciality,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('specialities', 'title')],
        ]);

        Speciality::create($validated);

        return redirect()
            ->route('management.specialities.index')
            ->with('success', 'Speciality created successfully.');
    }

    public function update(Request $request, Speciality $speciality): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('specialities', 'title')->ignore($speciality->id)],
        ]);

        $speciality->update($validated);

        return redirect()
            ->route('management.specialities.index', ['edit' => $speciality->id])
            ->with('success', 'Speciality updated successfully.');
    }

    public function destroy(Speciality $speciality): RedirectResponse
    {
        if ($speciality->coaches()->exists()) {
            return redirect()
                ->route('management.specialities.index', ['edit' => $speciality->id])
                ->with('error', 'This speciality is still assigned to some coaches.');
        }

        $speciality->delete();

        return redirect()
            ->route('management.specialities.index')
            ->with('success', 'Speciality deleted successfully.');
    }
}
