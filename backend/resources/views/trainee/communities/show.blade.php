<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 font-sans antialiased min-h-screen">
    @include('layouts.header')
    <x-notification-popup/>
    @php
        $defaultCommunityImage = asset('assets/images/communities_default.jpeg');
        $coverImage = $community->backgroundImage
            ? asset('storage/' . ltrim($community->backgroundImage, '/'))
            : $defaultCommunityImage;
    @endphp

    <div class="max-w-300 mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <a href="{{ route('communities.index') }}"
            class="inline-flex items-center text-sm font-medium text-zinc-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Hub
        </a>
    </div>

    <div class="max-w-300 mx-auto px-4 sm:px-6 lg:px-8 pb-16">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 mb-8">
            <div class="lg:col-span-2">
                <div class="w-full h-48 sm:h-64 rounded-2xl overflow-hidden relative border border-zinc-800">
                    <img src="{{ $coverImage }}" alt="{{ $community->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-linear-to-t from-[#111111] via-black/60 to-transparent"></div>

                    <div
                        class="absolute bottom-0 left-0 w-full p-6 sm:p-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                        <div>
                            <h1 class="text-3xl sm:text-4xl font-bold text-white tracking-tight mb-2">
                                {{ $community->title }}</h1>
                            <p class="text-zinc-300 text-sm sm:text-base max-w-2xl">
                                {{ $community->description }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span
                                class="bg-black/60 backdrop-blur-md border border-zinc-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl flex items-center gap-2">
                                <i class="fa-solid fa-user-group text-zinc-400"></i> {{ $community->users->count() }}
                                Members
                            </span>
                            @if ($isMember)
                                <form action="{{ route('communities.leave', $community) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-800 text-white font-bold px-6 py-2.5 rounded-lg transition-colors hover:bg-yellow-600">
                                        Leave
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('communities.join', $community) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-[#ff5520] text-white font-bold px-6 py-2.5 rounded-lg transition-colors hover:bg-[#ff6f42]">
                                        Join
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-6 h-full">
                    <h3 class="text-white font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-zinc-500"></i> About Community
                    </h3>
                    <p class="text-sm text-zinc-400 mb-5">
                        {{ $community->description }}
                    </p>

                    <div class="space-y-3 pt-4 border-t border-zinc-800/50">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Created</span>
                            <span
                                class="text-zinc-300 font-medium">{{ optional($community->created_at)->format('M d, Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-zinc-500">Privacy</span>
                            <span class="text-zinc-300 font-medium flex items-center gap-1.5"><i
                                    class="fa-solid fa-earth-africa text-zinc-500"></i> Public</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            @php
                $currentUserAvatar = auth()->user()?->avatar
                    ? asset('storage/users/profiles/' . ltrim(auth()->user()->avatar, '/'))
                    : asset('assets/images/profile.jpeg');
            @endphp

            <a href="{{ route('posts.create', ['community' => $community->id]) }}"
                class="block bg-[#111111] border border-zinc-800/80 rounded-2xl p-4 sm:p-5 hover:border-zinc-700 transition-colors">
                <div class="flex items-center gap-3">
                    <img src="{{ $currentUserAvatar }}" alt="Current user"
                        class="w-10 h-10 rounded-full border border-zinc-700 object-cover">
                    <div
                        class="flex-1 rounded-full bg-[#1c1c1c] border border-zinc-700 px-4 py-2.5 text-sm text-zinc-500 hover:text-zinc-300 transition-colors">
                        What do you think today?
                    </div>
                    <i class="fa-solid fa-pen-to-square text-zinc-500"></i>
                </div>
            </a>

            @forelse ($posts as $post)
                @php
                    $authorAvatar = $post->user?->avatar
                        ? asset('storage/users/profiles/' . ltrim($post->user->avatar, '/'))
                        : asset('assets/images/profile.jpeg');
                    $isLikedByCurrentUser = $post->likes->contains('user_id', auth()->id());
                @endphp

                <div
                    class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-5 sm:p-6 hover:border-zinc-700 transition-colors">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $authorAvatar }}" alt="{{ $post->user?->name ?? 'Author' }}"
                                class="w-10 h-10 rounded-full border border-zinc-700 object-cover">
                            <div>
                                <h4 class="text-white font-bold text-sm">{{ $post->user?->name ?? 'Unknown' }}</h4>
                                <span
                                    class="text-xs text-zinc-500">{{ optional($post->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>

                        <div class="relative group">
                            <button class="text-zinc-500 hover:text-white p-2 rounded-full outline-none cursor-pointer">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <div
                                class="absolute right-0 mt-1 w-32 bg-[#1c1c1c] border border-zinc-700 rounded-xl shadow-2xl invisible opacity-0 group-focus-within:visible group-focus-within:opacity-100 z-10 overflow-hidden">
                                <ul class="py-1 text-sm text-zinc-300">
                                    <li><button  class="open-report-modal block px-4 py-2 hover:bg-zinc-800 transition-colors">Report</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <p class="text-zinc-300 text-sm  mb-4 whitespace-pre-line">{{ $post->content }}</p>

                    @if ($post->images->isNotEmpty())
                        <div class="grid grid-cols-2 gap-2 mb-5 rounded-xl overflow-hidden border border-zinc-800">
                            @foreach ($post->images as $image)
                                <img src="{{ asset('storage/' . ltrim($image->content, '/')) }}" alt="Post image"
                                    class="w-full h-48 object-cover hover:opacity-90 transition-opacity cursor-pointer">
                            @endforeach
                        </div>
                    @endif

                    <div class="flex items-center gap-6 pt-4 border-t border-zinc-800/50">
                        <form action="{{ route('posts.like', $post) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 transition-colors group {{ $isLikedByCurrentUser ? 'text-[#ff5520]' : 'text-zinc-400 hover:text-[#ff5520]' }}">
                                <i class="fa-solid fa-fire transition-transform"></i>
                                <span class="text-sm font-medium">{{ $post->likes->count() }}</span>
                            </button>
                        </form>
                        <a href="{{ route('posts.show', ['post' => $post, 'community' => $community->id]) }}"
                            class="flex items-center gap-2 text-zinc-400 hover:text-white transition-colors">
                            <i class="fa-regular fa-comment"></i>
                            <span class="text-sm font-medium">{{ $post->comments->count() }}</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-8 text-center">
                    <p class="text-zinc-400 text-sm">No posts in this community yet.</p>
                </div>
            @endforelse

            <div>
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@include('coach.opinions.partials.report-modal')
</body>

</html>