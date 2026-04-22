<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SuspendedUser;
use App\Models\User;

class SuspendedUserController extends Controller
{
    public function show(User $user)
    {
        $suspension = SuspendedUser::query()
            ->active()
            ->where('user_id', $user->id)
            ->latest('expires_at')
            ->first();

        return view('suspended', compact('user', 'suspension'));
    }
}
