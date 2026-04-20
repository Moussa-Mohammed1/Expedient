<?php

namespace App\Http\Controllers\Coach;

use App\Http\Controllers\Controller;
use App\Models\CoachVerification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CoachVerificationController extends Controller
{
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $coach = $request->user()?->coach;

        if (!$coach) {
            return back()->with('error', 'Only coaches can request a verification badge.');
        }

        $latestVerification = $coach->latestVerification;

        if ($latestVerification && $latestVerification->status === 'pending') {
            return back()->with('error', 'You already have a pending verification request.');
        }

        if (!$request->filled('description') || !$request->hasFile('proof_document')) {
            return back()
                ->withInput()
                ->with('error', 'Verification request rejected. Please complete all required fields and attach a proof document.');
        }

        if (!$request->file('proof_document')->isValid()) {
            return back()
                ->withInput()
                ->with('error', 'Verification request rejected due to an invalid proof upload. Please try again.');
        }

        $validated = $request->validate([
            'proof_document' => ['required', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:5120'],
            'description' => ['required', 'string', 'max:1000'],
        ]);

        $storedPath = $request->file('proof_document')->store('coach-verifications', 'public');

        CoachVerification::create([
            'coach_id' => $coach->id,
            'status' => 'pending',
            'proof_document' => Storage::url($storedPath),
            'document_description' => $validated['description'],
        ]);

        return back()->with('success', 'Verification request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CoachVerification $coachVerification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoachVerification $coachVerification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoachVerification $coachVerification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoachVerification $coachVerification)
    {
        //
    }
}
