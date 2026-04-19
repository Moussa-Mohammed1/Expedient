<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SuspendedUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $role = (string) $request->query('role', 'all');
        $status = (string) $request->query('status', 'all');

        $roles = Role::query()
            ->orderBy('title')
            ->get(['id', 'title']);

        $usersQuery = User::query()
            ->with('role:id,title')
            ->latest();

        if ($search !== '') {
            $usersQuery->where(function ($query) use ($search) {
                if (is_numeric($search)) {
                    $query->orWhere('id', (int) $search);
                }

                $query->orWhere('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%")
                    ->orWhere('localisation', 'ilike', "%{$search}%");
            });
        }

        if ($role !== 'all') {
            $usersQuery->whereHas('role', function ($query) use ($role) {
                $query->where('title', 'ilike', $role);
            });
        }

        $activeSuspendedUserIds = SuspendedUser::query()
            ->active()
            ->select('user_id');

        if ($status === 'suspended') {
            $usersQuery->whereIn('id', $activeSuspendedUserIds);
        } elseif ($status === 'active') {
            $usersQuery->whereNotIn('id', $activeSuspendedUserIds);
        }

        $users = $usersQuery
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'q' => $search,
                'role' => $role,
                'status' => $status,
            ],
        ]);
    }
}
