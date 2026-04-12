<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('profile.show', auth()->id());
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with(['role', 'coach.specialities'])->findOrFail($id);

        if (auth()->id() !== $user->id && auth()->user()?->role?->title !== 'admin') {
            abort(403);
        }

        return view('profile.show', ['profileUser' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['role', 'coach.specialities'])->findOrFail($id);

        if (auth()->id() !== $user->id && auth()->user()?->role?->title !== 'admin') {
            abort(403);
        }

        $allSpecialities = Speciality::query()->orderBy('title')->get(['id', 'title']);

        return view('profile.edit', [
            'profileUser' => $user,
            'allSpecialities' => $allSpecialities,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $id)
    {
        $user = User::with('coach.specialities')->findOrFail($id);

        $this->authorizeUser($user);

        $validated = $request->validated();

        $this->handleAvatarUpload($request, $user, $validated);
        $isChangingPassword = $this->isChangingPassword($validated);

        if ($isChangingPassword && !$this->isCurrentPasswordValid($request, $user)) {
            return back()
                ->withErrors(['current_password' => 'The current password is incorrect.'])
                ->withInput();
        }

        $specialityIds = $this->extractSpecialityIds($validated);
        $this->sanitizeValidatedData($validated);

        $user->update($validated);

        $this->syncCoachSpecialities($user, $specialityIds);

        return redirect()
            ->route('profile.show', $user->id)
            ->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    
    private function authorizeUser(User $user): void
    {
        if (auth()->id() !== $user->id && auth()->user()?->role?->title !== 'admin') {
            abort(403);
        }
    }

    private function handleAvatarUpload(UpdateProfileRequest $request, User $user, array &$validated): void
    {
        if (!$request->hasFile('avatar')) {
            return;
        }

        if ($user->avatar && Storage::disk('public')->exists('users/profiles/' . $user->avatar)) {
            Storage::disk('public')->delete('users/profiles/' . $user->avatar);
        }

        $avatarFile = $request->file('avatar');
        $avatarName = Str::uuid() . '.' . $avatarFile->getClientOriginalExtension();
        $avatarFile->storeAs('users/profiles', $avatarName, 'public');
        $validated['avatar'] = $avatarName;
    }

    private function isChangingPassword(array $validated): bool
    {
        return !empty($validated['password']);
    }

    private function isCurrentPasswordValid(UpdateProfileRequest $request, User $user): bool
    {
        return Hash::check((string) $request->input('current_password'), $user->password);
    }

    private function extractSpecialityIds(array &$validated): array
    {
        $specialityIds = array_values(array_unique(array_map('intval', $validated['speciality_ids'] ?? [])));
        unset($validated['speciality_ids']);

        return $specialityIds;
    }

    private function sanitizeValidatedData(array &$validated): void
    {
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        unset($validated['current_password']);
    }

    private function syncCoachSpecialities(User $user, array $specialityIds): void
    {
        if (!$user->coach) {
            return;
        }

        $existingPivot = $user->coach->specialities
            ->mapWithKeys(function ($speciality) {
                return [
                    $speciality->id => [
                        'level' => $speciality->pivot->level,
                        'experienceYears' => $speciality->pivot->experienceYears,
                    ],
                ];
            })
            ->all();

        $syncPayload = [];
        foreach ($specialityIds as $specialityId) {
            $syncPayload[$specialityId] = $existingPivot[$specialityId] ?? [
                'level' => 'beginner',
                'experienceYears' => 1,
            ];
        }

        $user->coach->specialities()->sync($syncPayload);
    }
}
