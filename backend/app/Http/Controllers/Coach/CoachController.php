<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\User;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userLocal = auth()->user()?->localisation;

        $coaches = User::query()
            ->whereNotNull('localisation')
            ->where('localisation', 'like', '%' . $userLocal . '%')
            ->whereHas('coach')
            ->with([
                'coach' => function ($query) {
                    $query->withCount(['opinions as reviews_count' => function ($q) {
                        $q->where('isApproved', true);
                    }]);
                },
                'coach.specialities',
                'role'
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('trainee.coaches.index', compact('coaches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $coach = Coach::query()
            ->with([
                'user.role',
                'specialities',
                'opinions' => function ($query) {
                    $query->where('isApproved', true)
                        ->with('author')
                        ->latest();
                },
            ])
            ->withCount([
                'opinions as reviews_count' => function ($query) {
                    $query->where('isApproved', true);
                },
            ])
            ->findOrFail($id);

        $rating = (float) ($coach->opinions->avg('rate') ?? 0);

        return view('trainee.coaches.show', [
            'coach' => $coach,
            'coachUser' => $coach->user,
            'reviews' => $coach->opinions,
            'rating' => $rating,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
