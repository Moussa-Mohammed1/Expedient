<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salle\StoreSalleRequest;
use App\Http\Requests\Salle\UpdateSalleRequest;
use App\Models\Salle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SalleController extends Controller
{
    public function index(): View
    {
        $userLocation = auth()->user()?->localisation;

        return view('trainee.salles.index', [
            'salles' => Salle::with(['coach.user', 'sport', 'galleries'])
                ->when($userLocation, function ($query) use ($userLocation) {
                    $userLocal = strtolower(trim($userLocation));

                    $words = array_filter(explode(' ', $userLocal));

                    $query->when($words, function ($query) use ($words) {
                        $query->where(function ($q) use ($words) {
                            foreach ($words as $word) {
                                $q->orWhereRaw('LOWER(city) LIKE ?', ["%$word%"]);
                            }
                        }
                        );
                    });
                })
                ->latest()
                ->get(),
            'userLocation' => $userLocation,
        ]);
    }

    

    public function store(StoreSalleRequest $request): RedirectResponse
    {
        Salle::create($request->validated());

        return redirect()->route('salles.index')->with('success', 'Salle created successfully.');
    }

    public function show(Salle $salle): View
    {
        $salle->load([
            'coach.user',
            'sport',
            'galleries',
            'horaires',
            'services',
            'equipments',
        ]);

        return view('trainee.salles.show', compact('salle'));
    }

    public function update(UpdateSalleRequest $request, Salle $salle): RedirectResponse
    {
        $salle->update($request->validated());

        return redirect()->route('salles.index')->with('success', 'Salle updated successfully.');
    }

    public function destroy(Salle $salle): RedirectResponse
    {
        if ($salle->galleries()->exists()) {
            return redirect()->route('salles.index')->with('error', 'Salle cannot be deleted because it has galleries.');
        }

        $salle->delete();

        return redirect()->route('salles.index')->with('success', 'Salle deleted successfully.');
    }
}
