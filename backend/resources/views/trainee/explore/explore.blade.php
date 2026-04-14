<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - home</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="min-h-screen bg-black pb-24 font-sans">
    @include('layouts.header')
    <section class="max-w-7xl mx-auto px-6 lg:px-10 mb-16">
        <form action="{{ route('explore') }}" method="GET" class="relative max-w-4xl mx-auto group">

            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <i
                    class="fa-solid fa-magnifying-glass text-zinc-500 group-focus-within:text-yellow-500 transition-colors duration-200"></i>
            </div>
            <input name="q" type="text" placeholder="Search coaches, sports, or salles..." value="{{ $query ?? '' }}"
                class="w-full bg-[#111111] border-2 border-zinc-800 text-white text-xs md:text-sm rounded-full pl-12 pr-6 py-4 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/20 transition-all shadow-lg placeholder:text-zinc-600">
            <div class="absolute inset-y-0 right-2 flex items-center">
                <button type="submit"
                    class="bg-[#1c1c1c] cursor-pointer hover:bg-yellow-500 hover:text-black text-white text-xs font-bold py-2 px-4 rounded-2xl border border-zinc-700  duration-300">
                    Search
                </button>
            </div>
        </form>
    </section>

    @if(!empty($query))
        <section class="max-w-7xl mx-auto px-6 lg:px-10 mb-10">
            <div class="flex items-end justify-between mb-6">
                <h2 class="text-lg md:text-xl font-bold text-white ">
                    Search <span class="text-[#FBBF24]">Results</span>
                </h2>
            </div>

            <div class="mb-10">
                <h3 class="text-sm md:text-base font-bold text-zinc-200 mb-4">Coaches</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @forelse($coaches as $coachUser)
                        <a href="{{ route('coaches.show', $coachUser->coach->id) }}"
                            class="flex items-center gap-4 bg-[#2a2929] border rounded-xl p-2 hover:bg-[#272525] border-[#504a4a] transition-colors duration-300 group">
                            <div class="relative">
                                <img src="{{ $coachUser->avatar ? asset('/storage/users/profiles/' . $coachUser->avatar) : asset('assets/images/profile.jpeg')}}"
                                    alt="{{ $coachUser->name ?? 'Coach' }}"
                                    class="w-16 h-16 rounded-full border-2 border-zinc-800">
                                @if($coachUser->coach?->hasBadge())
                                    <span
                                        class="absolute -bottom-1 -right-1 bg-yellow-500 text-black text-[9px] w-4 h-4 flex items-center justify-center rounded-full border-2 border-[#111111]">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                @endif
                            </div>

                            <div class="min-w-0 flex-col">
                                <h3 class="text-white font-bold text-sm truncate">{{ $coachUser->name }}</h3>
                                <p class="text-zinc-500 text-xs mt-0.5 truncate">
                                    {{ $coachUser->localisation ?: 'Location not set' }}</p>
                                <p class="text-zinc-500 text-[10px] mt-1 truncate">
                                    {{ optional($coachUser->coach?->salles->first())->name ?: 'No salle assigned yet' }}</p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full rounded-xl border border-zinc-800 bg-[#111111] p-6 text-center">
                            <p class="text-sm text-zinc-400">No coaches match your search.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div>
                <h3 class="text-sm md:text-base font-bold text-zinc-200 mb-4">Salles</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($salles as $salle)
                        <a href="{{ url('/salles/' . $salle->id) }}"
                            class="rounded-xl border border-zinc-800 bg-[#111111] p-4 hover:border-yellow-500/60 transition-colors duration-300">
                            <h4 class="text-white font-bold text-sm md:text-base truncate">{{ $salle->name }}</h4>
                            <p class="text-zinc-500 text-xs mt-1 truncate">{{ $salle->city ?: 'Location not set' }}</p>
                        </a>
                    @empty
                        <div class="col-span-full rounded-xl border border-zinc-800 bg-[#111111] p-6 text-center">
                            <p class="text-sm text-zinc-400">No salles match your search.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    @else

        <section class="max-w-7xl mx-auto px-6 lg:px-10 mb-20">
            <div class="flex items-end justify-between mb-6">
                <h2 class="text-lg md:text-xl font-bold text-white ">
                    Top-Rated <span class="text-[#FBBF24]">Coaches</span>
                </h2>
            </div>
        
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @forelse($topCoaches->take(8) as $coach)
                    <a href="{{ route('coaches.show', $coach->id) }}"
                        class="flex items-center gap-4 bg-[#2a2929] border rounded-xl p-2 hover:bg-[#272525]  border-[#504a4a] transition-colors duration-300 group">
                        <div class="relative ">
                            <img src="{{ $coach->user?->avatar ? asset('/storage/users/profiles/' . $coach->user->avatar) : asset('assets/images/profile.jpeg')}}"
                                alt="{{ $coach->user?->name ?? 'Coach' }}"
                                class="w-16 h-16 rounded-full border-2 border-zinc-800">
                            @if($coach->hasBadge())
                                <span
                                    class="absolute -bottom-1 -right-1 bg-yellow-500 text-black text-[9px] w-4 h-4 flex items-center justify-center rounded-full border-2 border-[#111111]">
                                    <i class="fa-solid fa-check"></i>
                                </span>
                            @endif
                        </div>

                        <div class="min-w-0 flex-col">
                            <h3 class="text-white font-bold text-sm truncate">{{ $coach->user->name }}</h3>
                            <p class="text-zinc-500 text-xs mt-0.5 truncate">
                                {{ $coach->specialties?->pivot->first()?->title ?? 'General Fitness' }} - {{ $coach->user->localisation ?: 'No localisation'}}</p>

                            <div class="flex items-center gap-1 mt-2">
                                <div class="flex text-yellow-500 text-[10px]">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                </div>
                                <span class="text-zinc-500 text-[10px] font-medium">({{ $coach->reviews_count ?? 0 }})</span>
                            </div>
                        </div>
                    </a>
                @empty

                @endforelse
            </div>
        </section>
    @endif
</body>

</html>