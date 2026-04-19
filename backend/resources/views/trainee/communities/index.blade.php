<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Communities</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 font-sans antialiased min-h-screen">
    @include('layouts.header')
    <div class="max-w-350 mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">

        <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6 border-b border-zinc-800 pb-6">
            <div>
                <h1 class="text-3xl lg:text-4xl font-bold text-white tracking-tight mb-2">Community Hub</h1>
                <p class="text-zinc-400 text-sm lg:text-base flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-white"></i> Showing active groups in <span
                        class="text-white font-medium">{{ auth()->user()?->localisation }}</span>
                </p>
            </div>

            <form action="{{ route('communities.index') }}" method="GET" class="w-full md:w-auto flex gap-2">
                <div class="relative flex-1 md:flex-initial md:w-72">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                    </div>
                    <input type="text" name="search" placeholder="Find a community..."
                        value="{{ old('search', $searchQuery ?? '') }}"
                        class="w-full bg-[#111111] border border-zinc-700 text-white text-sm rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] transition-colors">
                </div>
                <button type="submit" 
                    class="bg-yellow-500 hover:bg-yellow-600 text-black font-semibold py-2.5 px-4 rounded-full transition-colors text-sm whitespace-nowrap">
                    Search
                </button>
                @if ($searchQuery)
                    <a href="{{ route('communities.index') }}"
                        class="bg-zinc-700 hover:bg-zinc-600 text-white font-semibold py-2.5 px-4 rounded-xl transition-colors text-sm whitespace-nowrap">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <div class="mb-12">
            <h2 class="text-xl font-bold text-yellow-500 mb-5 flex items-center gap-2">
                My Communities
            </h2>

            <div
                class="flex overflow-x-auto lg:grid lg:grid-cols-3 gap-4 lg:gap-6 hide-scrollbar pb-4 -mx-4 px-4 lg:mx-0 lg:px-0 lg:pb-0">
                @forelse ($joinedCommunities as $community)
                    @php
                        $defaultCommunityImage = asset('assets/images/communities_default.jpeg');
                        $coverImage = $community->backgroundImage
                            ? asset('storage/' . ltrim($community->backgroundImage, '/'))
                            : $defaultCommunityImage;
                    @endphp

                    <a href="{{ route('communities.show', $community) }}"
                        class="min-w-70 lg:min-w-0 bg-[#111111] border border-zinc-800/80 rounded-lg overflow-hidden hover:border-zinc-600 transition-all group shrink-0">
                        <div class="h-24 relative overflow-hidden bg-[#1c1c1c]">
                            <img src="{{ $coverImage }}" alt="{{ $community->title }}"
                                class="w-full h-full object-cover transition-transform duration-500 opacity-60">
                            <div class="absolute inset-0 bg-linear-to-t from-[#111111] to-transparent"></div>
                            <div
                                class="absolute top-3 right-3 bg-zinc-800/80 border border-zinc-600 text-zinc-300 text-[10px] font-bold px-2 py-0.5 rounded uppercase ">
                                Member
                            </div>
                        </div>
                        <div class="p-5 pt-2">
                            <h3 class="text-lg font-bold text-white truncate mb-1">{{ $community->title }}</h3>
                            <p class="text-xs text-zinc-500 mb-4 flex items-center gap-1.5">
                                <i class="fa-solid fa-user-group"></i> {{ $community->active_members_count }} Members
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-[#111111] border border-zinc-800/80 rounded-lg p-8 text-center">
                        <p class="text-zinc-400 text-sm">No communities available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-5 border-b border-zinc-800/50 pb-2">
                <h2 class="text-xl font-bold text-yellow-500 flex items-center gap-2">
                    @if ($searchQuery)
                        Search Results for "{{ $searchQuery }}"
                    @else
                        Discover Local Groups
                    @endif
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @forelse ($communities as $community)
                    <a href="{{ route('communities.show', $community) }}"
                        class="bg-[#111111] border border-zinc-800/80 rounded-lg overflow-hidden hover:border-zinc-600 transition-colors group flex flex-col">
                        <div class="h-32 relative overflow-hidden bg-[#1c1c1c]">
                            <img src="{{ $community->backgroundImage ? asset('storage/' . ltrim($community->backgroundImage, '/')) : asset('assets/images/communities_default.jpeg') }}"
                                alt="{{ $community->title }}"
                                class="w-full h-full object-cover transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div
                                class="absolute top-2 left-2 bg-black/60 backdrop-blur-md border border-zinc-700 text-[#d1fa48] text-[10px] font-bold px-2 py-0.5 rounded-md uppercase">
                                {{ in_array($community->id, $joinedCommunityIds, true) ? 'Joined' : 'Community' }}
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-1">
                            <h3 class="text-lg font-bold text-white leading-tight mb-1 truncate">{{ $community->title }}
                            </h3>
                            <p class="text-xs text-zinc-500 mb-3 flex items-center gap-1.5">
                                <i class="fa-solid fa-user-group text-zinc-600"></i> {{ $community->active_members_count }}
                                Members
                            </p>
                            <p class="text-sm text-zinc-400 line-clamp-2 mb-5">
                                {{ $community->description ?: 'No desription yet'}}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full bg-[#111111] border border-zinc-800/80 rounded-lg p-8 text-center">
                        <p class="text-zinc-400 text-sm">
                            @if ($searchQuery)
                                No communities found matching "{{ $searchQuery }}". Try a different search term.
                            @else
                                No communities available in your area yet.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</body>

</html>