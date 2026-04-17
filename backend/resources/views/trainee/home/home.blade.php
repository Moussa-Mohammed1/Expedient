<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - home</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    @include('layouts.header')

    <!-- Available sports And recent added Salles in the user locatlisation  -->

    <section class="py-6 bg-black relative w-full border-b border-zinc-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-8">
                    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                        <div>
                            <h2 class="text-xl md:text-xl font-extrabold text-white ">
                                Explore by <span class="text-[#FBBF24]">Discipline</span>
                            </h2>
                            <p class="mt-3 text-zinc-400 text-sm">
                                Find the perfect environment for your specific training needs.
                            </p>
                        </div>

                    </div>

                    <div
                        class="flex gap-3 overflow-x-auto md:overflow-visible md:flex-wrap pb-2 md:pb-0">
                        @forelse($sports as $sport)
                            <a href=""
                                class="group relative min-w-[calc(50%-0.375rem)] max-w-[calc(50%-0.375rem)] md:min-w-45 md:max-w-55 h-14 flex items-center gap-3 px-4 bg-[#322e2e] rounded-full border border-zinc-800/80 hover:border-yellow-500/50 duration-300 overflow-hidden cursor-pointer snap-start">

                                <div
                                    class="relative h-8 w-8 rounded-full bg-[#1c1c1c] border border-zinc-800 flex items-center justify-center group-hover:border-zinc-700 transition-all duration-300 shadow-sm shrink-0">
                                    <i
                                        class="fa-solid {{ $sport['icon'] }} text-[10px] md:text-xs text-zinc-500  transition-colors duration-300"></i>
                                </div>
                                <div class="relative text-left w-full">
                                    <h3
                                        class="text-white font-bold text-sm md:text-base w-full truncate">
                                        {{ $sport['title'] }}
                                    </h3>
                                </div>
                            </a>
                        @empty
                            <div
                                class="col-span-2 md:col-span-3 lg:col-span-6 rounded-lg border border-zinc-800 bg-[#1b1b1b] p-10 text-center">
                                <p class="mt-4 text-sm text-zinc-400">No disciplines available right now. <span
                                        class="text-yellow-500 font-bold italic text-xl uppercase">(empty)</span></p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="lg:col-span-4">
                    @if(blank(auth()->user()?->localisation))
                        <section class="bg-[#1c1c1c] rounded-2xl py-10 px-6">
                            <div class="mb-8">
                                <h2 class="text-xl md:text-2xl font-bold text-white mb-2">
                                    Recent added <span class="text-[#FBBF24]">Salles in your localisation</span>
                                </h2>
                                <p class="text-gray-400 text-sm">
                                    Discover the latest gyms around your area.
                                </p>
                            </div>
                            <div class="text-center">
                                <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Localisation Required</h2>
                                <p class="text-gray-300">
                                    You havent entered your localisation yet, go to your profile and enter it.
                                </p>
                            </div>
                        </section>
                    @else
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-5">
                            <div>
                                <h2 class="text-sm md:text-lg font-extrabold text-white">
                                    Recent added <span class="text-[#FBBF24]">Salles in your localisation</span>
                                </h2>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 h-60 overflow-y-scroll [scrollbar-width:none] gap-6 bg-[#2d2b2be3] p-4 rounded-lg ">
                            @forelse($recentSalles as $salle)
                                @php
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

                                    $backgroundUrl = $resolveImageUrl($salle->background, asset('assets/images/salle_default.jpeg'));
                                @endphp
                                <a href="{{ url('/salles/' . $salle['id']) }}"
                                    class="group relative h-30 flex flex-col justify-end rounded-2xl overflow-hidden border border-zinc-800 hover:border-yellow-500 transition-colors duration-200 cursor-pointer shadow-lg bg-[#111111]">
                                    <img src="{{ $backgroundUrl }}"
                                        class="absolute inset-0 w-full h-full object-cover" alt="{{ $salle['name'] }}">

                                    <div class="absolute inset-0 bg-linear-to-t from-black/80 via-black/20 to-transparent">
                                    </div>

                                    <div class="relative p-6 w-full">
                                        <div class="flex justify-between items-center mb-2 bg-black rounded-full w-fit px-2 py-1.5">
                                            <span class="text-xs text-yellow-500 font-medium">
                                                {{ optional($salle->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                        <h3
                                            class="text-white text-sm md:text-lg font-bold tracking-wide group-hover:text-yellow-400 transition-colors duration-200">
                                            {{ $salle['name'] }}
                                        </h3>
                                    </div>
                                </a>
                            @empty
                                <div class="rounded-2xl border border-zinc-800 bg-[#111111] p-6 text-center">
                                    <p class="text-sm text-zinc-400">No salles yet in your localisation. <span
                                            class="text-yellow-500 text-2xl italic font-bold uppercase">(Empty)</span> </p>
                                </div>
                            @endforelse
                        </div>
                        <div class="mt-8 text-center md:hidden">
                            <a href="{{ url('/salles') }}"
                                class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-yellow-400 transition-colors group">
                                Browse all salles
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Active communities section -->


    <section class="py-12 bg-black relative w-full border-b border-zinc-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">

            <div class="flex items-end justify-between mb-8">
                <h2 class="text-lg md:text-xl font-extrabold text-white ">
                    Your Active <span class="text-[#FBBF24]">Community</span>
                </h2>
            </div>

            @if($userCommunity)
                <div
                    class="group relative flex flex-col md:flex-row w-full min-h-55 rounded-2xl overflow-hidden border border-zinc-800 hover:border-yellow-500 transition-colors duration-200 bg-[#111111]">

                    <div class="relative w-full md:w-5/12 h-48 md:h-auto overflow-hidden shrink-0">
                        <img src="{{ $userCommunity->backgroundImage ? asset('storage/' . $userCommunity->backgroundImage) : 'https://images.unsplash.com/photo-1502680390469-be75c86b636f?auto=format&fit=crop&w=800&q=80' }}"
                            alt="{{ $userCommunity->title }}" class="absolute inset-0 w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-linear-to-t md:bg-linearto-r from-transparent via-[#111111]/60 to-[#111111]">
                        </div>
                    </div>

                    <div
                        class="relative z-10 flex-1 p-6 md:p-8 flex flex-col justify-center bg-linear-to-t md:bg-linear-to-l from-[#111111] via-[#111111] to-transparent md:to-[#111111]">

                        <div class="flex items-center gap-3 mb-3">
                            <span
                                class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Joined
                            </span>
                        </div>

                        <h3
                            class="text-white text-2xl md:text-3xl font-bold tracking-wide group-hover:text-yellow-400 transition-colors duration-200">
                            {{ $userCommunity->title }}
                        </h3>

                        @if($userCommunity->description)
                            <p class="text-zinc-400 text-sm mt-2 line-clamp-2 leading-relaxed max-w-2xl">
                                {{ $userCommunity->description }}
                            </p>
                        @endif

                        <div class="mt-6 flex flex-wrap gap-4 items-center">
                            <a href="{{ url('/communities/' . $userCommunity->id) }}"
                                class="inline-flex items-center justify-center h-10 px-6 font-bold text-xs rounded-lg bg-yellow-500 text-black transition-colors hover:bg-yellow-600">
                                Enter Hub <i class="fa-solid fa-arrow-right ml-2"></i>
                            </a>
                            <button
                                class="inline-flex items-center justify-center h-10 px-4 font-bold text-xs rounded-lg bg-[#1c1c1c] text-white border border-zinc-700 transition-colors hover:bg-zinc-800">
                                Leave Group
                            </button>
                        </div>
                    </div>

                </div>
            @else
                <div class=" rounded-xl  bg-[#111111] p-10 flex flex-col items-center justify-center text-center">

                    <h3 class="text-white font-bold text-lg">No Active Community</h3>
                    <p class="text-zinc-500 text-sm mt-2 max-w-md">You haven't joined a training group yet. Explore local
                        communities to connect with other athletes.</p>
                    <a href="{{ url('/communities') }}"
                        class="mt-6 inline-flex items-center justify-center h-10 px-6 font-bold text-xs rounded-full bg-yellow-500 text-black border border-zinc-700 transition-colors hover:bg-yellow-700">
                        Explore Communities
                    </a>
                </div>
            @endif

        </div>
    </section>
</body>

</html>