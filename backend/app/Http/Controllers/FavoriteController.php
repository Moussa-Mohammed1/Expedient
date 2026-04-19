<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function store(Request $request, Salle $salle): RedirectResponse
    {
        $request->user()->favoriteSalles()->syncWithoutDetaching([$salle->id]);

        return back()->with('success', 'Salle added to favorites.');
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $favoriteSalles = $user
            ->favoriteSalles()
            ->with([
                'sport:id,title',
                'coach.user:id,name',
                'galleries:id,salle_id,content',
            ])
            ->latest('favorites.created_at')
            ->get();

        return view('trainee.favoris.index', [
            'favoriteSalles' => $favoriteSalles,
            'favoriteCount' => $favoriteSalles->count(),
        ]);
    }

    public function destroy(Request $request, Salle $salle): RedirectResponse
    {
        $request->user()->favoriteSalles()->detach($salle->id);

        return back()->with('success', 'Salle removed from favorites.');
    }
}
