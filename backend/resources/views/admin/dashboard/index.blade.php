<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body
    class="bg-black text-gray-300 min-h-screen overflow-x-hidden">
    @include('layouts.adminSidebar') 
    <div class="fixed top-5 right-15  w-3 h-3 z-50">
        <x-profile-icon/>
    </div>
    <main class="flex-1 p-6 pt-24 lg:p-10 lg:ml-64">

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Expedient Overview</h2>
                <p class="text-zinc-400 text-sm mt-1">Live platform metrics for {{ now()->format('l, F j, Y') }}.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">

            <div class="bg-[#111111] border border-zinc-800/80 rounded-lg p-5 relative overflow-hidden">
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Total Users</p>
                <h3 class="text-3xl font-black text-white mb-2">{{ number_format($stats['totalUsers'] ?? 0) }}</h3>
                
            </div>

            <div class="bg-[#111111] border border-zinc-800/80 rounded-lg p-5 relative overflow-hidden">
               
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Total Posts</p>
                <h3 class="text-3xl font-black text-white mb-2">{{ number_format($stats['totalPosts'] ?? 0) }}</h3>
                
            </div>

            <div class="bg-[#111111] border border-zinc-800/80 rounded-lg p-5 relative overflow-hidden">
                
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Active Communities</p>
                <h3 class="text-3xl font-black text-[#FBBF24] mb-2">{{ number_format($stats['activeCommunities'] ?? 0) }}</h3>
                
            </div>

            <div class="bg-[#111111] border border-zinc-800/80 rounded-lg p-5 relative overflow-hidden">
                
                <p class="text-xs font-bold text-zinc-500 uppercase tracking-wider mb-2">Covered Cities</p>
                <h3 class="text-3xl font-black text-white mb-2">{{ number_format($stats['coveredCities'] ?? 0) }}</h3>
                
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-[#111111] border border-zinc-800/80 rounded-lg overflow-hidden">
                    <div class="p-5 border-b border-zinc-800/80 flex items-center justify-between bg-[#111111]">
                        <h3 class="text-white font-bold text-lg">Recent Registrations</h3>
                        <a href="#" class="text-xs font-bold text-zinc-400 hover:text-white transition-colors">View
                            All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#1c1c1c] text-zinc-500 text-[10px] uppercase tracking-wider">
                                    <th class="px-5 py-3 font-bold">User</th>
                                    <th class="px-5 py-3 font-bold">Role</th>
                                    <th class="px-5 py-3 font-bold">Location</th>
                                    <th class="px-5 py-3 font-bold">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/50 text-sm">
                                @forelse ($recentUsers ?? collect() as $user)
                                    @php
                                        $avatarUrl = $user->avatar
                                            ? asset('/storage/users/profiles/' . ltrim($user->avatar, '/'))
                                            : asset('assets/images/profile.jpeg');
                                    @endphp
                                    <tr class="hover:bg-[#1c1c1c] transition-colors">
                                        <td class="px-5 py-3 flex items-center gap-3">
                                            <img src="{{ $avatarUrl }}" class="w-8 h-8 rounded-full border border-zinc-700 object-cover"
                                                alt="{{ $user->name }}">
                                            <div class="min-w-0">
                                                <p class="text-white font-medium truncate">{{ $user->name }}</p>
                                                <p class="text-zinc-500 text-xs truncate">{{ $user->email }}</p>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3">
                                            <span
                                                class="bg-zinc-800 text-zinc-300 text-[10px] font-bold px-2 py-1 rounded uppercase">{{ $user->role?->title ?? 'User' }}</span>
                                        </td>
                                        <td class="px-5 py-3 text-zinc-400">{{ $user->localisation ?: '-' }}</td>
                                        <td class="px-5 py-3 text-zinc-500 text-xs">{{ optional($user->created_at)->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="px-5 py-4 text-zinc-500 text-sm" colspan="4">No recent registrations found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div 
                class="bg-[#111111] border border-[#ff5520]/30 rounded-lg overflow-hidden relative">
                 

                    <div class="p-5 border-b border-zinc-800/80 flex items-center justify-between">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            Needs Attention (Reports)
                        </h3>
                        <span class="bg-yellow-500 text-black text-xs font-bold px-3 py-1 rounded-full">{{ number_format($pendingReportsCount ?? 0) }} Pending</span>
                    </div>

                    <div class="p-5 space-y-4">
                        @forelse ($recentReports ?? collect() as $report)
                            @php
                                $isPending = !$report->isCancelled;
                                $cause = ucfirst($report->cause);
                            @endphp
                            <div
                                class="bg-[#1c1c1c] border border-zinc-700/50 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="{{ $isPending ? 'text-[#ff5520]' : 'text-zinc-400' }} text-[10px] font-bold uppercase tracking-wider">{{ $cause }}</span>
                                        <span class="text-zinc-600 text-xs">• #REP-{{ $report->id }}</span>
                                    </div>
                                    <h4 class="text-sm font-bold text-white mb-1 line-clamp-1">{{ $report->description }}</h4>
                                    <p class="text-xs text-zinc-400">Reporter: {{ $report->reporter?->name ?? 'Unknown' }} (User ID: {{ $report->reporter_id }})</p>
                                </div>
                                <a href="{{ route('reports.index') }}"
                                    class="{{ $isPending ? 'bg-yellow-500 text-black' : 'bg-[#222222] text-white border border-zinc-700 hover:bg-zinc-800' }}  text-xs font-bold px-4 py-2 rounded-lg transition-colors shrink-0">
                                    Review Case
                                </a>
                            </div>
                        @empty
                            <div class="bg-[#1c1c1c] border border-zinc-700/50 rounded-xl p-4">
                                <p class="text-sm text-zinc-400">No reports found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-[#111111] border border-zinc-800/80 rounded-lg h-full flex flex-col">
                    <div class="p-5 border-b border-zinc-800/80 flex items-center justify-between">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                             Recent Posts
                        </h3>
                    </div>

                    <div class="p-5 space-y-5 flex-1 overflow-y-auto">
                        @forelse ($recentPosts ?? collect() as $post)
                            @php
                                $postAvatar = $post->user?->avatar
                                    ? asset('/storage/users/profiles/' . ltrim($post->user->avatar, '/'))
                                    : asset('assets/images/profile.jpeg');
                            @endphp
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <img src="{{ $postAvatar }}" class="w-6 h-6 rounded-full border border-zinc-700 object-cover"
                                            alt="{{ $post->user?->name ?? 'User' }}">
                                        <span class="text-xs font-bold text-white truncate">{{ $post->user?->name ?? 'Unknown User' }}</span>
                                    </div>
                                    <span class="text-[10px] text-zinc-500 shrink-0">{{ optional($post->created_at)->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-zinc-300 line-clamp-2 mb-2">
                                    {{ $post->content }}
                                </p>
                                <div
                                    class="text-[10px] font-medium text-zinc-500 bg-[#1c1c1c] border border-zinc-800 truncate inline-block px-2 py-1 rounded">
                                    Community:  {{ $post->community?->title ?? 'No community' }}
                                </div>
                            </div>

                            @if (!$loop->last)
                                <div class="h-px w-full bg-zinc-800/50"></div>
                            @endif
                        @empty
                            <div class="text-center text-zinc-500 text-sm py-4">No recent posts found.</div>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

    </main>

</body>

</html>