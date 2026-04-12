<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Opinion;
use App\Models\Salle;
use App\Models\Sport;
use App\Models\Trainee;

class WelcomeController extends Controller
{
    public function index()
    {

        $activeAthletes = Trainee::where('isBanned', false)->count();
        $verifiedCoaches = Coach::where('hasBadge', true)->count();
        $gymsCount = Salle::count();
        $citiesCount = Salle::distinct('city')->count('city');
        $avgRatingDb = Opinion::avg('rate');
        $averageRating = $avgRatingDb ? number_format($avgRatingDb, 1) : "0.0";
        $sports = Sport::count();
        $coaches = Coach::where('hasBadge',true)->count();
        $featuredCoaches = Coach::query()
            ->whereHas('user')
            ->with([
                'user:id,name,avatar',
                'specialities:id,title',
            ])
            ->withAvg(['opinions as approved_rating_avg' => function ($query) {
                $query->where('isApproved', true);
            }], 'rate')
            ->orderByDesc('hasBadge')
            ->orderByDesc('reputation_rate')
            ->orderByDesc('approved_rating_avg')
            ->take(3)
            ->get()
            ->map(function ($coach) {
                $computedRating = (float) ($coach->allowed_rating_avg ?? $coach->reputation_rate ?? 0);
                $speciality = $coach->specialities->pluck('title')->filter()->take(2)->implode(' & ');

                return [
                    'name' => $coach->user?->name ?? 'Coach',
                    'avatar' => $coach->user?->avatar ?: 'https://randomuser.me/api/portraits/lego/1.jpg',
                    'speciality' => $speciality !== '' ? $speciality : 'General Coaching',
                    'rating' => max(0, min(5, round($computedRating, 1))),
                    'hasBadge' => (bool) $coach->hasBadge,
                ];
            });

        return view('welcome', compact('sports', 'coaches',  'activeAthletes', 'verifiedCoaches', 'gymsCount', 'citiesCount', 'averageRating', 'featuredCoaches'));
    }
}
