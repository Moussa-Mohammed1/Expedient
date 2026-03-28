<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\Coach;
use App\Models\Role;
use App\Models\Trainee;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if ($request->boolean('isCoach')) {
            $roleId = Role::where('title', 'coach')->value('id');
        } else {
            $roleId = $validated['role_id'] ?? Role::where('title', 'trainee')->value('id');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role_id' => $roleId,
            'localisation' => $validated['localisation'] ?? null,
            'avatar' => $validated['avatar'] ?? null,
            'password' => $validated['password'],
        ]);

        match ($user->role?->title) {
            'trainee' => Trainee::create(['user_id' => $user->id]),
            'coach' => Coach::create(['user_id' => $user->id]),
            'admin' => null,
            default => null,
        };

        Auth::login($user);

        return redirect('/');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
