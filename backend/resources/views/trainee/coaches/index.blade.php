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
                    <a href="#"
                        class="text-[#ff7a00] hover:text-[#ff9533] font-medium transition-colors flex items-center gap-2">
                        View all coaches
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                    <div
                        class="group bg-[#1c1c1c] rounded-2xl overflow-hidden border border-white/5 hover:border-[#d1fa48]/30 transition-all duration-300">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="" alt="Coach"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                            <div
                                class="absolute top-3 left-3 bg-[#111111]/80 backdrop-blur-md text-[#d1fa48] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                Strength
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-white font-bold text-xl">Alex Rivera</h3>
                                <div class="flex items-center text-[#ff7a00]">
                                    <span class="text-sm font-bold mr-1">4.9</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm mb-4">5 miles away • Certified PT</p>
                            <button
                                class="w-full bg-[#d1fa48] hover:bg-[#b8e63a] text-[#111111] font-bold py-2 rounded-xl transition-colors">
                                View Profile
                            </button>
                        </div>
                    </div>

                    <div
                        class="group bg-[#1c1c1c] rounded-2xl overflow-hidden border border-white/5 hover:border-[#d1fa48]/30 transition-all duration-300">
                        <div class="relative aspect-square overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1548690312-e3b507d17a4d?auto=format&fit=crop&q=80&w=600"
                                alt="Coach"
                                class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500">
                            <div
                                class="absolute top-3 left-3 bg-[#111111]/80 backdrop-blur-md text-[#d1fa48] text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                Yoga & Pilates
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="text-white font-bold text-xl">Sarah Chen</h3>
                                <div class="flex items-center text-[#ff7a00]">
                                    <span class="text-sm font-bold mr-1">5.0</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                            <p class="text-gray-500 text-sm mb-4">2 miles away • Wellness Expert</p>
                            <button
                                class="w-full bg-[#d1fa48] hover:bg-[#b8e63a] text-[#111111] font-bold py-2 rounded-xl transition-colors">
                                View Profile
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    @endif
</body>

</html>