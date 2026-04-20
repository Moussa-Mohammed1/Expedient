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
            $queryFilter = $filters['q'] ?? '';
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white tracking-tight">Coach Verifications</h2>
                <p class="text-zinc-400 text-sm mt-1">Review identity and certification documents submitted by coaches.</p>
            </div>
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 text-xs">
                <span class="bg-[#1c1c1c] border border-zinc-700 rounded-lg px-3 py-2 text-zinc-300">Total: {{ $counts['total'] ?? 0 }}</span>
                <span class="bg-[#FBBF24]/10 border border-[#FBBF24]/30 rounded-lg px-3 py-2 text-[#FBBF24]">Pending: {{ $counts['pending'] ?? 0 }}</span>
                <span class="bg-green-500/10 border border-green-500/30 rounded-lg px-3 py-2 text-green-400">Approved: {{ $counts['approved'] ?? 0 }}</span>
                <span class="bg-red-500/10 border border-red-500/30 rounded-lg px-3 py-2 text-red-300">Rejected: {{ $counts['rejected'] ?? 0 }}</span>
            </div>
        </div>

        <form action="{{ route('admin.verifications') }}" method="GET"
            class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-4 mb-6 flex flex-col md:flex-row gap-4">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                </div>
                <input type="text" name="q" value="{{ $queryFilter }}" placeholder="Search by request ID, coach ID, name, or reason..."
                    class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl pl-11 pr-4 py-3 focus:outline-none focus:border-[#FBBF24] transition-colors">
            </div>
            <div class="flex gap-3 flex-wrap">
                <select name="status"
                    class="bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-[#FBBF24] appearance-none cursor-pointer">
                    <option value="pending" @selected($statusFilter === 'pending')>Status: Pending</option>
                    <option value="approved" @selected($statusFilter === 'approved')>Status: Approved</option>
                    <option value="rejected" @selected($statusFilter === 'rejected')>Status: Rejected</option>
                    <option value="all" @selected($statusFilter === 'all')>All Applications</option>
                </select>
                <button type="submit"
                    class="bg-[#FBBF24] hover:bg-[#f6cd4f] text-black text-sm font-bold px-5 py-3 rounded-xl transition-colors">
                    Apply
                </button>
            </div>
        </form>

        <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl overflow-hidden shadow-xl">
            <div class="overflow-x-auto [scrollbar-width:thin]">
                <table class="w-full min-w-300 text-left border-collapse">
                    <thead>
                        <tr class="bg-[#1c1c1c] border-b border-zinc-800 text-zinc-500 text-[10px] uppercase tracking-wider">
                            <th class="px-6 py-4 font-bold w-1/5">Applicant</th>
                            <th class="px-6 py-4 font-bold w-1/6">Submitted Document</th>
                            <th class="px-6 py-4 font-bold w-1/4">Description</th>
                            <th class="px-6 py-4 font-bold w-1/6">Timeline</th>
                            <th class="px-6 py-4 font-bold w-1/6">Status & Feedback</th>
                            <th class="px-6 py-4 font-bold text-right w-1/6">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50 text-sm">
                        @forelse ($verifications as $verification)
                            @php
                                $coach = $verification->coach;
                                $coachUser = $coach?->user;
                                $avatarUrl = $coachUser?->avatar
                                    ? asset('/storage/users/profiles/' . ltrim($coachUser->avatar, '/'))
                                    : asset('assets/images/profile.jpeg');

                                $proofPath = (string) $verification->proof_document;
                                $normalizedProofPath = ltrim($proofPath, '/');
                                $proofUrl = null;
                                if ($proofPath !== '') {
                                    if (filter_var($proofPath, FILTER_VALIDATE_URL)) {
                                        $proofUrl = $proofPath;
                                    } elseif (str_starts_with($normalizedProofPath, 'storage/') || str_starts_with($normalizedProofPath, 'assets/')) {
                                        $proofUrl = asset($normalizedProofPath);
                                    } else {
                                        $proofUrl = asset('storage/' . $normalizedProofPath);
                                    }
                                }

                                $proofName = basename(parse_url($proofPath, PHP_URL_PATH) ?: $proofPath);

                                $statusBadgeClass = match ($verification->status) {
                                    'approved' => 'bg-green-500/10 border border-green-500/30 text-green-400',
                                    'rejected' => 'bg-red-500/10 border border-red-500/30 text-red-300',
                                    default => 'bg-[#FBBF24]/10 border border-[#FBBF24]/30 text-[#FBBF24]',
                                };
                            @endphp

                            <tr class="hover:bg-[#1c1c1c]/40 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $avatarUrl }}" class="w-10 h-10 rounded-full border border-zinc-700 object-cover"
                                            alt="{{ $coachUser?->name ?? 'Unknown coach' }}">
                                        <div>
                                            <p class="text-white font-bold text-sm">{{ $coachUser?->name ?? 'Unknown coach' }}</p>
                                            <p class="text-zinc-500 text-xs font-mono mt-0.5">Coach ID: {{ $verification->coach_id }}</p>
                                            <p class="text-zinc-600 text-[11px] mt-0.5">Request #VER-{{ $verification->id }}</p>
                                            @if ($coachUser)
                                                <a href="{{ route('profile.show', $coachUser->id) }}"
                                                    class="text-[10px] text-zinc-500 hover:text-white underline">View Profile</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-top">
                                    @if ($proofUrl)
                                        <a href="{{ $proofUrl }}" target="_blank"
                                            class="inline-flex items-center gap-2 bg-[#1c1c1c] border border-zinc-700 hover:border-[#FBBF24] hover:text-[#FBBF24] text-zinc-300 text-xs font-medium px-3 py-2 rounded-lg transition-colors w-fit">
                                            <i class="fa-solid fa-file-arrow-down text-zinc-500"></i>
                                            {{ $proofName }}
                                        </a>
                                    @else
                                        <span class="text-xs text-zinc-600 italic">No document</span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 align-top">
                                    <p class="text-zinc-300 text-xs leading-relaxed bg-[#1c1c1c] p-3 rounded-lg border border-zinc-800/50 whitespace-pre-line">
                                        {{ $verification->document_description }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 align-top">
                                    <div class="flex flex-col gap-1 text-xs">
                                        <span class="text-zinc-300"><span class="text-zinc-500">Requested:</span> {{ optional($verification->requested_at)->format('M d, Y') ?? '--' }}</span>
                                        <span class="text-zinc-500"><span class="text-zinc-600">Reviewed:</span> {{ optional($verification->reviewed_at)->format('M d, Y') ?? '--' }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-top">
                                    <div class="space-y-2">
                                        <span class="{{ $statusBadgeClass }} text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide inline-flex items-center w-fit gap-1.5">
                                            {{ $verification->status }}
                                        </span>
                                        @if ($verification->status === 'rejected' && $verification->rejection_cause)
                                            <p class="text-xs text-zinc-400 bg-[#1c1c1c] p-2 rounded border border-zinc-800/50 whitespace-pre-line">
                                                <span class="font-bold text-zinc-300">Reason:</span> {{ $verification->rejection_cause }}
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-top text-right">
                                    <div class="space-y-2 inline-flex flex-col items-end">
                                        @if ($verification->status !== 'approved')
                                            <form action="{{ route('admin.verifications.update-status', $verification) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit"
                                                    class="text-xs bg-green-500/15 hover:bg-green-500/25 border border-green-500/40 text-green-300 px-3 py-1.5 rounded-lg transition-colors">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif

                                        @if ($verification->status !== 'rejected')
                                            <button type="button"
                                                class="open-reject-verification text-xs bg-[#ff5520]/10 hover:bg-[#ff5520]/20 border border-[#ff5520]/30 text-[#ff5520] px-3 py-1.5 rounded-lg transition-colors"
                                                data-action="{{ route('admin.verifications.update-status', $verification) }}"
                                                data-coach-name="{{ $coachUser?->name ?? 'Unknown coach' }}"
                                                data-coach-id="{{ $verification->coach_id }}"
                                                data-request-id="{{ $verification->id }}"
                                                data-avatar="{{ $avatarUrl }}">
                                                Reject
                                            </button>
                                        @endif

                                        @if ($verification->status !== 'pending')
                                            <form action="{{ route('admin.verifications.update-status', $verification) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit"
                                                    class="text-xs bg-zinc-800 hover:bg-zinc-700 border border-zinc-700 text-zinc-300 px-3 py-1.5 rounded-lg transition-colors">
                                                    Mark Pending
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-10 text-center text-zinc-500 text-sm" colspan="6">
                                    No verification requests found for the selected filters.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-between">
                <span class="text-xs text-zinc-500 font-medium">
                    Showing {{ $verifications->firstItem() ?? 0 }} to {{ $verifications->lastItem() ?? 0 }} of {{ $verifications->total() }} requests
                </span>
                <div class="flex items-center gap-2">
                    <a href="{{ $verifications->previousPageUrl() ?? '#' }}"
                        class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-colors flex items-center justify-center {{ $verifications->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                    <a href="{{ $verifications->nextPageUrl() ?? '#' }}"
                        class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-zinc-500 transition-colors flex items-center justify-center {{ $verifications->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>

@include('admin.verifications.partials.reject-modal')