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

<body class="bg-[#000] text-gray-300 font-sans antialiased min-h-screen selection:bg-[#ff5520] selection:text-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="#"
            class="inline-flex items-center text-sm font-medium text-zinc-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Exploration
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
        <div
            class="grid grid-cols-1 md:grid-cols-4 gap-2 md:gap-3 h-[300px] md:h-[400px] rounded-2xl overflow-hidden bg-[#111111] border border-zinc-800/80">
            <div class="md:col-span-3 h-full relative group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=1200&auto=format&fit=crop"
                    alt="Main Gallery Image" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-all"></div>
                <div
                    class="absolute bottom-4 left-4 bg-black/80 backdrop-blur-md border border-zinc-700 text-[#d1fa48] text-xs font-bold px-3 py-1.5 rounded-md uppercase tracking-wider">
                    General Fitness & Weights
                </div>
            </div>
            <div class="hidden md:flex flex-col gap-2 md:gap-3 h-full">
                <div class="h-1/2 relative group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1540497077202-7c8a3999166f?q=80&w=400&auto=format&fit=crop"
                        alt="Gallery 2" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/40 group-hover:bg-transparent transition-all"></div>
                </div>
                <div class="h-1/2 relative group cursor-pointer">
                    <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?q=80&w=400&auto=format&fit=crop"
                        alt="Gallery 3" class="w-full h-full object-cover">
                    <div
                        class="absolute inset-0 bg-black/60 flex items-center justify-center group-hover:bg-black/40 transition-all">
                        <span class="text-white font-bold text-sm flex items-center gap-2"><i
                                class="fa-solid fa-images"></i> View All</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">

            <div class="w-full lg:w-2/3 space-y-10">

                <div>
                    <h1 class="text-3xl sm:text-5xl font-bold text-white tracking-tight mb-3">Atlas Power Gym</h1>
                    <p class="text-lg sm:text-xl text-zinc-400 italic mb-4">"Forge your body, elevate your mind."</p>

                    <div class="flex flex-wrap items-center gap-4 text-sm font-medium border-b border-zinc-800 pb-6">
                        <div class="flex items-center gap-1.5 text-zinc-300">
                            <i class="fa-solid fa-location-dot text-[#FBBF24]"></i> Safi, Morocco
                        </div>
                        <div class="w-1.5 h-1.5 rounded-full bg-zinc-700"></div>
                        <div class="flex items-center gap-1.5 text-zinc-300">
                            <i class="fa-solid fa-building text-zinc-500"></i> Established 5 Years Ago
                        </div>
                        <div class="w-1.5 h-1.5 rounded-full bg-zinc-700"></div>
                        <div
                            class="flex items-center gap-1.5 text-white bg-[#1c1c1c] border border-zinc-700 px-2 py-0.5 rounded uppercase text-[11px] tracking-wider">
                            Mixed Sessions
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-4">About this Space</h2>
                    <p class="text-zinc-400 leading-relaxed text-sm sm:text-base">
                        Atlas Power Gym is a premium fitness facility located in the heart of Safi. We provide top-tier
                        equipment for powerlifters, bodybuilders, and general fitness enthusiasts. Our space is designed
                        to foster a strong community environment with high energy and zero distractions. Whether you're
                        a beginner or a seasoned athlete, you'll find everything you need to hit your next PR.
                    </p>
                </div>

                <div>
                    <h2 class="text-xl font-bold text-white mb-4">Amenities & Services</h2>
                    <div class="flex flex-wrap gap-2.5">
                        <span
                            class="bg-[#111111] border border-zinc-800 text-zinc-300 text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                            <i class="fa-solid fa-shower text-[#FBBF24]"></i> Showers & Lockers
                        </span>
                        <span
                            class="bg-[#111111] border border-zinc-800 text-zinc-300 text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                            <i class="fa-solid fa-wifi text-[#FBBF24]"></i> Free WiFi
                        </span>
                        <span
                            class="bg-[#111111] border border-zinc-800 text-zinc-300 text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                            <i class="fa-solid fa-bottle-water text-[#FBBF24]"></i> Supplement Bar
                        </span>
                        <span
                            class="bg-[#111111] border border-zinc-800 text-zinc-300 text-sm px-4 py-2 rounded-lg flex items-center gap-2">
                            <i class="fa-solid fa-car text-[#FBBF24]"></i> Free Parking
                        </span>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-white">Available Equipment</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div class="bg-[#111111] border border-zinc-800/80 rounded-xl p-4 flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-lg bg-[#1c1c1c] overflow-hidden shrink-0 border border-zinc-700">
                                <img src="https://images.unsplash.com/photo-1576678927484-cc907957088c?w=100&h=100&fit=crop"
                                    alt="Squat Rack" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-sm">Competition Squat Racks (x4)</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Rogue Fitness Monster Series</p>
                                <div
                                    class="mt-2 inline-flex items-center bg-[#1c1c1c] border border-green-500/30 text-green-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                    Condition: Excellent
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#111111] border border-zinc-800/80 rounded-xl p-4 flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-lg bg-[#1c1c1c] overflow-hidden shrink-0 border border-zinc-700">
                                <img src="https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?w=100&h=100&fit=crop"
                                    alt="Dumbbells" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-sm">Dumbbell Racks (5-50kg)</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Full set of calibrated dumbbells</p>
                                <div
                                    class="mt-2 inline-flex items-center bg-[#1c1c1c] border border-green-500/30 text-green-400 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                    Condition: Good
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#111111] border border-zinc-800/80 rounded-xl p-4 flex items-center gap-4">
                            <div
                                class="w-16 h-16 rounded-lg bg-[#1c1c1c] overflow-hidden shrink-0 border border-zinc-700">
                                <img src="https://images.unsplash.com/photo-1518310383802-640c2de311b2?w=100&h=100&fit=crop"
                                    alt="Treadmills" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="text-white font-bold text-sm">Cardio Zone</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Treadmills & Stairmasters</p>
                                <div
                                    class="mt-2 inline-flex items-center bg-[#1c1c1c] border border-[#FBBF24]/30 text-[#FBBF24] text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                    Condition: Fair
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <div class="w-full lg:w-1/3">
                <div class="sticky top-6 flex flex-col gap-6">

                    <div
                        class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-6 relative overflow-hidden shadow-xl">
                        <div
                            class="absolute top-0 right-0 -mt-16 -mr-16 w-32 h-32 bg-[#ff5520] rounded-full blur-[70px] opacity-20">
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <div class="flex flex-col">
                                <span class="text-2xl font-bold text-white">Open</span>
                                <span class="text-xs text-green-400 font-medium">Closes at 10:00 PM</span>
                            </div>
                            <div
                                class="flex items-center gap-1.5 bg-[#1c1c1c] border border-zinc-700 px-3 py-1.5 rounded-lg">
                                <i class="fa-solid fa-star text-[#FBBF24] text-sm"></i>
                                <span class="text-white font-bold text-sm">4.8</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button
                                class="w-full bg-[#ff5520] hover:bg-[#ff7a00] text-white font-bold py-3.5 px-4 rounded-xl transition-colors text-center text-sm">
                                Book a Session
                            </button>
                            <button
                                class="w-full bg-[#1c1c1c] hover:bg-zinc-800 border border-zinc-700 text-white font-medium py-3 px-4 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                                <i class="fa-regular fa-bookmark"></i> Save to Favorites
                            </button>
                        </div>
                    </div>

                    <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-6">
                        <h3 class="text-white font-bold text-lg mb-4 flex items-center gap-2">
                            <i class="fa-regular fa-clock text-zinc-500"></i> Opening Hours
                        </h3>
                        <ul class="text-sm text-zinc-400 space-y-3">
                            <li class="flex justify-between items-center py-1 border-b border-zinc-800/50">
                                <span>Monday</span> <span class="text-white font-medium">06:00 - 22:00</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-zinc-800/50">
                                <span>Tuesday</span> <span class="text-white font-medium">06:00 - 22:00</span>
                            </li>
                            <li
                                class="flex justify-between items-center py-1 border-b border-zinc-800/50 text-[#d1fa48] font-medium">
                                <span>Wednesday</span> <span>06:00 - 22:00 (Today)</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-zinc-800/50">
                                <span>Thursday</span> <span class="text-white font-medium">06:00 - 22:00</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-zinc-800/50">
                                <span>Friday</span> <span class="text-white font-medium">06:00 - 22:00</span>
                            </li>
                            <li class="flex justify-between items-center py-1 border-b border-zinc-800/50">
                                <span>Saturday</span> <span class="text-white font-medium">08:00 - 20:00</span>
                            </li>
                            <li class="flex justify-between items-center py-1">
                                <span>Sunday</span> <span class="text-[#ff5520] font-medium">Closed</span>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-6">
                        <h3 class="text-zinc-500 font-bold text-xs uppercase tracking-wider mb-4">Managed By</h3>
                        <div class="flex items-center gap-4 group cursor-pointer">
                            <img src="https://ui-avatars.com/api/?name=Marcus+Rashford&background=1c1c1c&color=fff"
                                alt="Coach" class="h-12 w-12 rounded-full border border-zinc-700">
                            <div class="flex-1">
                                <h4 class="text-white font-bold text-sm group-hover:text-[#FBBF24] transition-colors">
                                    Marcus R.</h4>
                                <p class="text-xs text-zinc-500 mt-0.5">Professional Coach</p>
                            </div>
                            <i
                                class="fa-solid fa-chevron-right text-zinc-600 group-hover:text-white transition-colors text-sm"></i>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

</body>

</html>