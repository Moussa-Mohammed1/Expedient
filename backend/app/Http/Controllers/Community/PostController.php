<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Models\Community;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function getCommunityPosts(Community $community): LengthAwarePaginator
    {
        return Post::query()
            ->where('community_id', $community->id)
            ->with([
                'user:id,name,avatar',
                'images:id,post_id,content',
                'comments.user:id,name,avatar',
                'likes:id,post_id,user_id',
            ])
            ->latest()
            ->paginate(10);
    }

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
    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        $selectedCommunityId = (int) $request->query('community');
        $community = Community::query()->findOrFail($selectedCommunityId);

        $isActiveMember = $user
            ? $user->memberships()
                ->where('community_id', $community->id)
                ->whereNull('left_at')
                ->exists()
            : false;

        if (!$isActiveMember) {
            return redirect('/home');
        }

        return view('trainee.communities.posts.create', [
            'community' => $community,
            'selectedCommunityId' => $selectedCommunityId,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $community = Community::query()->findOrFail((int) $validated['community_id']);

        $isActiveMember = $request->user()->memberships()
            ->where('community_id', $community->id)
            ->whereNull('left_at')
            ->exists();

        if (!$isActiveMember) {
            return redirect('/home');
        }

        $post = Post::query()->create([
            'content' => $validated['content'],
            'user_id' => $request->user()->id,
            'community_id' => $community->id,
        ]);

        foreach ($request->file('images', []) as $imageFile) {
            $post->images()->create([
                'content' => $imageFile->store('communities/posts', 'public'),
            ]);
        }

        return redirect()
            ->route('communities.show', $community)
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Post $post): View
    {
        $community = $request->query('community')
            ? Community::query()->findOrFail((int) $request->query('community'))
            : $post->community()->firstOrFail();

        $post->load([
            'user:id,name,avatar',
            'community:id,title',
            'images:id,post_id,content',
            'comments.user:id,name,avatar',
            'likes:id,post_id,user_id',
        ]);

        return view('trainee.communities.posts.show', [
            'post' => $post,
            'community' => $community,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
