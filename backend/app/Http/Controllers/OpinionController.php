<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\Opinion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    public function store(Request $request, Coach $coach): RedirectResponse
    {
        $validated = $request->validate([
            'rate' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['nullable', 'string', 'max:1000'],
        ]);

        Opinion::create([
            'coach_id' => $coach->id,
            'author_id' => (int) auth()->id(),
            'rate' => (int) $validated['rate'],
            'content' => $validated['content'] ?? null,
            'isApproved' => true,
        ]);

        return redirect()
            ->route('coaches.show', $coach->id)
            ->with('success', 'Your review was posted successfully.');
    }

    public function show(Opinion $opinion)
    {
        //
    }

    public function update(Request $request, Opinion $opinion): RedirectResponse
    {
        $this->authorizeOwner($opinion);

        $validated = $request->validate([
            'rate' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['nullable', 'string', 'max:1000'],
        ]);

        $opinion->update([
            'rate' => (int) $validated['rate'],
            'content' => $validated['content'] ?? null,
        ]);

        return redirect()
            ->route('coaches.show', $opinion->coach_id)
            ->with('success', 'Your review was updated.')
            ->withFragment('review-' . $opinion->id);
    }

    public function destroy(Opinion $opinion): RedirectResponse
    {
        $this->authorizeOwner($opinion);

        $coachId = $opinion->coach_id;
        $opinion->delete();

        return redirect()
            ->route('coaches.show', $coachId)
            ->with('success', 'Your review was deleted.');
    }

    private function authorizeOwner(Opinion $opinion): void
    {
        if ((int) $opinion->author_id !== (int) auth()->id()) {
            abort(403);
        }
    }
}
