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

<body class="bg-black text-gray-300 min-h-screen overflow-x-hidden">
    @include('layouts.adminSidebar')
    <x-notification-popup />
    <main class="flex-1 p-6 pt-24 lg:p-10 lg:ml-64">
        @php
            $statusFilter = $filters['status'] ?? 'pending';
            $causeFilter = $filters['cause'] ?? 'all';
            $queryFilter = $filters['q'] ?? '';
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Platform Reports</h2>
                <p class="text-zinc-400 text-sm mt-1">Review user-submitted complaints regarding behavior, facilities,
                    and content.</p>
            </div>
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 text-xs">
                <span class="bg-[#1c1c1c] border border-zinc-700 rounded-lg px-3 py-2 text-zinc-300">Total: {{ $counts['total'] ?? 0 }}</span>
                <span class="bg-[#FBBF24]/10 border border-[#FBBF24]/30 rounded-lg px-3 py-2 text-[#FBBF24]">Pending: {{ $counts['pending'] ?? 0 }}</span>
                <span class="bg-green-500/10 border border-green-500/30 rounded-lg px-3 py-2 text-green-400">Resolved: {{ $counts['resolved'] ?? 0 }}</span>
                <span class="bg-zinc-800 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-400">Cancelled: {{ $counts['cancelled'] ?? 0 }}</span>
            </div>
        </div>

        <form action="{{ route('admin.reports') }}" method="GET"
            class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-4 mb-6 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                </div>
                <input type="text" name="q" value="{{ $queryFilter }}"
                    placeholder="Search by ticket ID, reporter, cause, or text..."
                    class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl pl-11 pr-4 py-3 focus:outline-none focus:border-[#ff5520] transition-colors">
            </div>
            <div class="flex gap-3 flex-wrap">
                <select name="cause"
                    class="bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-[#ff5520] appearance-none cursor-pointer">
                    <option value="all" @selected($causeFilter === 'all')>All Causes</option>
                    @foreach ($causes ?? collect() as $cause)
                        <option value="{{ $cause }}" @selected($causeFilter === $cause)>
                            {{ ucfirst($cause) }}
                        </option>
                    @endforeach
                </select>
                <select name="status"
                    class="bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-[#ff5520] appearance-none cursor-pointer">
                    <option value="all" @selected($statusFilter === 'all')>All Statuses</option>
                    <option value="pending" @selected($statusFilter === 'pending')>Pending Review</option>
                    <option value="resolved" @selected($statusFilter === 'resolved')>Resolved</option>
                    <option value="cancelled" @selected($statusFilter === 'cancelled')>User Cancelled</option>
                </select>
                <button type="submit"
                    class="bg-[#ff5520] hover:bg-[#ff6a3d] text-black text-sm font-bold px-5 py-3 rounded-xl transition-colors">
                    Apply
                </button>
            </div>
        </form>

        <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto [scrollbar-width:thin]">
                <table class="w-full min-w-275 text-left border-collapse">
                    <thead>
                        <tr
                            class="bg-[#1c1c1c] border-b border-zinc-800 text-zinc-500 text-[10px] uppercase tracking-wider">
                            <th class="px-6 py-4 font-bold w-1/4">Ticket Info & Reporter</th>
                            <th class="px-6 py-4 font-bold w-5/12">Cause & Description</th>
                            <th class="px-6 py-4 font-bold w-1/6">Status</th>
                            <th class="px-6 py-4 font-bold w-1/6">Proof</th>
                            <th class="px-6 py-4 font-bold text-right w-1/12">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50 text-sm">
                        @forelse ($reports as $report)
                            @php
                                $avatarUrl = $report->reporter?->avatar
                                    ? asset('/storage/users/profiles/' . ltrim($report->reporter->avatar, '/'))
                                    : asset('assets/images/profile.jpeg');
                                $isCancelled = (bool) $report->isCancelled;
                                $status = $isCancelled ? 'cancelled' : ($report->status ?? 'pending');
                                $statusLabel = match ($status) {
                                    'resolved' => 'Resolved',
                                    'cancelled' => 'Cancelled',
                                    default => 'Needs Review',
                                };
                                $statusBadgeClass = match ($status) {
                                    'resolved' => 'bg-green-500/10 border border-green-500/30 text-green-400',
                                    'cancelled' => 'bg-zinc-800 border border-zinc-700 text-zinc-400',
                                    default => 'bg-[#FBBF24]/10 border border-[#FBBF24]/30 text-[#FBBF24]',
                                };
                                $proofName = $report->proof ? basename($report->proof) : null;
                                $proofUrl = $report->proof ? asset('storage/' . ltrim($report->proof, '/')) : null;
                            @endphp

                            <tr class="hover:bg-[#1c1c1c]/40 transition-colors group {{ $status === 'cancelled' ? 'opacity-75' : '' }}">
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span
                                            class="bg-zinc-800 border border-zinc-700 text-zinc-300 text-[10px] font-mono px-2 py-0.5 rounded">#REP-{{  $report->id }}</span>
                                        <span class="text-xs text-zinc-500">{{ optional($report->created_at)->format('M d, Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-3">
                                        <img src="{{ $avatarUrl }}" class="w-8 h-8 rounded-full border border-zinc-700 object-cover"
                                            alt="{{ $report->reporter?->name ?? 'Unknown user' }}">
                                        <div>
                                            <p class="text-white font-bold text-xs">{{ $report->reporter?->name ?? 'Unknown user' }}</p>
                                            @if ($report->reporter)
                                                <a href="{{ route('profile.show', $report->reporter_id) }}"
                                                    class="text-[10px] text-zinc-500 hover:text-white underline">View Profile</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <h4 class="text-white font-bold text-sm mb-1 capitalize">{{ $report->cause ?? 'Unethical'}}</h4>
                                    <p
                                        class="text-zinc-400 text-xs leading-relaxed line-clamp-4 bg-[#1c1c1c] p-3 rounded-lg border border-zinc-800/50 whitespace-pre-line">
                                        {{ $report->description }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="mb-3">
                                        <span
                                            class="{{ $statusBadgeClass }} text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide flex items-center w-fit gap-1.5">
                                            {{ $statusLabel }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    @if ($proofUrl)
                                        <a href="{{ $proofUrl }}" target="_blank"
                                            class="flex items-center gap-2 text-xs font-medium text-zinc-400 hover:text-white transition-colors bg-[#1c1c1c] border border-zinc-800 px-3 py-1.5 rounded-lg w-fit">
                                            <i class="fa-solid fa-file-arrow-down text-zinc-500"></i> {{ $proofName }}
                                        </a>
                                    @else
                                        <span class="text-xs font-medium text-zinc-600 italic">No proof attached</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="space-y-2 inline-flex flex-col items-end">
                                        @if (!$isCancelled && $status !== 'resolved')
                                            <form action="{{ route('admin.reports.update-status', $report) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="resolved">
                                                <button type="submit"
                                                    class=" bg-green-500/15 hover:bg-green-500/25 border border-green-500/40 text-green-300 px-1 py-1.5 truncate text-xs rounded-lg transition-colors">
                                                    Mark Resolved
                                                </button>
                                            </form>
                                        @endif

                                        @if (!$isCancelled && $status === 'resolved')
                                            <form action="{{ route('admin.reports.update-status', $report) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit"
                                                    class="text-xs bg-[#FBBF24]/10 hover:bg-[#FBBF24]/20 border border-[#FBBF24]/30 text-[#FBBF24] px-3 py-1.5 rounded-lg transition-colors">
                                                    Reopen Case
                                                </button>
                                            </form>
                                        @endif

                                        <span class="text-[11px] text-zinc-500">{{ optional($report->updated_at)->diffForHumans() }}</span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-zinc-500 text-sm" colspan="5">
                                    No reports found for the selected filters.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-between">
                <span class="text-xs text-zinc-500 font-medium">
                    Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} reports
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ $reports->previousPageUrl() ?? '#' }}"
                        class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-colors flex items-center justify-center {{ $reports->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                    <a href="{{ $reports->nextPageUrl() ?? '#' }}"
                        class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-colors flex items-center justify-center {{ $reports->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>

    </main>
</body>

</html>