<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\SuspendedUser;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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

        $pageUserIds = $users->getCollection()->pluck('id');
        $suspendedUserIds = SuspendedUser::query()
            ->active()
            ->whereIn('user_id', $pageUserIds)
            ->pluck('user_id')
            ->all();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'suspendedUserIds' => $suspendedUserIds,
            'filters' => [
                'q' => $search,
                'role' => $role,
                'status' => $status,
            ],
        ]);
    }

    public function assignRole(User $user): RedirectResponse
    {
        $adminId = Role::where('title', 'admin')->value('id');

        if (!$adminId) {
            return redirect()->back()->with('error', 'Admin role not found.');
        }

        $user->update(['role_id' => $adminId]);

        return redirect()->back()->with('success', "Admin role assigned to {$user->name} successfully.");
    }

    public function unassignRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'new_role' => 'required|string|in:trainee,coach',
        ]);
        if ($user->is_super_admin) {
            return redirect()->back()->with('error', 'Admin role not revokable from super admins.');
        }
        $roleTitle = $validated['new_role'] === 'coach' ? 'coach' : 'trainee';
        $newRoleId = Role::where('title', $roleTitle)->value('id');

        if (!$newRoleId) {
            return redirect()->back()->with('error', 'Selected role not found.');
        }

        $user->update(['role_id' => $newRoleId]);

        return redirect()->back()->with('success', "Admin role revoked from {$user->name} successfully.");
    }

    public function suspend(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:255',
            'expires_at' => 'required|date|after:now',
        ]);
        if ($user->is_super_admin) {
            return redirect()->back()->with('error', 'You can not suspend a super admin');
        }
        SuspendedUser::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        SuspendedUser::create([
            'user_id' => $user->id,
            'suspended_by' => auth()->id(),
            'reason' => $validated['reason'],
            'status' => 'active',
            'expires_at' => $validated['expires_at'],
        ]);

        return redirect()->back()->with('success', "{$user->name} has been suspended successfully.");
    }
}
