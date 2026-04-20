<?php

namespace App\Http\Controllers\Salle;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Salle $salle): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'max:255'],
        ]);

        $salle->reviews()->create([
            'user_id' => $request->user()->id,
            'content' => $validated['content'],
        ]);

        return back()->with('success', 'Your review was posted successfully.');
    }
}
