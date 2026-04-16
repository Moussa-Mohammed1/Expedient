<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Salles</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 min-h-screen">

    @include('layouts.header')
    @if (blank(auth()->user()?->localisation))
        <div class="max-w-350 mx-auto px-3 sm:px-6 lg:px-8 py-8 lg:py-12">
            <div class="border border-zinc-800 rounded-xl p-6 sm:p-10 text-center bg-[#111111]">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Localisation Required</h2>
                <p class="text-zinc-400">
                    You havent entered your localisation yet, go to your profile and enter it.
                </p>
            </div>
        </div>
    @else
        <div class="max-w-350 mx-auto px-3 sm:px-6 lg:px-8 py-8 lg:py-12">

            <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 border-b border-zinc-800 pb-5">
                <div>
                    <h1 class="text-2xl lg:text-4xl font-bold text-white tracking-tight mb-2">Explore Facilities</h1>
                    <p class="text-zinc-400 text-sm lg:text-base flex items-center gap-2">
                        Showing salles near <span class="text-white font-medium">{{ $userLocation ?: 'your area' }}</span>
                    </p>
                </div>

                <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
                    <button
                        class="bg-[#1c1c1c] border border-zinc-700 text-white text-xs font-medium px-4 py-2 rounded-full hover:bg-zinc-800 transition-colors">
                        <i class="fa-solid fa-sliders mr-1"></i> Filters
                    </button>
                    <button
                        class="bg-[#ff5520]/10 border border-[#ff5520]/50 text-[#ff5520] text-xs font-medium px-4 py-2 rounded-full ">
                        Open Now (Default)
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
                @forelse ($salles as $salle)
                    <a href="{{ route('salles.show', $salle->id) }}"
                        class="bg-[#111111] border border-zinc-800/80 rounded-xl overflow-hidden hover:border-zinc-600 transition-colors flex flex-col">
                        <div class="relative h-28 sm:h-40 lg:h-48 overflow-hidden bg-[#1c1c1c]">
                            @php
                                $cover = $salle->galleries->first()?->content;
                                $coverUrl = $cover
                                    ? asset('/storage/salles/galleries/' . $cover)
                                    : asset('/' . ($salle->background ?: 'assets/images/salle_default.jpeg'));
                            @endphp
                            <img src="{{ $coverUrl }}" alt="{{ $salle->name }}"
                                class="w-full h-full object-cover transition-transform duration-500">
                            <div
                                class="absolute top-2 left-2 bg-black/80 backdrop-blur-sm border border-zinc-700 text-white text-[10px] sm:text-xs font-bold px-2 py-1 rounded-md uppercase tracking-wider">
                                {{ $salle->sessionType ?: 'Any Session' }}
                            </div>
                        </div>

                        <div class="p-3 sm:p-5 flex flex-col flex-1">
                            <div class="mb-2">
                                <div class="flex justify-between items-start gap-2">
                                    <h3 class="text-sm sm:text-lg font-bold text-white leading-tight truncate"
                                        title="{{ $salle->name }}">{{ $salle->name }}</h3>
                                    <div class="flex items-center gap-1 text-zinc-400 text-[10px] sm:text-xs shrink-0">
                                        <i class="fa-solid fa-dumbbell"></i>
                                        {{ $salle->sport?->title ?: 'Sport' }}
                                    </div>
                                </div>
                                <p class="text-[10px] sm:text-xs text-zinc-500 mt-1"><i
                                        class="fa-solid fa-location-dot mr-1"></i>{{ $salle->city }}</p>
                            </div>

                            <p class="text-[10px] sm:text-sm text-zinc-400 italic line-clamp-2 mb-3">
                                "{{ $salle->tagline ?: ($salle->description ?: 'Start your training journey today.') }}"
                            </p>

                            <div class="flex flex-wrap gap-1 mb-4 sm:mb-5 mt-auto">
                                @if ($salle->coach)
                                    <span
                                        class="bg-[#1c1c1c] border border-zinc-800 text-zinc-300 text-[9px] sm:text-[11px] px-1.5 sm:px-2 py-0.5 rounded-md">Coach:
                                        {{ $salle->coach->user?->name ?: 'Assigned' }}</span>
                                @endif
                                @if (!is_null($salle->existenceYears))
                                    <span
                                        class="bg-[#1c1c1c] border border-zinc-800 text-zinc-300 text-[9px] sm:text-[11px] px-1.5 sm:px-2 py-0.5 rounded-md"
                                        title="Established">Est. {{ $salle->existenceYears }}y</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-2 lg:col-span-4 border border-zinc-800 rounded-xl p-6 text-center bg-[#111111]">
                        <p class="text-white font-semibold mb-1">No salles found for your location.</p>
                        <p class="text-zinc-400 text-sm">Update your location or check back later for new facilities.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

</body>

</html>