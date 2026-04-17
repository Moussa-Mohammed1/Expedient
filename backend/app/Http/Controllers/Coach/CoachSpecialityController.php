<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CoachSpeciality;
use App\Models\User;
use Illuminate\Http\Request;

class CoachSpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(CoachSpeciality $coachSpeciality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoachSpeciality $coachSpeciality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoachSpeciality $coachSpeciality)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoachSpeciality $coachSpeciality)
    {
        //
    }

    public function syncForCoach(User $user, array $specialityIds): void
    {
        if (!$user->coach) {
            return;
        }

        $existingPivot = $user->coach->specialities
            ->mapWithKeys(function ($speciality) {
                return [
                    $speciality->id => [
                        'level' => $speciality->pivot->level,
                        'experienceYears' => $speciality->pivot->experienceYears,
                    ],
                ];
            })
            ->all();

        $syncPayload = [];
        foreach ($specialityIds as $specialityId) {
            $syncPayload[$specialityId] = $existingPivot[$specialityId] ?? [
                'level' => 'beginner',
                'experienceYears' => 1,
            ];
        }

        $user->coach->specialities()->sync($syncPayload);
    }
}
