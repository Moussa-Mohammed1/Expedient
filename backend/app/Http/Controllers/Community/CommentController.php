<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('trainee.communities.index');
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
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $isActiveMember = $request->user()
            ? $request->user()->memberships()
                ->where('community_id', $post->community_id)
                ->whereNull('left_at')
                ->exists()
            : false;

        if (!$isActiveMember) {
            return redirect('/home');
        }

        $post->comments()->create([
            'content' => $validated['content'],
            'user_id' => $request->user()->id,
        ]);

        return redirect()
            ->route('posts.show', ['post' => $post, 'community' => $post->community_id])
            ->with('success', 'Comment posted successfully.');
    }

    public function update(Request $request, Post $post, Comment $comment): RedirectResponse
    {
         if ($comment->post_id !== $post->id ||$request->user()?->id !== $comment->user_id) {
            return redirect('/home');
        }
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:1000'],
        ]);

        $comment->update([
            'content' => $validated['content'],
        ]);

        return redirect()
            ->route('posts.show', ['post' => $post, 'community' => $post->community_id])
            ->with('success', 'Comment updated successfully.');
    }

    public function destroy(Request $request, Post $post, Comment $comment): RedirectResponse
    {
        if ($comment->post_id !== $post->id ||$request->user()?->id === $comment->user_id) {
            return redirect('/home');
        }

        $comment->delete();

        return redirect()
            ->route('posts.show', ['post' => $post, 'community' => $post->community_id])
            ->with('success', 'Comment deleted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

}
