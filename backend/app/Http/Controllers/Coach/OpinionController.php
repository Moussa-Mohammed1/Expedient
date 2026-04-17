<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\Opinion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OpinionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|RedirectResponse
    {
        $coach = $request->user()?->coach;

        if (!$coach) {
            return redirect('/home');
        }

        $opinionsQuery = Opinion::query()
            ->where('coach_id', $coach->id)
            ->with('author:id,name,avatar')
            ->latest();

        $opinions = $opinionsQuery->paginate(12);

        $totalReviews = Opinion::query()->where('coach_id', $coach->id)->count();
        $approvedReviews = Opinion::query()->where('coach_id', $coach->id)->where('isApproved', true)->count();
        $unapprovedReviews = Opinion::query()->where('coach_id', $coach->id)->where('isApproved', false)->count();
        $averageRating = (float) (Opinion::query()->where('coach_id', $coach->id)->avg('rate') ?? 0);

        return view('coach.opinions.index', [
            'coach' => $coach,
            'opinions' => $opinions,
            'totalReviews' => $totalReviews,
            'approvedReviews' => $approvedReviews,
            'unapprovedReviews' => $unapprovedReviews,
            'averageRating' => round($averageRating, 1),
        ]);
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
        //
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
