<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SportController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $editingSportId = $request->integer('edit');

        $sportsQuery = Sport::query()
            ->withCount('salles')
            ->latest();

        if ($search !== '') {
            $sportsQuery->where('title', 'ilike', "%{$search}%");
        }

        $sports = $sportsQuery
            ->paginate(10)
            ->withQueryString();

        $editingSport = $editingSportId
            ? Sport::query()->withCount('salles')->find($editingSportId)
            : null;

        return view('admin.management.sports.index', [
            'sports' => $sports,
            'search' => $search,
            'editingSport' => $editingSport,
            'iconOptions' => [
                'fa-person-running' => 'Running',
                'fa-dumbbell' => 'Fitness',
                'fa-basketball' => 'Basketball',
                'fa-futbol' => 'Football',
                'fa-person-swimming' => 'Swimming',
                'fa-person-biking' => 'Cycling',
                'fa-hands-praying' => 'Yoga',
                'fa-hand-fist' => 'Combat',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
        ]);

        Sport::create($validated);

        return redirect()
            ->route('management.sports.index')
            ->with('success', 'Sport created successfully.');
    }

    public function update(Request $request, Sport $sport): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:255'],
        ]);

        $sport->update($validated);

        return redirect()
            ->route('management.sports.index', ['edit' => $sport->id])
            ->with('success', 'Sport updated successfully.');
    }

    public function destroy(Sport $sport): RedirectResponse
    {
        if ($sport->salles()->exists()) {
            return redirect()
                ->route('management.sports.index', ['edit' => $sport->id])
                ->with('error', 'This sport is still assigned to some salles and can not be deleted.');
        }

        $sport->delete();

        return redirect()
            ->route('management.sports.index')
            ->with('success', 'Sport deleted successfully.');
    }
}
