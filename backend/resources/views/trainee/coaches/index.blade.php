<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Coaches</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body>
    @include('layouts.header')
    @if (blank(auth()->user()?->localisation))
        <section class="bg-[#1c1c1c] py-16 px-6 lg:px-24">
            <div class="mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">
                    Coaches <span class="text-[#d1fa48]">Near You</span>
                </h2>
                <p class="text-gray-400 max-w-md">
                    Connect with professional trainers in your area to accelerate your fitness journey.
                </p>
            </div>
            <div class=" bg- md:p-8 text-center">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">Localisation Required</h2>
                <p class="text-gray-300">
                    You havent entered your localisation yet, go to your profile and enter it.
                </p>
            </div>
        </section>
    @else
        <section class="bg-[#1c1c1c] py-16 px-6 lg:px-24">
            <div class="max-w-7xl mx-auto">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">
                            Coaches <span class="text-[#d1fa48]">Near You</span>
                        </h2>
                        <p class="text-gray-400 max-w-md">
                            Connect with professional trainers in your area to accelerate your fitness journey.
                        </p>
                    </div>
                    <a href="{{ route('explore') }}"
                        class="text-[#ff7a00] hover:text-[#ff9533] font-medium transition-colors flex items-center gap-2">
                        View all coaches
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 lg:gap-8">

                    @forelse($coaches as $coach)
                        <a href="{{ route('coaches.show', $coach->coach->id) }}"
                            class="group block w-full bg-[#1c1c1c] rounded-xl overflow-hidden border border-white/5 hover:border-[#d1fa48]/30 transition-all duration-300">
                            <div class="relative aspect-4/3 overflow-hidden">
                                <img src="{{ $coach->avatar ? asset('/storage/users/profiles/' . $coach->avatar) : asset('assets/images/profile.jpeg') }}"
                                    alt="{{ $coach->name }}"
                                    class="object-cover w-full h-full transition-transform duration-500">
                                <div
                                    class="absolute top-3 left-3 bg-[#111111]/80 backdrop-blur-md text-[#d1fa48] text-[11px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $coach->coach->specialities->first()?->title ?? 'Fitness' }}
                                </div>
                                @if($coach->coach->hasBadge)
                                    <div class="absolute top-3 right-3 bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                        <i class="fa-solid fa-star"></i> Verified
                                    </div>
                                @endif
                            </div>
                            <div class="p-3 md:p-4">
                                <div class="flex justify-between items-start mb-1 gap-1">
                                    <h3 class="text-white font-bold text-sm md:text-base truncate">{{ $coach->name }}</h3>
                                    <div class="flex items-center text-[#ff7a00]">
                                        <span class="text-xs font-bold mr-1">{{ number_format($coach->coach->reputation_rate ?? 0, 1) }}</span>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                                <p class="text-gray-500 text-xs mb-3 line-clamp-1">
                                    {{ $coach->localisation }} • {{ $coach->coach->reviews_count ?? 0 }} reviews
                                </p>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-400">No coaches found in your area.</p>
                            <p class="text-gray-400">Try explore some other coaches</p>
                            <a  class="text-white" href="{{ route('explore') }}"> Explore</a>
                        </div>
                    @endforelse


                </div>
            </div>
        </section>
    @endif
</body>

</html>