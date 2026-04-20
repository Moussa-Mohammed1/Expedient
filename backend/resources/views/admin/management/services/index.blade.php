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
        $editingService = $editingService ?? null;
        $isEditing = filled($editingService);
        $formAction = $isEditing
            ? route('management.services.update', $editingService)
            : route('management.services.store');
        $panelTitle = $isEditing ? 'Edit Service' : 'Add Service';
        $submitLabel = $isEditing ? 'Save Changes' : 'Create Service';
        $helpText = $isEditing
            ? 'Changing this name updates the label used by every salle linked to this service.'
            : 'Create a new amenity that can be assigned to salles.';
        $currentTitle = old('title', $editingService->title ?? '');
    @endphp

    <main class="flex-1 p-6 pt-24 lg:p-10 lg:ml-64">
        <div class="mb-8">
            <a href="{{ url('/admin/management') }}"
                class="inline-flex items-center text-xs font-bold text-zinc-500 hover:text-white transition-colors uppercase tracking-wider mb-3">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Overview
            </a>
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Manage Services</h2>
                    <p class="text-zinc-400 text-sm mt-0.5">Add, update, or remove amenities that facilities can offer
                        to users.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col xl:flex-row gap-6">
            <div class="w-full xl:w-2/3">
                <form method="GET" action="{{ route('management.services.index') }}"
                    class="bg-[#111111] border border-zinc-800/80 rounded-t-lg p-4 flex gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                        </div>
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search services..."
                            class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-lg pl-11 pr-4 py-2.5 focus:outline-none focus:border-[#d1fa48] focus:ring-1 focus:ring-[#d1fa48] transition-colors">
                    </div>
                    <button type="submit"
                        class="bg-[#222222] border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500 px-4 py-2.5 rounded-lg text-sm font-bold transition-colors">
                        Search
                    </button>
                </form>

                <div class="bg-[#111111] border-x border-b border-zinc-800/80 rounded-b-lg ">
                    <div class="overflow-x-auto [scrollbar-width:thin]">
                    <table class="w-full min-w-190 text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-[#1c1c1c] border-y border-zinc-800 text-zinc-500 text-[10px] uppercase ">
                                <th class="px-6 py-4 font-bold">ID</th>
                                <th class="px-6 py-4 font-bold">Service Name</th>
                                <th class="px-6 py-4 font-bold">Facilities Offering</th>
                                <th class="px-6 py-4 font-bold">Added Date</th>
                                <th class="px-6 py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50 text-sm">
                            @forelse ($services as $service)
                                @php
                                    $isSelected = $isEditing && $editingService->id === $service->id;
                                @endphp
                                <tr
                                    class="group hover:bg-[#1c1c1c]/50 transition-colors {{ $isSelected ? 'bg-[#d1fa48]/5' : '' }}">
                                    <td
                                        class="px-6 py-4 text-zinc-500 font-mono text-xs {{ $isSelected ? 'text-[#d1fa48]' : '' }}">
                                        #{{ $service->id }}</td>
                                    <td class="px-6 py-4 font-bold text-white flex items-center gap-2">
                                        <span>{{ $service->title }}</span>
                                        @if ($isSelected)
                                            <span
                                                class="bg-[#d1fa48] text-black text-[9px] px-1.5 py-0.5 rounded uppercase tracking-wider font-bold">Editing</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-[#222222] border border-zinc-700 text-zinc-300 text-xs px-2 py-1 rounded-lg">
                                            {{ $service->salles_count }} Salles
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-500 text-xs">
                                        {{ optional($service->created_at)->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('management.services.index', ['edit' => $service->id, 'q' => $search]) }}"
                                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-[#d1fa48] transition-colors flex items-center justify-center tooltip"
                                                title="Edit">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('management.services.destroy', $service) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-[#ff5520] hover:bg-[#ff5520] hover:text-white hover:border-[#ff5520] transition-colors flex items-center justify-center tooltip"
                                                    title="Delete">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-zinc-500">No services found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>

                    <div class="p-4 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-between">
                        <span class="text-xs text-zinc-500 font-medium">
                            @if ($services->total() > 0)
                                Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of
                                {{ $services->total() }} records
                            @else
                                Showing 0 records
                            @endif
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ $services->previousPageUrl() ?: '#' }}"
                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white transition-colors flex items-center justify-center {{ $services->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </a>
                            <a href="{{ $services->nextPageUrl() ?: '#' }}"
                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white transition-colors flex items-center justify-center {{ $services->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full xl:w-1/3">
                <div class="sticky top-6">
                    <form action="{{ $formAction }}" method="POST"
                        class="bg-[#111111] border border-zinc-800/80 rounded-lg overflow-hidden shadow-2xl relative">
                        @csrf
                        @if ($isEditing)
                            @method('PUT')
                        @endif

                        <div class="bg-[#d1fa48]/10 border-b border-[#d1fa48]/20 p-5 flex items-center justify-between">
                            <h3 class="text-[#d1fa48] font-bold text-lg flex items-center gap-2">
                                <i class="fa-solid fa-pen-to-square"></i> {{ $panelTitle }}
                            </h3>
                            @if ($isEditing)
                                <a href="{{ route('management.services.index') }}"
                                    class="text-zinc-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
                                    Cancel
                                </a>
                            @endif
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <label for="title"
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Service
                                    Name <span class="text-[#d1fa48]">*</span></label>
                                <input type="text" id="title" name="title" value="{{ $currentTitle }}" required
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-[#d1fa48] focus:ring-1 focus:ring-[#d1fa48]">
                            </div>

                            <div class="bg-[#1c1c1c] border border-zinc-800 rounded-lg p-4">
                                <p class="text-xs text-zinc-400 leading-relaxed">
                                    <i class="fa-solid fa-circle-info text-zinc-500 mr-1"></i>
                                    {{ $helpText }}
                                </p>
                            </div>
                        </div>

                        <div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full bg-[#d1fa48] hover:bg-[#b4d83d] text-black text-sm font-bold py-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> {{ $submitLabel }}
                            </button>
                            <a href="{{ route('management.services.index') }}"
                                class="w-full bg-transparent border  border-zinc-600 text-zinc-400 hover:text-white hover:border-zinc-400 text-sm font-bold py-3 rounded-lg transition-colors text-center">
                                + Switch to Add New Service
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>

</html>