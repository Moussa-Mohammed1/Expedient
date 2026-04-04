<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Sport;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $userCommunity = auth()->user()?->memberships()
            ->whereNull('left_at')
            ->latest()
            ->with('community:id,title,description,backgroundImage,localisation')
            ->first()?->community;

        $sports = Sport::query()
            ->select(['id', 'title', 'icon'])
            ->withCount('salles')
            ->orderBy('title')
            ->get();

        $recentSalles = Salle::query()
            ->when(
                $user?->localisation,
                fn($query) => $query->whereLike('city', $user->localisation),
                fn($query) => $query->whereRaw('1 = 0')
            )
            ->latest()
            ->take(3)
            ->get(['id', 'name', 'city', 'created_at']);

        return view('trainee.home.home', compact('sports', 'recentSalles', 'userCommunity'));
    }
}
