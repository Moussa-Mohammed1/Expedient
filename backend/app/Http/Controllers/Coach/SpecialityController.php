<?php

namespace App\Http\Controllers\Coach;

use App\Http\Requests\Speciality\StoreSpecialityRequest;
use App\Models\Speciality;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpecialityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $coach = $user?->coach;
        if (!$coach) {
            return redirect('/home');
        }

        $coach->load([
            'specialities' => function ($query) {
                $query->orderBy('title');
            }
        ]);

        $assignedIds = $coach->specialities->pluck('id');
        $allSpecialities = Speciality::query()
            ->whereNotIn('id', $assignedIds)
            ->orderBy('title')
            ->get();

        return view('coach.specialities.index', [
            'coach' => $coach,
            'allSpecialities' => $allSpecialities,
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
    public function store(Request $request): RedirectResponse
    {
        $coach = $request->user()?->coach;

        $validated = $request->validate(
            [
                'speciality_id' => 'required|exists:specialities,id',
                'level' => 'required|string',
                'experienceYears' => 'integer|required',
            ]
        );
        $speciality = Speciality::query()->findOrFail((int) $validated['speciality_id']);

        $alreadyAttached = $coach->specialities()
            ->where('speciality_id', $speciality->id)
            ->exists();

        if ($alreadyAttached) {
            return back()->with('error', 'This speciality is already assigned to your profile.');
        }

        $coach->specialities()->attach($speciality->id, [
            'level' => $validated['level'],
            'experienceYears' => $validated['experienceYears'],
        ]);

        return back()->with('success', 'Speciality added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Speciality $speciality)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Speciality $speciality)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Speciality $speciality): RedirectResponse
    {
        $coach = $request->user()?->coach;

        $request->validate([
            'level' => ['required', 'in:beginner,intermediate,advanced'],
            'experienceYears' => ['required', 'integer', 'min:0', 'max:80'],
        ]);

        $isAttached = $coach->specialities()
            ->where('speciality_id', $speciality->id)
            ->exists();
        if (!$isAttached) {
            return redirect('/home');
        }

        $coach->specialities()->updateExistingPivot($speciality->id, [
            'level' => $request->input('level'),
            'experienceYears' => (int) $request->input('experienceYears'),
        ]);

        return back()->with('success', 'Speciality updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Speciality $speciality): RedirectResponse
    {
        $coach = $request->user()?->coach;
        if (!$coach) {
            return redirect('/home');
        }

        $coach->specialities()->detach($speciality->id);

        return back()->with('success', 'Speciality removed successfully.');
    }
}
