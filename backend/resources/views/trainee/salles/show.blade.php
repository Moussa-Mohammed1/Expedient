<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Salles</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 min-h-screen">
    @include('layouts.header')

    @php
        $defaultBackgroundImage = asset('assets/images/salle_default.jpeg');
        $defaultLogoImage = asset('assets/images/salle_logo_default.jpeg');

        $resolveImageUrl = function (?string $path, string $fallbackUrl): string {
            if (!$path) {
                return $fallbackUrl;
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

        $coverImage = $resolveImageUrl($salle->background, $defaultBackgroundImage);
        $logoImage = $resolveImageUrl($salle->logo, $defaultLogoImage);

        $coachName = $salle->coach?->user?->name ?: 'Assigned coach';
        $dayOrder = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $horairesByDay = $salle->horaires->keyBy('day');
    @endphp

    <div class="bg-[#111111] border-b border-zinc-800">
        <div class="h-64 sm:h-80 w-full bg-[#1c1c1c]">
            <img src="{{ $coverImage }}" alt="{{ $salle->name }} cover"
                onerror="this.onerror=null;this.src='{{ $defaultBackgroundImage }}';"
                class="w-full h-full object-cover">
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 pb-6 relative">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end -mt-16 sm:-mt-20 gap-4">

                <div class="flex flex-col sm:flex-row items-center sm:items-end gap-4 sm:gap-6 w-full">
                    <div class="w-32 h-32 rounded-lg border-4 border-[#111111] bg-[#1c1c1c] overflow-hidden">
                        <img src="{{ $logoImage }}" alt="{{ $salle->name }} logo"
                            onerror="this.onerror=null;this.src='{{ $defaultLogoImage }}';"
                            class="w-full h-full object-cover">
                    </div>

                    <div class="mb-1 w-full flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ $salle->name }}</h1>
                            <p class="text-zinc-400 mt-1">"{{ $salle->tagline ?: 'Train smarter, train stronger.' }}"
                            </p>
                            <div class="flex items-center gap-4 mt-2 text-sm font-medium text-zinc-500">
                                <span><i class="fa-solid fa-location-dot text-[#FBBF24]"></i> {{ $salle->city }}</span>
                                <span><i class="fa-solid fa-users"></i>
                                    {{ $salle->sessionType ?: 'Open sessions' }}</span>
                            </div>
                        </div>

                        @php
                            $isFavoris = $salle->isFavoris();
                        @endphp

                        <div class="flex gap-2">
                            @if ($isFavoris)
                                <form action="{{ route('favorites.destroy', $salle) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-[#1c1c1c] border border-red-500 text-red-500 font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-red-500 hover:text-white transition-colors"
                                        title="Remove from favorites" aria-label="Remove from favorites">
                                        <i class="fa-solid fa-heart"></i>
                                        Favoris
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('favorites.store', $salle) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-[#1c1c1c] border border-red-500 text-red-500 font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-red-500 hover:text-white transition-colors"
                                        title="Add to favorites" aria-label="Add to favorites">
                                        <i class="fa-regular fa-heart"></i>
                                        Favoris
                                    </button>
                                </form>
                            @endif

                            @can('update', $salle)
                                <a href="{{ route('coach.salles.edit', $salle) }}"
                                    class="bg-[#d1fa48] text-black font-semibold py-2 px-4 rounded-lg flex items-center gap-2 hover:bg-[#bde13f] transition-colors">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </a>
                            @endcan

                            <a href="{{ route('salles.index') }}"
                                class="bg-[#1c1c1c] border border-zinc-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center gap-2">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                            @can('update', $salle)
                                <form action="{{ route('coach.salles.destroy', $salle) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        class="inline-flex items-center justify-center gap-2  text-white text-sm font-bold py-3 px-6 rounded-md bg-black">
                                        <i class="fa-solid fa-trash"></i>
                                        Force Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-1 flex flex-col gap-4">

            <div class="bg-[#111111] border border-zinc-800 rounded-lg p-5">
                <h2 class="text-lg font-bold text-white mb-3">About</h2>
                <p class="text-sm text-zinc-300  mb-4">
                    {{ $salle->description ?: 'No detailed description has been added for this salle yet.' }}
                </p>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3 text-zinc-400">
                        <i class="fa-solid fa-calendar-check w-5 text-center"></i>
                        <span>Established <strong class="text-white">{{ $salle->existenceYears ?? 0 }} Years</strong>
                            ago</span>
                    </div>
                    <div class="flex items-center gap-3 text-zinc-400">
                        <i class="fa-solid fa-dumbbell w-5 text-center"></i>
                        <span>Primary Sport: <strong
                                class="text-white">{{ $salle->sport?->title ?: 'Not specified' }}</strong></span>
                    </div>
                    <div class="flex items-center gap-3 text-zinc-400">
                        <i class="fa-solid fa-user-tie w-5 text-center"></i>
                        <span>Head Coach: <strong class="text-white">{{ $coachName }}</strong></span>
                    </div>
                </div>
            </div>

            <div class="bg-[#111111] border border-zinc-800 rounded-lg p-5">
                <h2 class="text-lg font-bold text-white mb-3">Opening Hours</h2>
                @if ($salle->horaires->isNotEmpty())
                    <ul class="text-sm space-y-2">
                        @foreach ($dayOrder as $day)
                            @php $slot = $horairesByDay->get($day); @endphp
                            <li class="flex justify-between {{ $slot ? 'text-zinc-300' : 'text-zinc-500' }}">
                                <span>{{ ucfirst($day) }}</span>
                                <span>
                                    @if ($slot && $slot->openHour && $slot->closeHour)
                                        {{ \Illuminate\Support\Carbon::parse($slot->openHour)->format('H:i') }} -
                                        {{ \Illuminate\Support\Carbon::parse($slot->closeHour)->format('H:i') }}
                                    @else
                                        Closed
                                    @endif
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-zinc-500">No opening hours added yet.</p>
                @endif
            </div>

            <div class="bg-[#111111] border border-zinc-800 rounded-lg p-5">
                <h2 class="text-lg font-bold text-white mb-3">Services</h2>
                @if ($salle->services->isNotEmpty())
                    <div class="flex flex-wrap gap-2">
                        @foreach ($salle->services as $service)
                            <span
                                class="bg-[#1c1c1c] border border-zinc-700 text-zinc-300 text-xs px-3 py-1.5 rounded-lg">{{ $service->title }}</span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-zinc-500">No services listed.</p>
                @endif
            </div>

            <div class="bg-[#111111] border border-zinc-800 rounded-lg p-5">
                <h2 class="text-lg font-bold text-white mb-3">Equipment Inventory</h2>
                @if ($salle->equipments->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($salle->equipments as $equipment)
                            <div class="flex gap-3">
                                @php
                                    $equipmentImage = $resolveImageUrl($equipment->image, $defaultBackgroundImage);
                                @endphp
                                <img src="{{ $equipmentImage }}" alt="{{ $equipment->name }}"
                                    onerror="this.onerror=null;this.src='{{ $defaultBackgroundImage }}';"
                                    class="w-12 h-12 rounded-lg object-cover bg-[#1c1c1c]">
                                <div>
                                    <h4 class="text-white text-sm font-semibold">{{ $equipment->name }}</h4>
                                    <p class="text-xs text-zinc-500">Condition:
                                        {{ $equipment->pivot?->condition ?: 'Not specified' }}
                                    </p>
                                    @if ($equipment->pivot?->description)
                                        <p class="text-xs text-zinc-400 mt-1">{{ $equipment->pivot->description }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-zinc-500">No equipment inventory added yet.</p>
                @endif
            </div>

        </div>

        <div class="lg:col-span-2 flex flex-col gap-4">

            <div class="bg-[#111111] border border-zinc-800 rounded-lg p-5">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-lg font-bold text-white">Photos</h2>
                </div>
                @if ($salle->galleries->isNotEmpty())
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        @foreach ($salle->galleries as $gallery)
                            @php
                                $galleryImage = $resolveImageUrl($gallery->content, $defaultBackgroundImage);
                            @endphp
                            <img src="{{ $galleryImage }}" alt="{{ $salle->name }} gallery"
                                onerror="this.onerror=null;this.src='{{ $defaultBackgroundImage }}';"
                                class="w-full h-24 sm:h-32 object-cover rounded-lg border border-zinc-800">
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-zinc-500">No gallery images uploaded yet.</p>
                @endif
            </div>

            <div class="bg-[#111111] border border-zinc-800 rounded-lg p-5">
                <h2 class="text-lg font-bold text-white mb-2">Quick Summary</h2>
                <p class="text-sm text-zinc-400 leading-relaxed">
                    {{ $salle->name }} is located in {{ $salle->city }} and offers {{ $salle->sessionType ?: 'open' }}
                    sessions.
                    {{ $salle->services->count() }} services and {{ $salle->equipments->count() }} equipment items are
                    currently listed.
                </p>
            </div>

        </div>
    </div>

</body>

</html>