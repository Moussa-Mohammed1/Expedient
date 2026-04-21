<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salle\StoreSalleRequest;
use App\Http\Requests\Salle\UpdateSalleRequest;
use App\Models\Equipment;
use App\Models\Horaire;
use App\Models\Gallery;
use App\Models\Salle;
use App\Models\Service;
use App\Models\Sport;
use App\Support\CloudinaryStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function edit(Salle $salle): View
    {

        $salle->load(['coach', 'sport', 'services', 'horaires', 'equipments']);

        return view('coach.salle.edit', [
            'salle' => $salle,
            'sports' => Sport::query()->orderBy('title')->get(['id', 'title']),
            'services' => Service::query()->orderBy('title')->get(['id', 'title']),
            'equipments' => Equipment::query()->orderBy('name')->get(['id', 'name', 'image']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalleRequest $request, Salle $salle): RedirectResponse
    {
        $coach = $request->user()?->coach;

        if (!$coach || $salle->coach_id !== $coach->id) {
            return redirect('/home');
        }

        $validated = $request->validated();

        DB::transaction(function () use ($request, $salle, $validated) {
            $salleData = [
                'name' => $validated['name'],
                'city' => $validated['city'],
                'tagline' => $validated['tagline'] ?? null,
                'description' => $validated['description'] ?? null,
                'sessionType' => $validated['sessionType'] ?? null,
                'sport_id' => $validated['sport_id'],
                'existenceYears' => $validated['existenceYears'] ?? null,
            ];

            if ($request->hasFile('logo')) {
                $salleData['logo'] = CloudinaryStorage::upload($request->file('logo'), 'salles/logos');
            }

            if ($request->hasFile('background')) {
                $salleData['background'] = CloudinaryStorage::upload($request->file('background'), 'salles/backgrounds');
            }

            $salle->update($salleData);

            $serviceIds = collect($validated['services'] ?? [])->map(fn($id) => (int) $id)->all();
            $salle->services()->sync($serviceIds);

            $equipmentPayload = collect($validated['equipment'] ?? [])
                ->mapWithKeys(fn($item) => [
                    (int) $item['equipment_id'] => [
                        'condition' => $item['condition'],
                    ],
                ])
                ->all();
            $salle->equipments()->sync($equipmentPayload);

            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $schedule = $validated['horaires'] ?? [];

            foreach ($days as $day) {
                $open = trim((string) ($schedule[$day]['open'] ?? ''));
                $close = trim((string) ($schedule[$day]['close'] ?? ''));

                Horaire::query()->updateOrCreate(
                    ['salle_id' => $salle->id, 'day' => $day],
                    [
                        'openHour' => $open !== '' ? $open . ':00' : null,
                        'closeHour' => $close !== '' ? $close . ':00' : null,
                    ]
                );
            }

            if ($request->hasFile('galleries')) {
                $existingCount = $salle->galleries()->count();
                $remainingSlots = max(0, 5 - $existingCount);

                collect($request->file('galleries'))
                    ->take($remainingSlots)
                    ->each(function ($file) use ($salle) {
                        $salle->galleries()->create([
                            'content' => CloudinaryStorage::upload($file, 'salles/galleries'),
                        ]);
                    });
            }
        });

        return redirect()
            ->route('coach.salles')
            ->with('success', 'Salle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Salle $salle): RedirectResponse
    {
        $coach = $request->user()?->coach;

        if (!$coach || $salle->coach_id !== $coach->id) {
            return redirect('/home');
        }

        DB::transaction(function () use ($salle) {
            $galleryPaths = $salle->galleries()->pluck('content')->filter()->all();

            CloudinaryStorage::delete($salle->logo);

            CloudinaryStorage::delete($salle->background);

            if ($galleryPaths !== []) {
                foreach ($galleryPaths as $galleryPath) {
                    CloudinaryStorage::delete((string) $galleryPath);
                }
            }

            $salle->galleries()->delete();
            $salle->horaires()->delete();
            $salle->services()->detach();
            $salle->equipments()->detach();
            $salle->delete();
        });

        return redirect()
            ->route('coach.salles')
            ->with('success', 'Salle deleted successfully.');
    }
}
