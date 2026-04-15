<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salle\StoreSalleRequest;
use App\Models\Salle;
use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalleController extends Controller
{
    public function index(Request $request): View
    {
        $coach = $request->user()?->coach()->with([
            'salles' => function ($query) {
                $query->with(['sport', 'galleries'])->latest();
            },
        ])->first();

        $salles = $coach?->salles ?? collect();

        return view('coach.salle.index', [
            'coach' => $coach,
            'salles' => $salles,
            'salleCount' => $salles->count(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $coach = $request->user()?->coach;

        return view('coach.salle.create', [
            'coach' => $coach,
            'sports' => Sport::query()->orderBy('title')->get(['id', 'title']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalleRequest $request): RedirectResponse
    {
        $coach = $request->user()?->coach;
        $validated = $request->validated();
        $validated['coach_id'] = $coach->id;

        Salle::create($validated);

        return redirect()->route('coach.salles')
            ->with('success', 'Salle created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('trainee.salle.show');
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
