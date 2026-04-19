<?php

namespace App\Http\Controllers\Community;

use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Membership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function store(Request $request, Community $community): RedirectResponse
    {
        $membership = Membership::query()
            ->where('user_id', $request->user()->id)
            ->where('community_id', $community->id)
            ->latest()
            ->first();

        if ($membership && $membership->left_at === null) {
            return back()->with('success', 'You are already a member of this community.');
        }

        if ($membership) {
            $membership->update([
                'left_at' => null,
                'role' => $membership->role ?? 'member',
            ]);
        } else {
            Membership::create([
                'user_id' => $request->user()->id,
                'community_id' => $community->id,
                'role' => 'member',
            ]);
        }

        return back()->with('success', 'Joined community successfully.');
    }

    public function destroy(Request $request, Community $community): RedirectResponse
    {
        $membership = Membership::query()
            ->where('user_id', $request->user()->id)
            ->where('community_id', $community->id)
            ->whereNull('left_at')
            ->latest()
            ->first();

        if (!$membership) {
            return back()->with('error', 'You are not currently a member of this community.');
        }

        $membership->update([
            'left_at' => now(),
        ]);

        return back()->with('success', 'Left community successfully.');
    }
}