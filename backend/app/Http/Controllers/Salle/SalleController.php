<?php

namespace App\Http\Controllers;

use App\Http\Requests\Salle\StoreSalleRequest;
use App\Http\Requests\Salle\UpdateSalleRequest;
use App\Models\Salle;
use Illuminate\Http\JsonResponse;

class SalleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Salles fetched successfully.',
            'salles' => Salle::with(['coach', 'sport'])->latest()->get(),
        ]);
    }

    public function store(StoreSalleRequest $request): JsonResponse
    {
        $salle = Salle::create($request->validated());

        return response()->json([
            'message' => 'Salle created successfully.',
            'salle' => $salle->load(['coach', 'sport']),
        ], 201);
    }

    public function show(Salle $salle): JsonResponse
    {
        return response()->json([
            'message' => 'Salle fetched successfully.',
            'salle' => $salle->load(['coach', 'sport']),
        ]);
    }

    public function update(UpdateSalleRequest $request, Salle $salle): JsonResponse
    {
        $salle->update($request->validated());

        return response()->json([
            'message' => 'Salle updated successfully.',
            'salle' => $salle->load(['coach', 'sport']),
        ]);
    }

    public function destroy(Salle $salle): JsonResponse
    {
        if ($salle->galleries()->exists()) {
            return response()->json([
                'message' => 'Salle cannot be deleted because it has galleries.',
            ], 422);
        }

        $salle->delete();

        return response()->json([
            'message' => 'Salle deleted successfully.',
        ]);
    }
}
