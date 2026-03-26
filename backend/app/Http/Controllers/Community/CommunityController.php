<?php

namespace App\Http\Controllers;

use App\Http\Requests\Community\StoreCommunityRequest;
use App\Http\Requests\Community\UpdateCommunityRequest;
use App\Models\Community;
use Illuminate\Http\JsonResponse;

class CommunityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Communities fetched successfully.',
            'communities' => Community::latest()->get(),
        ]);
    }

    public function store(StoreCommunityRequest $request): JsonResponse
    {
        $community = Community::create($request->validated());

        return response()->json([
            'message' => 'Community created successfully.',
            'community' => $community,
        ], 201);
    }

    public function show(Community $community): JsonResponse
    {
        return response()->json([
            'message' => 'Community fetched successfully.',
            'community' => $community,
        ]);
    }

    public function update(UpdateCommunityRequest $request, Community $community): JsonResponse
    {
        $community->update($request->validated());

        return response()->json([
            'message' => 'Community updated successfully.',
            'community' => $community,
        ]);
    }

    public function destroy(Community $community): JsonResponse
    {
        if ($community->memberships()->exists()) {
            return response()->json([
                'message' => 'Community cannot be deleted because it has active memberships.',
            ], 422);
        }

        if ($community->posts()->exists()) {
            return response()->json([
                'message' => 'Community cannot be deleted because it has posts.',
            ], 422);
        }

        $community->delete();

        return response()->json([
            'message' => 'Community deleted successfully.',
        ]);
    }
}
