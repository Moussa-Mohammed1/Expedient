<?php

namespace App\Http\Controllers;

use App\Models\Salle;
use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('welcome');
        }

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
            ->when($user?->localisation, function ($query) use ($user) {
                $userLocal = strtolower(trim($user->localisation));

                $words = array_filter(explode(' ', $userLocal));

                $query->when($words, function ($query) use ($words) {
                    $query->where(function ($q) use ($words) {
                        foreach ($words as $word) {
                            $q->orWhereRaw('LOWER(city) LIKE ?', ["%$word%"]);
                        }
                    });
                });
            }, fn($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->take(3)
            ->get(['id', 'name', 'city', 'background', 'created_at']);

        return view('trainee.home.home', compact('sports', 'recentSalles', 'userCommunity'));
    }
}
