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
        $editingEquipment = $editingEquipment ?? null;
        $isEditing = filled($editingEquipment);
        $formAction = $isEditing
            ? route('management.equipments.update', $editingEquipment)
            : route('management.equipments.store');
        $panelTitle = $isEditing ? 'Edit Equipment' : 'Add Equipment';
        $submitLabel = $isEditing ? 'Save Changes' : 'Create Equipment';
        $helpText = $isEditing
            ? 'Updating this item changes the catalog entry used across all linked salles.'
            : 'Create a new equipment entry that can be used throughout the facility catalog.';
        $currentName = old('name', $editingEquipment->name ?? '');
        $currentCategory = old('category', $editingEquipment->category ?? 'cardio');
        $currentImagePath = $editingEquipment->image ?? null;

        $resolveEquipmentImage = function (?string $path): ?string {
            if (!$path) {
                return null;
            }

            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            $normalizedPath = ltrim($path, '/');

            if (str_starts_with($normalizedPath, 'assets/') || str_starts_with($normalizedPath, 'storage/')) {
                return asset($normalizedPath);
            }

            return asset('storage/' . $normalizedPath);
        };
        $currentImageUrl = $resolveEquipmentImage($currentImagePath);
        $categoryLabels = $categoryOptions ?? [];
    @endphp

    <main class="flex-1 p-6 pt-24 lg:p-10 lg:ml-64">

        <div class="mb-8">
            <a href="{{ url('/admin/management') }}"
                class="inline-flex items-center text-xs font-bold text-zinc-500 hover:text-white transition-colors uppercase tracking-wider mb-3">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Overview
            </a>
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-2xl font-bold text-white tracking-tight">Manage Equipment</h2>
                    <p class="text-zinc-400 text-sm mt-0.5">Manage the global dictionary of machines, free weights, and
                        accessories.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col xl:flex-row gap-6">

            <div class="w-full xl:w-2/3">

                <form method="GET" action="{{ route('management.equipments.index') }}"
                    class="bg-[#111111] border border-zinc-800/80 rounded-t-2xl p-4 flex gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-zinc-500"></i>
                        </div>
                        <input type="text" name="q" value="{{ $search ?? '' }}"
                            placeholder="Search equipment by name or category..."
                            class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl pl-11 pr-4 py-2.5 focus:outline-none focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] transition-colors">
                    </div>
                    <button type="submit"
                        class="bg-[#222222] border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500 px-4 py-2.5 rounded-xl text-sm font-bold transition-colors">
                        Search
                    </button>
                </form>

                <div class="bg-[#111111] border-x border-b border-zinc-800/80 rounded-b-2xl overflow-hidden">
                    <div class="overflow-x-auto [scrollbar-width:thin]">
                    <table class="w-full min-w-225 text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-[#1c1c1c] border-y border-zinc-800 text-zinc-500 text-[10px] uppercase tracking-wider">
                                <th class="px-6 py-4 font-bold">ID</th>
                                <th class="px-6 py-4 font-bold">Equipment Details</th>
                                <th class="px-6 py-4 font-bold">Category</th>
                                <th class="px-6 py-4 font-bold">Salles Using</th>
                                <th class="px-6 py-4 font-bold">Added Date</th>
                                <th class="px-6 py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/50 text-sm">
                            @forelse ($equipments as $equipment)
                                @php
                                    $isSelected = $isEditing && $editingEquipment->id === $equipment->id;
                                    $equipmentImageUrl = $resolveEquipmentImage($equipment->image);
                                    $categoryLabel = $categoryLabels[$equipment->category] ?? ucfirst(str_replace('_', ' ', $equipment->category ?? 'general'));
                                @endphp
                                <tr
                                    class="hover:bg-[#1c1c1c]/50 transition-colors group {{ $isSelected ? 'bg-[#ff5520]/5' : '' }}">
                                    <td
                                        class="px-6 py-4 text-zinc-500 font-mono text-xs {{ $isSelected ? 'text-[#ff5520]' : '' }}">
                                        #{{ $equipment->id }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-zinc-800 overflow-hidden shrink-0 border border-zinc-700">
                                                @if ($equipmentImageUrl)
                                                    <img src="{{ $equipmentImageUrl }}" alt="{{ $equipment->name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-zinc-600">
                                                        <i class="fa-solid fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-bold text-white flex items-center gap-2">
                                                    {{ $equipment->name }}
                                                    @if ($isSelected)
                                                        <span
                                                            class="bg-[#ff5520] text-white text-[9px] px-1.5 py-0.5 rounded uppercase tracking-wider font-bold">Editing</span>
                                                    @endif
                                                </span>
                                                <span class="text-zinc-500 font-mono text-[10px] uppercase tracking-wider">
                                                    ID: #EQ-{{ str_pad((string) $equipment->id, 3, '0', STR_PAD_LEFT) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-[#1c1c1c] border border-zinc-700 text-zinc-400 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">
                                            {{ $categoryLabel }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-[#222222] border border-zinc-700 text-zinc-300 text-xs px-2 py-1 rounded">
                                            {{ $equipment->salles_count }} Salles
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-zinc-500 text-xs">
                                        {{ optional($equipment->created_at)->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div
                                            class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('management.equipments.index', ['edit' => $equipment->id, 'q' => $search]) }}"
                                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white hover:border-[#ff5520] transition-colors flex items-center justify-center tooltip"
                                                title="Edit">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('management.equipments.destroy', $equipment) }}"
                                                method="POST">
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
                                    <td colspan="6" class="px-6 py-10 text-center text-zinc-500">No equipments found.</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                    </div>

                    <div class="p-4 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-between">
                        <span class="text-xs text-zinc-500 font-medium">
                            @if ($equipments->total() > 0)
                                Showing {{ $equipments->firstItem() }} to {{ $equipments->lastItem() }} of
                                {{ $equipments->total() }} records
                            @else
                                Showing 0 records
                            @endif
                        </span>
                        <div class="flex items-center gap-2">
                            <a href="{{ $equipments->previousPageUrl() ?: '#' }}"
                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white transition-colors flex items-center justify-center {{ $equipments->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </a>
                            <a href="{{ $equipments->nextPageUrl() ?: '#' }}"
                                class="w-8 h-8 rounded-lg bg-[#222222] border border-zinc-700 text-zinc-400 hover:text-white transition-colors flex items-center justify-center {{ $equipments->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full xl:w-1/3">
                <div class="sticky top-6">

                    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data"
                        class="bg-[#111111] border border-zinc-800/80 rounded-2xl overflow-hidden shadow-2xl relative">
                        @csrf
                        @if ($isEditing)
                            @method('PUT')
                        @endif

                        <div class="bg-[#ff5520]/10 border-b border-[#ff5520]/20 p-5 flex items-center justify-between">
                            <h3 class="text-[#ff5520] font-bold text-lg flex items-center gap-2">
                              {{ $panelTitle }}
                            </h3>
                            @if ($isEditing)
                                <a href="{{ route('management.equipments.index') }}"
                                    class="text-zinc-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
                                    Cancel
                                </a>
                            @else
                                <span class="text-zinc-500 text-xs font-bold uppercase tracking-wider">Create mode</span>
                            @endif
                        </div>

                        <div class="p-6 space-y-6">

                            <input type="hidden" name="id" value="{{ $editingEquipment->id ?? '' }}">

                            <div>
                                <label
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Display
                                    Image</label>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-16 h-16 rounded-xl bg-[#1c1c1c] border-2 border-zinc-700 overflow-hidden shrink-0 flex items-center justify-center text-zinc-600">
                                        <img id="equipment-preview-image" src="{{ $currentImageUrl ?? '' }}"
                                            alt="Equipment preview"
                                            class="{{ $currentImageUrl ? '' : 'hidden' }} w-full h-full object-cover">
                                    </div>
                                    <label
                                        class="bg-[#1c1c1c] border border-zinc-700 hover:bg-zinc-800 text-zinc-300 text-xs font-bold px-4 py-2.5 rounded-lg cursor-pointer transition-colors flex items-center gap-2">
                                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload Image
                                        <input id="equipment-image-input" type="file" name="image" class="hidden"
                                            accept="image/*">
                                    </label>
                                </div>
                                @error('image')
                                    <p class="mt-2 text-xs text-[#ff5520]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="name"
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Equipment
                                    Name <span class="text-[#ff5520]">*</span></label>
                                <input type="text" id="name" name="name" value="{{ $currentName }}" required
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520]">
                                @error('name')
                                    <p class="mt-2 text-xs text-[#ff5520]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category"
                                    class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Category
                                    <span class="text-[#ff5520]">*</span></label>
                                <select id="category" name="category"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#ff5520] appearance-none cursor-pointer">
                                    @foreach ($categoryLabels as $categoryKey => $categoryLabel)
                                        <option value="{{ $categoryKey }}" @selected($currentCategory === $categoryKey)>
                                            {{ $categoryLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-2 text-xs text-[#ff5520]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-[#1c1c1c] border border-zinc-800 rounded-xl p-4">
                                <p class="text-xs text-zinc-400 leading-relaxed">
                                    <i class="fa-solid fa-circle-info text-zinc-500 mr-1"></i>
                                    {{ $helpText }}
                                </p>
                            </div>

                        </div>

                        <div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full bg-[#ff5520] hover:bg-[#ff7a00] text-white text-sm font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> {{ $submitLabel }}
                            </button>
                            <a href="{{ route('management.equipments.index') }}"
                                class="w-full bg-transparent text-center border border-zinc-600 text-zinc-400 hover:text-white hover:border-zinc-400 text-sm font-bold py-3 rounded-xl transition-colors">
                                + Switch to Add New Equipment
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </main>

    <script>
        const equipmentImageInput = document.getElementById('equipment-image-input');
        const equipmentPreviewImage = document.getElementById('equipment-preview-image');

        if (equipmentImageInput && equipmentPreviewImage) {
            equipmentImageInput.addEventListener('change', () => {
                const file = equipmentImageInput.files && equipmentImageInput.files[0];

                if (!file) {
                    return;
                }

                const previewUrl = URL.createObjectURL(file);
                equipmentPreviewImage.src = previewUrl;
                equipmentPreviewImage.classList.remove('hidden');
            });
        }
    </script>
</body>
</html>