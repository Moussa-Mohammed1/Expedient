<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Expedient - salles</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-[#0f0f10] text-gray-300 font-sans antialiased min-h-screen">
    @include('layouts.coachNavbar')
    <x-notification-popup />
    <div class="relative overflow-hidden">

        <div class="relative max-w-350 mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="flex flex-col xl:flex-row xl:items-end justify-between gap-6 mb-10">
                <div class="max-w-3xl space-y-4">
                    <div>
                        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white">Hello <span
                                class="text-yellow-500">{{ auth()->user()->name }}</span>, There is your salles</h1>
                        <p class="mt-3 text-sm sm:text-base text-zinc-400 max-w-2xl">
                            Track the salles linked to your coach profile, review their details, and jump into each
                            space.
                        </p>
                    </div>
                </div>

                <div class="w-full xl:w-auto xl:min-w-90 space-y-3">

                    <div class="grid grid-cols-2 gap-3 sm:gap-4">
                        <div class="rounded-lg border border-white/10 bg-[#323131]/90 p-4 backdrop-blur">
                            <p class="text-[11px] uppercase text-zinc-500">Coach access</p>
                            <p class="mt-2 text-lg font-semibold text-white">
                                {{ auth()->user()?->role?->title ?? 'User' }}
                            </p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-[#323131] p-4 backdrop-blur">
                            <p class="text-[11px] uppercase  text-zinc-500">Total salles</p>
                            <p class="mt-2 text-lg font-semibold text-white">{{ $salleCount }}</p>
                        </div>
                        <div>
                            <a href="{{ route('coach.salles.create') }}"
                                class=" w-full items-center justify-center gap-2 rounded-full bg-yellow-500 px-4 py-3 text-sm font-semibold text-black transition-colors hover:bg-[#ff6f42]">
                                <i class="fa-solid fa-plus"></i>
                                Create Salle
                            </a>
                        </div>
                    </div>
                </div>
            </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                    @forelse ($salles as $salle)
                        @php
                            $cover = $salle->background ?: $salle->galleries->first()?->content;
                            $resolveImageUrl = function (?string $path, string $fallbackUrl): string {
                                if (!$path) {
                                    return $fallbackUrl;
                                }

                                if (filter_var($path, FILTER_VALIDATE_URL)) {
                                    return $path;
                                }

                                $normalizedPath = ltrim($path, '/');

                                if (str_starts_with($normalizedPath, 'assets/') || str_starts_with($normalizedPath, 'storage/')) {
                                    return asset($normalizedPath);
                                }

                                return asset('storage/' . $normalizedPath);
                            };

                            $coverUrl = $resolveImageUrl($cover, asset('assets/images/salle_default.jpeg'));
                        @endphp

                        <div
                            class="overflow-hidden  rounded-sm border-white/10 bg-[#141414] cursor-pointer">
                            <a href="{{ route('salles.show', $salle) }}" class="block">
                                <div class="relative h-52 overflow-hidden bg-zinc-900">
                                    <img src="{{ $coverUrl }}" alt="{{ $salle->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 ">
                                    
                                    <div
                                        class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full border border-white/15 bg-black/45 px-3 py-1 text-[11px] font-semibold text-white backdrop-blur">
                                        <i class="fa-solid fa-location-dot text-[#d1fa48]"></i>
                                        {{ $salle->city ?: 'Unknown city' }}
                                    </div>
                                    <div class="absolute  bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                                        <div class="min-w-0">
                                            <h2 class="truncate text-xl font-bold text-white">{{ $salle->name }}</h2>
                                            <p class="mt-1 text-sm text-zinc-300">{{ $salle->sport?->title ?: 'General sport' }}
                                            </p>
                                        </div>
                                        <div
                                            class="rounded-full bg-black/50 px-3 py-1 text-[11px] font-semibold text-white backdrop-blur">
                                            {{ $salle->sessionType ?: 'Open session' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 p-5">
                                    <p class="line-clamp-2 min-h-12 text-sm text-zinc-400">
                                        {{ $salle->tagline ?: $salle->description ?: 'No description provided yet.' }}
                                    </p>

                                    <div class="flex flex-wrap gap-2 text-[11px] font-medium">
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-zinc-300">
                                            Coach: {{ $salle->coach?->user?->name ?? 'Assigned coach' }}
                                        </span>
                                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-zinc-300">
                                            {{ $salle->galleries->count() }} photos
                                        </span>
                                        @if (!is_null($salle->existenceYears))
                                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-zinc-300">
                                                Est. {{ $salle->existenceYears }} years
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between border-t border-white/5 pt-4 text-sm">
                                        <span class="text-zinc-500">View the full salle profile</span>
                                        <span class="inline-flex items-center gap-2 text-[#ff5520] font-semibold">
                                            Open
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <div
                            class="sm:col-span-2 xl:col-span-3  p-6 sm:p-10 text-center">
                            <h2 class="text-2xl font-bold text-white">No salles yet</h2>
                            <p class="mt-3 text-zinc-400 max-w-xl mx-auto">
                                Your coach profile is ready, but no salle has been created for it yet.
                            </p>
                        </div>
                    @endforelse
                </div>
        </div>
    </div>

</body>

</html>