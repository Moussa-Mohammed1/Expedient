<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $validated = $request->validated();
        $remember = (bool) ($validated['remember'] ?? false);

        if (Auth::attempt([
            'email' => $validated['email'],
            'password' => $validated['password'],
        ], $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }
        return redirect()->back();
    }

    public function register(RegisterUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $roleId = $validated['role_id'] ?? DB::table('roles')->value('id');
        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->role_id = $roleId;
        $user->localisation = $validated['localisation'] ?? null;
        $user->avatar = $validated['avatar'] ?? null;
        $user->password = $validated['password'];
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/')->with('status', 'registered successfully.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Deconnexion reussie.');
    }
}
