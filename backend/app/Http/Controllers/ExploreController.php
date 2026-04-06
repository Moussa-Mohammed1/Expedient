<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Salle;
use App\Models\Sport;

class ExploreController extends Controller
{
    public function index()
    {
        $topCoaches = Coach::top()
            ->take(8)
            ->get();

        $userLocal = auth()->user()?->localisation ?? '';
        
        $localSalles = Salle::query()
            ->when($userLocal !== '', function ($query) use ($userLocal) {
                $query->whereLike('city', '%' . $userLocal . '%');
            })
            ->latest()
            ->get();
        return view('trainee.explore.explore', [
            'topCoaches' => $topCoaches,
            'localSalles' => $localSalles,
        ]);
    }
}
