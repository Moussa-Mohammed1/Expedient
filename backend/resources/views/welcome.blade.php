<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-black min-h-screen">
    <section class="min-h-screen bg-black relative overflow-hidden">
        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-40 pointer-events-none">
            <div
                class="w-full h-full bg-[linear-gradient(30deg,rgba(0,0,0,0.02)_12%,transparent_12.5%,transparent_87%,rgba(0,0,0,0.02)_87.5%,rgba(0,0,0,0.02)),linear-gradient(150deg,rgba(0,0,0,0.02)_12%,transparent_12.5%,transparent_87%,rgba(0,0,0,0.02)_87.5%,rgba(0,0,0,0.02)),linear-gradient(30deg,rgba(0,0,0,0.02)_12%,transparent_12.5%,transparent_87%,rgba(0,0,0,0.02)_87.5%,rgba(0,0,0,0.02)),linear-gradient(150deg,rgba(0,0,0,0.02)_12%,transparent_12.5%,transparent_87%,rgba(0,0,0,0.02)_87.5%,rgba(0,0,0,0.02)),linear-gradient(60deg,rgba(0,0,0,0.015)_25%,transparent_25.5%,transparent_75%,rgba(0,0,0,0.015)_75%,rgba(0,0,0,0.015)),linear-gradient(60deg,rgba(0,0,0,0.015)_25%,transparent_25.5%,transparent_75%,rgba(0,0,0,0.015)_75%,rgba(0,0,0,0.015))] bg-[length:80px_140px] bg-[position:0_0,0_0,40px_70px,40px_70px,0_0,40px_70px]">
            </div>
        </div>

        <header class="relative z-10">
            <div class="max-w-7xl mx-auto px-6 lg:px-10 py-7 flex items-center justify-between">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-3">
                    <span class="text-3xl font-bold text-white tracking-tight">Expedient.</span>
                </a>

                <nav class="flex items-center gap-8">
                    <a href="{{ route('login') }}"
                        class="text-white text-lg font-medium hover:text-emerald-600 transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                        class="bg-yellow-400 text-sm hover:bg-yellow-500 text-black rounded-l-full rounded-e-full font-semibold px-4 py-2 rounded-md shadow-sm transition">
                        Join us </a>
                </nav>
            </div>
        </header>

        <!-- Hero -->
        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-10 pt-10 pb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-12 min-h-[calc(100vh-120px)]">
                <!-- Left content -->
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

                    <div class="mt-10">
                        <a href="{{ route('register') }}"
                            class="inline-block bg-emerald-400 hover:bg-emerald-500 text-white text-2xl font-semibold px-16 py-5 rounded-md shadow-sm transition">
                            Commencer
                        </a>
                    </div>

                    <p class="mt-10 text-white font-semibold text-lg">
                        Available for athletes, coaches and gyms, online
                    </p>
                </div>

                <div class="flex items-center justify-center">
                    <div
                        class="w-full max-w-[620px] h-[620px] overflow-hidden rounded-xl bgb-lack border-2  border-yellow-400 flex items-center justify-center">
                        <div class="text-center px-6">
                            <p class="mt-3 text-zinc-400 text-lg">
                                <img class="w-full h-full object-cover"
                                    src="https://i.pinimg.com/736x/77/c6/24/77c6240cac8ed308010d92510402056a.jpg"
                                    alt="">
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
                <!-- Stat 1 -->
                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ number_format($activeAthletes) }}+
                    </h3>
                    <p class="text-yellow-400 text-lg font-semibold">Active Athletes</p>
                </div>
                <!-- Stat 2 -->
                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ number_format($verifiedCoaches) }}+
                    </h3>
                    <p class="text-zinc-400 text-lg font-medium">Verified Coaches</p>
                </div>
                <!-- Stat 3 -->
                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ number_format($gymsCount) }}+</h3>
                    <p class="text-zinc-400 text-lg font-medium">Gyms Across {{ $citiesCount }} Cities</p>
                </div>
                <!-- Stat 4 -->
                <div class="flex flex-col items-center space-y-2 w-full">
                    <h3 class="text-4xl lg:text-5xl font-extrabold text-white">{{ $averageRating }}</h3>
                    <div class="flex items-center gap-1 text-yellow-400 mb-1">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star-half-stroke"></i>
                    </div>
                    <p class="text-zinc-400 text-lg font-medium">Average Rating</p>
                </div>
            </div>
        </div>
    </section>
    <section class="py-20 bg-black text-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-14">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white">
                    One for all, all for one
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
                <!-- Column 1 -->
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

                <!-- Column 2 -->
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

                <!-- Column 3 -->
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