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

<body class="bg-black min-h-screen">
    <section class="min-h-screen bg-black relative overflow-hidden">

        @include('layouts.guestNavbar')
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 pt-10 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12 min-h-[calc(100vh-120px)]">
                <div class="max-w-xl lg:pl-8">
                    <h1
                        class="text-3xl md:text-3xl lg:text-5xl font-extrabold leading-[1.05] tracking-tight text-white">
                        Find your
                        <br />
                        sport, perfect gym
                        <br />
                        <span class="text-[#FBBF24]">Your coach and your</span>
                        <br />
                        <span class="text-yellow-500">energy.</span>
                    </h1>

                    <div class="flex items-center gap-6 mt-10 text-5xl">
                        <span class="text-emerald-200"><i class="fa-solid fa-dumbbell text-yellow-500"></i></span>
                        <span class="text-violet-200"><i class="fa-solid fa-running text-red-500"></i></span>
                        <span class="text-rose-300"><i class="fa-solid fa-swimmer text-white"></i></span>
                        <span class="text-zinc-700"><i class="fa-solid fa-hand-fist text-blue-500"></i></span>
                    </div>

                    <p class="mt-10 text-white text-lg leading-relaxed max-w-lg">
                        Explore gyms, discover qualified coaches and join a community that helps you progress, near you
                        or anywhere in <span class="text-red-600 font-bold">Morocco</span>
                    </p>

                    <p class="mt-10 text-white font-semibold text-lg">
                        Available for athletes, coaches and gyms, online
                    </p>
                </div>

                <div class="flex items-center justify-center">
                    <div
                        class="w-full max-w-155 h-155 overflow-hidden rounded-xl bg-black   border-yellow-400 flex items-center justify-center">
                        <div class="text-center px-6">
                            <p class="mt-3 text-zinc-400 text-lg">

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-12 bg-black border-y border-zinc-800 relative z-10 w-full mt-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex flex-col md:flex-row items-center justify-between gap-10 md:gap-4 text-center">

                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ number_format($activeAthletes) }}+
                    </h3>
                    <p class="text-yellow-400 text-lg font-semibold">Active Athletes</p>
                </div>

                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ number_format($verifiedCoaches) }}+
                    </h3>
                    <p class="text-zinc-400 text-lg font-medium">Verified Coaches</p>
                </div>
                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ number_format($gymsCount) }}+</h3>
                    <p class="text-zinc-400 text-lg font-medium">Gyms Across {{ $citiesCount }} Cities</p>
                </div>
                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ $averageRating }}</h3>
                    <p class="text-zinc-400 text-lg font-medium">Average Rating</p>
                </div>
            </div>
        </div>
    </section>
    <section id="coaches" class="py-24 bg-black relative overflow-hidden border-b border-zinc-800">
        <div
            class="absolute top-1/2 left-0 -translate-y-1/2 w-125 h-125 bg-yellow-500/5 blur-[120px] rounded-full pointer-events-none">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

                <div class="max-w-xl">
                    <h2
                        class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white leading-[1.05] tracking-tight">
                        Elevate your game with <br>
                        <span class="text-[#FBBF24]">top-tier coaches.</span>
                    </h2>

                    <p class="mt-6 text-zinc-400 text-lg leading-relaxed max-w-lg">
                        Expedient brings the experts directly to you. Whether you are mastering deadlifts in Safi,
                        perfecting your striking in Casablanca, or looking for online guidance, our verified
                        professionals are ready to push your limits.
                    </p>

                    <div class="mt-12 flex items-center gap-10     pl-6">
                        <div>
                            <p class="text-4xl md:text-5xl font-extrabold text-white">{{ $coaches }}</p>
                            <p class="text-sm text-zinc-500 font-semibold uppercase tracking-wider mt-2">Verified
                                Coaches</p>
                        </div>
                        <div class="h-12 w-px bg-zinc-800"></div>
                        <div>
                            <p class="text-4xl md:text-5xl font-extrabold text-white">{{ $sports }}</p>
                            <p class="text-sm text-zinc-500 font-semibold uppercase tracking-wider mt-2">Sports
                                Disciplines</p>
                        </div>
                    </div>
                </div>

                <div class="relative w-full max-w-md mx-auto lg:mx-0 lg:ml-auto">
                    <div class=" rounded-lg border border-zinc-800/80 p-6 md:p-8 shadow-2xl relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-xl font-bold text-white">Featured Experts</h3>
                            <div class="flex gap-3 text-lg">
                                <i class="fa-solid fa-dumbbell text-yellow-500"></i>
                                <i class="fa-solid fa-hand-fist text-blue-500"></i>
                                <i class="fa-solid fa-swimmer text-emerald-400"></i>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @forelse($featuredCoaches as $coach)
                                                    @php
                                                        $avatar = $coach['avatar'] ?? null;
                                                        $coachAvatarUrl = $avatar
                                                            ? ((str_starts_with($avatar, 'http://') || str_starts_with($avatar, 'https://'))
                                                                ? $avatar
                                                                : (str_starts_with($avatar, 'users/profiles/')
                                                                    ? asset('/storage/' . $avatar)
                                                                    : asset('/storage/users/profiles/' . ltrim($avatar, '/'))))
                                                            : asset('assets/images/profile.jpeg');
                                                    @endphp
                                                    <div
                                                        class="flex items-center gap-4 p-3 rounded-lg bg-[#1c1c1c] border border-transparent hover:border-yellow-500/50 cursor-pointer group transition-all">
                                                        <div class="relative">
                                                            <div
                                                                class="w-14 h-14 rounded-full overflow-hidden border-2 {{ $coach['hasBadge'] ? 'border-yellow-500' : 'border-zinc-600' }}">
                                                                <img src="{{ $coachAvatarUrl }}" alt="Coach"
                                                                    class="w-full h-full object-cover transition-transform duration-300">
                                                            </div>
                                                            @if($coach['hasBadge'])
                                                                <span
                                                                    class="absolute -bottom-1 -right-1 bg-yellow-500 text-black text-[10px] font-bold px-1.5 py-0.5 rounded-md"><i
                                                                        class="fa-solid fa-check"></i></span>
                                                            @endif
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="truncate text-white font-bold text-base">{{ $coach['name'] }}</p>
                                                            <p class="truncate text-zinc-400 text-sm">{{ $coach['speciality'] }}</p>
                                                        </div>
                                                        <div class="ml-auto shrink-0 text-yellow-400 text-[10px] flex gap-0.5">
                                                            @for($star = 1; $star <= 5; $star++)
                                                                <i
                                                                    class="{{ $coach['rating'] >= $star ? 'fa-solid fa-star' : ($coach['rating'] >= ($star - 0.5) ? 'fa-solid fa-star-half-stroke' : 'fa-regular fa-star') }}"></i>
                                                            @endfor
                                                        </div>
                                                    </div>
                            @empty
                                <div class="rounded-xl bg-[#1c1c1c] p-4 text-sm text-zinc-400 border border-zinc-800">
                                    Coaches will appear here soon.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <section id="about" class="py-20 bg-black text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white">
                    One for all, all for one
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">

                <div class="space-y-6">
                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">
                            Search for gyms and coaches
                        </p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Quick session booking</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Discover multiple sports disciplines</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Advanced filters by city, sport and availability</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Consult reviews and ratings</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Add to favorites</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Explore sports communities</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Seamless access on mobile and desktop</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Complete profile for each coach</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Detailed presentation of gyms</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Clear display of essential information</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Location and visibility of establishments</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Simple journey for visitors and members</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Messaging or direct contact with coaches</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Personalized dashboard</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Smart recommendations</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Advanced management for gym owners</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Statistics and performance tracking</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Premium profile highlighting</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Enriched search and targeted suggestions</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Early access to new features</p>
                    </div>

                    <div class="group flex items-start gap-3">
                        <span
                            class="mt-1 w-6 flex justify-center items-center font-bold text-black h-fit rounded-sm bg-yellow-400 -rotate-45 transition-transform duration-300 group-hover:rotate-0">
                            Ex
                        </span>
                        <p class="text-xl text-white leading-snug">Distraction-free experience</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>