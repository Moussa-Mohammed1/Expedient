<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = strtolower(trim((string) $request->query('q', '')));
        $words = $this->parseSearchWords($query);

        $coaches = $this->searchCoaches($words);
        $salles = $this->searchSalles($words);

        $topCoaches = Coach::top()
            ->take(8)
            ->get();

        $userLocal = auth()->user()?->localisation ?? '';

        $localSalles = Salle::query()
            ->when($userLocal !== '', function ($query) use ($userLocal) {
                $query->where('city', 'ilike', '%' . trim($userLocal) . '%');
            })
            ->latest()
            ->get();
        return view('trainee.explore.explore', [
            'query' => $query,
            'coaches' => $coaches,
            'salles' => $salles,
            'topCoaches' => $topCoaches,
            'localSalles' => $localSalles,
        ]);
    }

    private function parseSearchWords(string $query): array
    {
        return array_values(array_filter(explode(' ', $query), fn($word) => $word !== ''));
    }

    private function searchCoaches(array $words)
    {
        if ($words === []) {
            return collect();
        }

        return User::query()
            ->whereHas('coach')
            ->with('coach.salles')
            ->searchWords($words)
            ->latest()
            ->get();
    }

    private function searchSalles(array $words)
    {
        if ($words === []) {
            return collect();
        }

        return Salle::query()
            ->searchWords($words)
            ->latest()
            ->get();
    }
}
