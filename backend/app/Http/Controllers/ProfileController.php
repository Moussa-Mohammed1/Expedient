<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Coach\CoachSpecialityController;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Models\Speciality;
use App\Models\User;
use App\Support\CloudinaryStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

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

        return view('profile.show', ['profileUser' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::with(['role', 'coach.specialities'])->findOrFail($id);
        $this->authorizeOwner($user);

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
        $this->authorizeOwner($user);

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

        app(CoachSpecialityController::class)->syncForCoach($user, $specialityIds);

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
    
    private function authorizeOwner(User $user)
    {
        if (auth()->id() !== $user->id) {
            return redirect('/home');
        }
    }

    private function handleAvatarUpload(UpdateProfileRequest $request, User $user, array &$validated): void
    {
        if (!$request->hasFile('avatar')) {
            return;
        }

        CloudinaryStorage::delete($user->avatar, 'users/profiles');

        $avatarFile = $request->file('avatar');
        $validated['avatar'] = CloudinaryStorage::upload(
            $avatarFile,
            'users/profiles',
        );
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
}
