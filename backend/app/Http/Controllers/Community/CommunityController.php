<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\StoreCommunityRequest;
use App\Http\Requests\Community\UpdateCommunityRequest;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login.show');
        }

        if (blank($user->localisation)) {
            return redirect()
                ->route('profile.edit', $user->id)
                ->with('error', 'Please add your localisation in your profile to discover communities near you.');
        }

        $joinedCommunityIds = $user
            ? $user->memberships()
                ->whereNull('left_at')
                ->pluck('community_id')
                ->all()
            : [];

        $joinedCommunities = Community::query()
            ->whereIn('id', $joinedCommunityIds)
            ->withCount(['memberships as active_members_count' => fn ($query) => $query->whereNull('left_at')])
            ->latest()
            ->get();

        $searchQuery = $request->query('search');
        $communitiesQuery = Community::query()
            ->where('localisation', 'ilike',  $user->localisation)
            ->withCount(['memberships as active_members_count' => fn ($query) => $query->whereNull('left_at')])
            ->when($searchQuery, function ($query) use ($searchQuery) {
                return $query->where('title', 'ilike', "%{$searchQuery}%")
                    ->orWhere('description', 'ilike', "%{$searchQuery}%");
            })
            ->latest();

        return view('trainee.communities.index', [
            'communities' => $communitiesQuery->paginate(10),
            'joinedCommunities' => $joinedCommunities,
            'joinedCommunityIds' => $joinedCommunityIds,
            'searchQuery' => $searchQuery,
        ]);
    }

    public function create(): View
    {
        return view('communities.create');
    }

    public function store(StoreCommunityRequest $request): RedirectResponse
    {
        Community::create($request->validated());

        return redirect()->route('communities.index')->with('success', 'Community created successfully.');
    }

    public function show(Community $community): View
    {
        $community->load('users:id,name,avatar');

        $posts = app(PostController::class)->getCommunityPosts($community);

        $isMember = auth()->user()?->memberships()
            ->where('community_id', $community->id)
            ->whereNull('left_at')
            ->exists() ?? false;

        return view('trainee.communities.show', [
            'community' => $community,
            'posts' => $posts,
            'isMember' => $isMember,
        ]);
    }

    public function edit(Community $community): View
    {
        return view('communities.edit', compact('community'));
    }

    public function update(UpdateCommunityRequest $request, Community $community): RedirectResponse
    {
        $community->update($request->validated());

        return redirect()->route('communities.index')->with('success', 'Community updated successfully.');
    }

    public function destroy(Community $community): RedirectResponse
    {
        if ($community->memberships()->exists()) {
            return redirect()->route('communities.index')->with('error', 'Community cannot be deleted because it has active memberships.');
        }

        if ($community->posts()->exists()) {
            return redirect()->route('communities.index')->with('error', 'Community cannot be deleted because it has posts.');
        }

        $community->delete();

        return redirect()->route('communities.index')->with('success', 'Community deleted successfully.');
    }
}
