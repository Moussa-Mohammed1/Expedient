<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sport\StoreSportRequest;
use App\Http\Requests\Sport\UpdateSportRequest;
use App\Models\sport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SportController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Sports fetched successfully.',
            'sports' => sport::latest()->get(),
        ]);
    }

    public function store(StoreSportRequest $request): JsonResponse
    {
        $sport = sport::create($request->validated());

        return response()->json([
            'message' => 'Sport created successfully.',
            'sport' => $sport,
        ], 201);
    }

    public function show(sport $sport): JsonResponse
    {
        return response()->json([
            'message' => 'Sport fetched successfully.',
            'sport' => $sport,
        ]);
    }

    public function update(UpdateSportRequest $request, sport $sport): JsonResponse
    {
        $sport->update($request->validated());

        return response()->json([
            'message' => 'Sport updated successfully.',
            'sport' => $sport,
        ]);
    }

    public function destroy(sport $sport): JsonResponse
    {
        if ($sport->salles()->exists()) {
            return response()->json([
                'message' => 'Sport cannot be deleted because it is assigned to salles.',
            ], 422);
        }

        $sport->delete();

        return response()->json([
            'message' => 'Sport deleted successfully.',
        ]);
    }
}