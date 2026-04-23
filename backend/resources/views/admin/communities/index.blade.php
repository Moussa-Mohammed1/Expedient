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

<body class="bg-black text-gray-300 min-h-screen overflow-x-hidden">
    @include('layouts.adminSidebar')
    <x-notification-popup />

    @php
        $editingCommunity = $editingCommunity ?? null;
        $isEditing = filled($editingCommunity);
        $formAction = $isEditing
            ? route('admin.communities.update', $editingCommunity)
            : route('admin.communities.store');

        $panelTitle = $isEditing ? 'Edit Community' : 'Create Community';
        $submitLabel = $isEditing ? 'Save Changes' : 'Create Community';

        $defaultCover = asset('assets/images/communities_default.jpeg');
        $currentTitle = old('title', $editingCommunity->title ?? '');
        $currentDescription = old('description', $editingCommunity->description ?? '');
        $currentLocalisation = old('localisation', $editingCommunity->localisation ?? '');

        $currentCover = $defaultCover;

        if ($isEditing && filled($editingCommunity->backgroundImage)) {
            $currentCover = asset('storage/' . ltrim($editingCommunity->backgroundImage, '/'));
        }
    @endphp

    <main class="flex-1 p-6 pt-24 lg:p-10 lg:ml-64">
        <div class="mb-8">
            <a href="{{ url('/admin/dashboard') }}"
                class="inline-flex items-center text-xs font-bold text-zinc-500 hover:text-white transition-colors uppercase tracking-wider mb-3">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
            <div class="flex items-center gap-3">
             
                    <h2 class="text-2xl font-bold text-white tracking-tight">Manage Communities</h2>
                    <p class="text-zinc-400 text-sm mt-0.5">Search, filter, and maintain local communities from one
                        place.</p>
               
            </div>
        </div>

        <div class="flex flex-col xl:flex-row gap-6">
            <div class="w-full xl:w-2/3">
                <form method="GET" action="{{ route('admin.communities') }}"
                    class="bg-[#111111] border border-zinc-800/80 rounded-t-lg p-4 flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                        </div>
                        <input type="text" name="q" value="{{ $search ?? '' }}"
                            placeholder="Search community title or description..."
                            class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-lg pl-11 pr-4 py-2.5 focus:outline-none focus:border-[#06b6d4] focus:ring-1 focus:ring-[#06b6d4] transition-colors">
                    </div>

                    <div class="flex items-center gap-2">
                        <div
                            class="rounded-lg flex items-center gap-2 w-full md:w-auto">
                            <i class="fa-solid fa-location-dot text-zinc-500"></i>
                            <select name="localisation"
                                class="bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-[#FBBF24] appearance-none cursor-pointer">
                                <option value="all" @selected(($localisation ?? 'all') === 'all')>All Locations</option>
                                @foreach ($localisationOptions ?? collect() as $option)
                                    <option value="{{ $option }}" @selected(($localisation ?? 'all') === $option)>{{ $option }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="bg-[#222222] border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500 px-4 py-2.5 rounded-lg text-sm font-bold transition-colors">
                            Apply
                        </button>
                    </div>
                </form>

                <div class="bg-[#111111] border-x border-b border-zinc-800/80 rounded-b-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-190 text-left border-collapse">
                            <thead>
                                <tr
                                    class="bg-[#1c1c1c] border-y border-zinc-800 text-zinc-500 text-[10px] uppercase tracking-wider">
                                    <th class="px-6 py-4 font-bold">Community & Cover</th>
                                    <th class="px-6 py-4 font-bold">Localisation</th>
                                    <th class="px-6 py-4 font-bold">Members</th>
                                    <th class="px-6 py-4 font-bold">Created Date</th>
                                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-800/50 text-sm">
                                @forelse ($communities as $community)
                                    @php
                                        $isSelected = $isEditing && $editingCommunity->id === $community->id;
                                        $coverImage = $community->backgroundImage
                                            ? asset('storage/' . ltrim($community->backgroundImage, '/'))
                                            : $defaultCover;
                                    @endphp
                                    <tr
                                        class="hover:bg-[#1c1c1c]/50 transition-colors group {{ $isSelected ? 'bg-[#06b6d4]/5' : '' }}">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="w-16 h-10 rounded-lg bg-zinc-800 overflow-hidden shrink-0 border {{ $isSelected ? 'border-[#06b6d4]/50' : 'border-zinc-700' }} relative">
                                                    <img src="{{ $coverImage }}" class="w-full h-full object-cover"
                                                        alt="{{ $community->title }}">
                                                </div>
                                                <div class="flex flex-col min-w-0">
                                                    <span class="font-bold text-white flex items-center gap-2 truncate">
                                                        {{ $community->title }}
                                                        @if ($isSelected)
                                                            <span
                                                                class="bg-[#06b6d4] text-black text-[9px] px-1.5 py-0.5 rounded-lg uppercase tracking-wider font-bold">Editing</span>
                                                        @endif
                                                    </span>
                                                    <span class="text-zinc-500 text-xs line-clamp-1 max-w-65">
                                                        {{ $community->description ?: 'No description yet.' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="bg-[#222222] border border-zinc-700 text-zinc-300 text-xs px-2.5 py-1 rounded-lg flex items-center w-fit gap-1.5">
                                                <i class="fa-solid fa-map-pin text-[#06b6d4]"></i>
                                                {{ $community->localisation ?: 'Not set' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-zinc-300 font-medium">
                                            {{ number_format($community->active_members_count ?? 0) }}
                                        </td>
                                        <td class="px-6 py-4 text-zinc-500 text-xs">
                                            {{ optional($community->created_at)->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div
                                                class="flex items-center justify-end gap-2 {{ $isSelected ? '' : 'opacity-0 group-hover:opacity-100' }} transition-opacity">
                                                <a href="{{ route('admin.communities', ['edit' => $community->id, 'q' => $search, 'localisation' => $localisation]) }}"
                                                    class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-[#06b6d4] transition-colors flex items-center justify-center"
                                                    title="Edit">
                                                    <i class="fa-solid fa-pen text-xs"></i>
                                                </a>
                                                <form action="{{ route('admin.communities.destroy', $community) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-[#ff5520] hover:bg-[#ff5520] hover:text-white hover:border-[#ff5520] transition-colors flex items-center justify-center"
                                                        title="Delete">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-zinc-500">
                                            No communities found for your current filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-between">
                        <span class="text-xs text-zinc-500 font-medium">
                            @if ($communities->total() > 0)
                                Showing {{ $communities->firstItem() }} to {{ $communities->lastItem() }} of
                                {{ $communities->total() }} communities
                            @else
                                Showing 0 communities
                            @endif
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ $communities->previousPageUrl() ?: '#' }}"
                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white transition-colors flex items-center justify-center {{ $communities->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </a>
                            <a href="{{ $communities->nextPageUrl() ?: '#' }}"
                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white transition-colors flex items-center justify-center {{ $communities->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full xl:w-1/3">
                <div class="sticky top-6">
                    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data"
                        class="bg-[#111111] border border-zinc-800/80 rounded-lg overflow-hidden shadow-2xl relative">
                        @csrf
                        @if ($isEditing)
                            @method('PUT')
                        @endif

                        <div class="bg-[#06b6d4]/10 border-b border-[#06b6d4]/20 p-5 flex items-center justify-between">
                            <h3 class="text-[#06b6d4] font-bold text-lg flex items-center gap-2">
                                {{ $panelTitle }}
                            </h3>
                            @if ($isEditing)
                                <a href="{{ route('admin.communities', ['q' => $search, 'localisation' => $localisation]) }}"
                                    class="text-zinc-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
                                    Cancel
                                </a>
                            @endif
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Cover
                                    Background</label>

                                <div class="w-full h-36 rounded-lg bg-[#1c1c1c] border border-zinc-700 overflow-hidden">
                                    <img src="{{ $currentCover }}" class="w-full h-full object-cover" alt="Current cover">
                                </div>

                                <input type="file" name="backgroundImage" accept="image/*"
                                    class="mt-3 w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-3 py-2.5 text-white text-sm file:bg-[#222222] file:text-zinc-300 file:border-0 file:rounded-lg file:px-3 file:py-1.5 file:mr-3">

                                @if ($isEditing && filled($editingCommunity->backgroundImage))
                                    <label class="mt-3 inline-flex items-center gap-2 text-xs text-zinc-400">
                                        <input type="checkbox" name="remove_background" value="1"
                                            class="rounded-lg border-zinc-600 bg-[#1c1c1c] text-[#06b6d4] focus:ring-[#06b6d4]">
                                        Remove existing cover image
                                    </label>
                                @endif
                            </div>

                            <div>
                                <label for="title"
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Community
                                    Title <span class="text-[#06b6d4]">*</span></label>
                                <input type="text" id="title" name="title" value="{{ $currentTitle }}" required
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-[#06b6d4] focus:ring-1 focus:ring-[#06b6d4]">
                            </div>

                            <div>
                                <label for="localisation"
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Localisation</label>
                                <input type="text" id="localisation" name="localisation"
                                    value="{{ $currentLocalisation }}" placeholder="e.g. Safi"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-[#06b6d4] focus:ring-1 focus:ring-[#06b6d4]">
                            </div>

                            <div>
                                <label for="description"
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-[#06b6d4] resize-none">{{ $currentDescription }}</textarea>
                            </div>
                        </div>

                        <div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full bg-[#06b6d4] hover:bg-[#0891b2] text-black text-sm font-bold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> {{ $submitLabel }}
                            </button>
                            @if ($isEditing)
                                <a href="{{ route('admin.communities', ['q' => $search, 'localisation' => $localisation]) }}"
                                    class="w-full bg-transparent border border-dashed border-zinc-600 text-zinc-400 hover:text-white hover:border-zinc-400 text-sm font-bold py-3 rounded-lg transition-colors text-center">
                                    + Create New Community
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
