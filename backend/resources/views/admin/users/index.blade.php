<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-[#000] text-gray-300 font-sans antialiased min-h-screen">
    @include('layouts.adminSidebar')
    <x-notification-popup />
    <main class="flex-1 p-6 lg:p-10 lg:ml-64">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">User Management</h2>
                <p class="text-zinc-400 text-sm mt-1">Search, filter, and manage all registered accounts on the
                    platform.</p>
            </div>
        </div>

        <form action="{{ url('/admin/users') }}" method="GET"
            class="bg-[#111111] border border-zinc-800/80 rounded-lg p-4 mb-6 flex flex-col md:flex-row gap-4">

            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                </div>
                <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
                    placeholder="Search by name, email, or ID..."
                    class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-lg pl-11 pr-4 py-3 focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] transition-colors">
            </div>

            <div class="flex gap-4 flex-wrap">
                <select name="role"
                    class="bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-lg px-4 py-3 focus:outline-none focus:border-[#FBBF24] appearance-none cursor-pointer min-w-35">
                    <option value="all" @selected(($filters['role'] ?? 'all') === 'all')>All Roles</option>
                    @foreach ($roles ?? collect() as $role)
                        <option value="{{ strtolower($role->title) }}" @selected(($filters['role'] ?? '') === strtolower($role->title))>
                            {{ ucfirst($role->title) }}
                        </option>
                    @endforeach
                </select>

                <select name="status"
                    class="bg-[#1c1c1c] border border-zinc-700 appearance-none cursor-pointer text-white text-sm rounded-lg px-4 py-3 focus:outline-none focus:border-[#FBBF24] min-w-35">
                    <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>All Statuses</option>
                    <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active</option>
                    <option value="suspended" @selected(($filters['status'] ?? '') === 'suspended')>Suspended</option>
                </select>

                <button type="submit"
                    class="bg-[#d1fa48] hover:bg-[#b4d83d] text-black text-sm font-bold px-4 py-3 rounded-lg transition-colors">
                    Apply
                </button>
            </div>
        </form>

        <div class="bg-[#111111] border border-zinc-800/80 rounded-lg overflow-hidden [scrollbar-with:none]">
            <div class="w-full max-h-[70vh] overflow-auto [scrollbar-width:none]">
                <table class="w-full text-left ">
                    <thead class="sticky top-0 z-20">
                        <tr class="bg-[#1c1c1c] border-b border-zinc-800 text-zinc-500 text-[10px] uppercase ">
                            <th class="px-6 py-4 font-bold ">User Details</th>
                            <th class="px-6 py-4 font-bold">Role</th>
                            <th class="px-6 py-4 font-bold">ID</th>
                            <th class="px-6 py-4 font-bold">Location</th>
                            <th class="px-6 py-4 font-bold">Status</th>
                            <th class="px-6 py-4 font-bold">Joined Date</th>
                            <th class="px-6 py-4 font-bold text-right ">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50 text-sm h-fit">
                        @forelse ($users as $user)
                            @php
                                $avatarUrl = $user->avatar
                                    ? asset('/storage/users/profiles/' . ltrim($user->avatar, '/'))
                                    : asset('assets/images/profile.jpeg');
                                $roleTitle = strtolower($user->role?->title ?? 'user');
                                $isSuspended = in_array($user->id, $suspendedUserIds ?? [], true);
                                $roleBadgeClass = match ($roleTitle) {
                                    'admin' => 'bg-[#d1fa48]/10 text-[#d1fa48] border border-[#d1fa48]/30',
                                    'coach' => 'bg-[#FBBF24]/10 text-[#FBBF24] border border-[#FBBF24]/30',
                                    'trainee' => 'bg-zinc-800 text-zinc-300',
                                };
                            @endphp
                            <tr class="hover:bg-[#1c1c1c]/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $avatarUrl }}"
                                            class="w-10 h-10 rounded-full border border-zinc-700 object-cover"
                                            alt="{{ $user->name }}">
                                        <div>
                                            <p class="text-white font-bold text-sm">{{ $user->name }}</p>
                                            <p class="text-zinc-500 text-xs">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="{{ $roleBadgeClass }} text-[10px] font-bold px-2.5 py-1 rounded-lg uppercase tracking-wide">{{ ucfirst($roleTitle) }}</span>
                                </td>
                                <td class="px-6 py-4 text-zinc-400">#{{ $user->id }}</td>
                                <td class="px-6 py-4 text-zinc-400">{{ $user->localisation ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <div
                                        class="flex items-center gap-1.5 text-xs font-bold {{ $isSuspended ? 'text-[#FBBF24]' : 'text-green-400' }}">
                                        {{ $isSuspended ? 'Suspended' : 'Active' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-zinc-500 text-xs font-medium">
                                    {{ optional($user->created_at)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="relative inline-block text-left group" tabindex="0">
                                        <button
                                            class="text-zinc-500 hover:text-white p-2 rounded-lg outline-none cursor-pointer transition-colors focus:bg-zinc-800">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <div
                                            class="absolute right-0 mt-2 w-40 bg-[#222222] border border-zinc-700 rounded-lg  invisible opacity-0 group-focus-within:visible group-focus-within:opacity-100 transition-all z-50 overflow-hidden text-left origin-top-right">
                                            <ul class="py-1 text-xs font-medium">
                                                <li><a href="{{ route('profile.show', $user->id) }}"
                                                        class="block px-4 py-2.5 text-zinc-300 hover:bg-zinc-700 hover:text-white transition-colors">View
                                                        Profile</a></li>


                                                @if ($roleTitle === 'admin')
                                                    <li>
                                                        <button id="unassign-admin"
                                                            class="block px-4 py-2.5 text-[#ff5520] hover:bg-zinc-700 w-full text-start hover:text-[#ff7a00] transition-colors\">Revoke
                                                            <span class="font-bold">Admin</span> Access</button>
                                                    </li>
                                                @else
                                                    <li>
                                                        <button id="assign-admin"
                                                            class="block px-4 py-2.5 text-zinc-300 hover:bg-zinc-700 w-full text-start hover:text-white transition-colors">Assign
                                                            <span class="text-red-500">Admin</span>
                                                            Role</button>
                                                    </li>
                                                @endif
                                                <li>
                                                    <button id="suspend-user"
                                                        class="block px-4 py-2.5 text-red-300 hover:bg-zinc-700 w-full text-start hover:text-red-500 transition-colors">Suspend</button>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-zinc-500">No users found for your current
                                    filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>



        <div class="p-4 border-t border-zinc-800/80 bg-[#1c1c1c] rounded-b-lg flex items-center justify-between">
            <span class="text-xs text-zinc-500 font-medium">
                @if ($users->total() > 0)
                    Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                @else
                    Showing 0 users
                @endif
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ $users->previousPageUrl() ?: '#' }}"
                    class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-colors flex items-center justify-center {{ $users->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
                <a href="{{ $users->nextPageUrl() ?: '#' }}"
                    class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-colors flex items-center justify-center {{ $users->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">
                    <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            </div>
        </div>
    </main>

</body>

</html>

@include('admin.users.partials.assignRole-modal')
@include('admin.users.partials.unassignRole-modal')
@include('admin.users.partials.suspend-modal')