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
    <main class="flex-1 p-6 pt-24 lg:p-10 lg:ml-64">
        @php
            $resolveEquipmentImage = static function (?string $path): ?string {
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
        @endphp

        <div class="mb-7 border-b border-zinc-800/80 pb-5">
            <h2 class="text-xl font-bold text-white tracking-tight">System Data Overview</h2>
            <p class="text-zinc-400 text-sm mt-1">Select a resource category below to add, edit, or remove global taxonomies.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl flex flex-col h-100">
                <div class="p-5 border-b border-zinc-800/50 flex items-center justify-between shrink-0">
                    <h3 class="text-white font-bold text-lg flex items-center gap-3">
                       
                        Sports Categories
                    </h3>
                    <span class="bg-[#1c1c1c] text-zinc-400 text-xs font-bold px-3 py-1.5 rounded-lg border border-zinc-700">{{ $sportsCount }} Active</span>
                </div>

                <div class="flex-1 overflow-y-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden p-5 space-y-2.5">
                    @forelse ($recentSports as $sport)
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-zinc-300 font-medium text-sm flex items-center gap-2">
                                <i class="fa-solid {{ $sport->icon ?? 'fa-person-running' }} text-zinc-500"></i>
                                {{ $sport->title }}
                            </span>
                        </div>
                    @empty
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 text-zinc-500 text-sm">No sports available.</div>
                    @endforelse
                </div>

                <div class="p-5 pt-0 shrink-0">
                    <a href="{{ route('management.sports.index') }}" class="w-full bg-[#1c1c1c] border border-zinc-700 hover:bg-[#FBBF24] hover:text-black hover:border-[#FBBF24] text-white font-bold py-3 rounded-xl transition-colors flex justify-center items-center gap-2">
                        Manage Sports <i class="fa-solid fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>

            <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl flex flex-col h-100">
                <div class="p-5 border-b border-zinc-800/50 flex items-center justify-between shrink-0">
                    <h3 class="text-white font-bold text-lg flex items-center gap-3">
                       
                        Facility Services
                    </h3>
                    <span class="bg-[#1c1c1c] text-zinc-400 text-xs font-bold px-3 py-1.5 rounded-lg border border-zinc-700">{{ $servicesCount }} Active</span>
                </div>

                <div class="flex-1 overflow-y-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden p-5 space-y-2.5">
                    @forelse ($recentServices as $service)
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-zinc-300 font-medium text-sm">{{ $service->title }}</span>
                        </div>
                    @empty
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 text-zinc-500 text-sm">No services available.</div>
                    @endforelse
                </div>

                <div class="p-5 pt-0 shrink-0">
                    <a href="{{ route('management.services.index') }}" class="w-full bg-[#1c1c1c] border border-zinc-700 hover:bg-[#d1fa48] hover:text-black hover:border-[#d1fa48] text-white font-bold py-3 rounded-xl transition-colors flex justify-center items-center gap-2">
                        Manage Services <i class="fa-solid fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>

            <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl flex flex-col h-100">
                <div class="p-5 border-b border-zinc-800/50 flex items-center justify-between shrink-0">
                    <h3 class="text-white font-bold text-lg flex items-center gap-3">
                       
                        Coach Specialties
                    </h3>
                    <span class="bg-[#1c1c1c] text-zinc-400 text-xs font-bold px-3 py-1.5 rounded-lg border border-zinc-700">{{ $specialitiesCount }} Active</span>
                </div>

                <div class="flex-1 overflow-y-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden p-5 space-y-2.5">
                    @forelse ($recentSpecialities as $speciality)
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-zinc-300 font-medium text-sm">{{ $speciality->title }}</span>
                        </div>
                    @empty
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 text-zinc-500 text-sm">No specialities available.</div>
                    @endforelse
                </div>

                <div class="p-5 pt-0 shrink-0">
                    <a href="{{ route('management.specialities.index') }}" class="w-full bg-[#1c1c1c] border border-zinc-700 hover:bg-white hover:text-black hover:border-white text-white font-bold py-3 rounded-xl transition-colors flex justify-center items-center gap-2">
                        Manage Specialties <i class="fa-solid fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>

            <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl flex flex-col h-100">
                <div class="p-5 border-b border-zinc-800/50 flex items-center justify-between shrink-0">
                    <h3 class="text-white font-bold text-lg flex items-center gap-3">
                        
                        Equipment Inventory
                    </h3>
                    <span class="bg-[#1c1c1c] text-zinc-400 text-xs font-bold px-3 py-1.5 rounded-lg border border-zinc-700">{{ $equipmentsCount }} Active</span>
                </div>

                <div class="flex-1 overflow-y-auto [scrollbar-width:none] [-ms-overflow-style:none] [&::-webkit-scrollbar]:hidden p-5 space-y-2.5">
                    @forelse ($recentEquipments as $equipment)
                        @php
                            $equipmentImageUrl = $resolveEquipmentImage($equipment->image);
                            $equipmentCategory = $categoryLabels[$equipment->category] ?? ucfirst(str_replace('_', ' ', $equipment->category ?? 'general'));
                        @endphp
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-zinc-800 overflow-hidden shrink-0 border border-zinc-700 flex items-center justify-center text-zinc-600">
                                @if ($equipmentImageUrl)
                                    <img src="{{ $equipmentImageUrl }}" alt="{{ $equipment->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-image"></i>
                                @endif
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-zinc-300 font-medium text-sm truncate">{{ $equipment->name }}</span>
                                <span class="text-zinc-500 text-[10px] uppercase tracking-wider">{{ $equipmentCategory }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="bg-[#1c1c1c] border border-zinc-800/50 rounded-xl p-3.5 text-zinc-500 text-sm">No equipment available.</div>
                    @endforelse
                </div>

                <div class="p-5 pt-0 shrink-0">
                    <a href="{{ route('management.equipments.index') }}" class="w-full bg-[#1c1c1c] border border-zinc-700 hover:bg-[#ff5520] hover:text-white hover:border-[#ff5520] text-white font-bold py-3 rounded-xl transition-colors flex justify-center items-center gap-2">
                        Manage Equipment <i class="fa-solid fa-arrow-right text-sm"></i>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>