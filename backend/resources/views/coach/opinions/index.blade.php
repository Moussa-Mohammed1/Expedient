<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Expedient - Reviews</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-[#18181b] text-gray-300">
    @include('layouts.coachNavbar')
    <x-notification-popup />

    @php
        $formattedAverage = number_format((float) $averageRating, 1);

        $resolveAvatarUrl = function (?string $path): string {
            $defaultAvatarUrl = asset('assets/images/profile.jpeg');

            if (!$path) {
                return $defaultAvatarUrl;
            }

            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            $normalizedPath = ltrim($path, '/');

            if (str_starts_with($normalizedPath, 'assets/') || str_starts_with($normalizedPath, 'storage/')) {
                return asset($normalizedPath);
            }

            return asset('storage/users/profiles/' . $normalizedPath);
        };
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-12">
        @if (session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-lg p-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div
            class="mb-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-zinc-800/80 pb-6">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white">Your Reviews</h1>
                <p class="text-zinc-500 text-sm mt-1">Track feedback and moderate reported content.</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div class="bg-[#222222] border border-zinc-700 px-5 py-2 rounded-full flex items-center gap-3">
                    <div class="flex items-center gap-1.5 text-[#FBBF24]">
                        <i class="fa-solid fa-star"></i>
                        <span class="text-white font-bold text-lg leading-none mt-0.5">{{ $formattedAverage }}</span>
                    </div>
                    <div class="w-px h-4 bg-zinc-600"></div>
                    <span class="text-sm text-zinc-400 font-medium">{{ $totalReviews }} Total Reviews</span>
                </div>

                <span class="bg-[#222222] border border-zinc-700 text-zinc-300 px-3 py-1.5 rounded-full text-sm">
                    {{ $approvedReviews }} Approved
                </span>
                <span class="bg-[#222222] border border-zinc-700 text-zinc-300 px-3 py-1.5 rounded-full text-sm">
                    {{ $unapprovedReviews }} Unapproved
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:gap-6">
            @forelse ($opinions as $opinion)
                @php
                    $authorName = $opinion->author?->name ?: 'Anonymous';
                    $avatarUrl = $resolveAvatarUrl($opinion->author?->avatar)
                        ?: 'https://ui-avatars.com/api/?name=' . urlencode($authorName) . '&background=1c1c1c&color=FBBF24';
                    $roundedRate = (int) round((float) $opinion->rate);
                @endphp

                <div class="bg-[#222222] border border-zinc-800 rounded-md p-5 sm:p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $avatarUrl }}" alt="{{ $authorName }}"
                                class="h-10 w-10 rounded-full border border-zinc-700 object-cover">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ $authorName }}</h4>
                                <span class="text-xs text-zinc-500">{{ $opinion->created_at?->format('F d, Y') }}</span>
                            </div>
                        </div>

                        <div class="relative group">
                            <button type="button" class="text-zinc-500 p-2 rounded-full outline-none cursor-pointer">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <div
                                class="absolute right-0 mt-1 w-40 bg-[#1c1c1c] border border-zinc-700 rounded-md shadow-2xl invisible opacity-0 group-focus-within:visible group-focus-within:opacity-100 z-10 overflow-hidden">
                                <div class="py-1">
                                    <button type="button"
                                        class="open-report-modal w-full text-left px-4 py-2.5 text-sm text-[#ff5520] font-medium"
                                        data-opinion-id="{{ $opinion->id }}" data-author-name="{{ $authorName }}">
                                        <i class="fa-solid fa-flag w-5 text-center mr-1"></i> Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex text-[#FBBF24] text-xs mb-3 gap-0.5">
                        @for ($star = 1; $star <= 5; $star++)
                            @if ($star <= $roundedRate)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-regular fa-star text-zinc-600"></i>
                            @endif
                        @endfor
                    </div>

                    <p class="text-zinc-300 text-sm leading-relaxed mb-4">
                        "{{ $opinion->content }}"
                    </p>

                </div>
            @empty
                <div class="bg-[#222222] border border-zinc-800 rounded-md p-10 text-center">
                    <p class="text-zinc-400">No reviews yet for your profile.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $opinions->links() }}
        </div>
    </div>

    @include('coach.opinions.partials.report-modal')
</body>

</html>