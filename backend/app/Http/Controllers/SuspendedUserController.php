<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SuspendedUserController extends Controller
{
    public function show(User $user)
    {
        return view('suspended', compact('user'));
    }
}
