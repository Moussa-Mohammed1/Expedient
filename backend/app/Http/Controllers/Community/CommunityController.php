<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Community\StoreCommunityRequest;
use App\Http\Requests\Community\UpdateCommunityRequest;
use App\Models\Community;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommunityController extends Controller
{
    public function index(): View
    {
        return view('trainee.communities.index', [
            'communities' => Community::latest()->paginate(10)
        ]);
    }

    public function create(): View
    {
        return view('communities.create');
    }

    public function store(StoreCommunityRequest $request): RedirectResponse
    {
        Community::create($request->validated());
        return redirect()->route('trainee.communities.index')->with('success', 'Community created successfully.');
    }

    public function show(Community $community): View
    {
        $community->load('posts', 'posts.comments', 'posts.likes', 'members');
        return view('trainee.communities.show', compact('community'));
    }

    public function edit(Community $community): View
    {
        return view('communities.edit', compact('community'));
    }

    public function update(UpdateCommunityRequest $request, Community $community): RedirectResponse
    {
        $community->update($request->validated());
        return redirect()->route('trainee.communities.index')->with('success', 'Community updated successfully.');
    }

    public function destroy(Community $community): RedirectResponse
    {
        if ($community->memberships()->exists()) {
            return redirect()->route('trainee.communities.index')->with('error', 'Community cannot be deleted because it has active memberships.');
        }

        if ($community->posts()->exists()) {
            return redirect()->route('trainee.communities.index')->with('error', 'Community cannot be deleted because it has posts.');
        }

        $community->delete();
        return redirect()->route('trainee.communities.index')->with('success', 'Community deleted successfully.');
    }
}
