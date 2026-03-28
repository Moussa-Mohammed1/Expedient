<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Salle\StoreSalleRequest;
use App\Http\Requests\Salle\UpdateSalleRequest;
use App\Models\Salle;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SalleController extends Controller
{
    public function index(): View
    {
        return view('salles.index', [
            'salles' => Salle::with(['coach', 'sport'])->latest()->get(),
        ]);
    }

    public function create(): View
    {
        return view('salles.create');
    }

    public function store(StoreSalleRequest $request): RedirectResponse
    {
        Salle::create($request->validated());

        return redirect()->route('salles.index')->with('success', 'Salle created successfully.');
    }

    public function show(Salle $salle): View
    {
        $salle->load(['coach', 'sport']);
        return view('salles.show', compact('salle'));
    }

    public function edit(Salle $salle): View
    {
        $salle->load(['coach', 'sport']);
        return view('salles.edit', compact('salle'));
    }

    public function update(UpdateSalleRequest $request, Salle $salle): RedirectResponse
    {
        $salle->update($request->validated());

        return redirect()->route('salles.index')->with('success', 'Salle updated successfully.');
    }

    public function destroy(Salle $salle): RedirectResponse
    {
        if ($salle->galleries()->exists()) {
            return redirect()->route('salles.index')->with('error', 'Salle cannot be deleted because it has galleries.');
        }

        $salle->delete();

        return redirect()->route('salles.index')->with('success', 'Salle deleted successfully.');
    }
}
