<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Favorites</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-black text-gray-300 font-sans antialiased min-h-screen">
    @include('layouts.header')

    @php
        $defaultBackgroundImage = asset('assets/images/salle_default.jpeg');

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
    @endphp

    <div class="max-w-350 mx-auto px-3 sm:px-6 lg:px-8 py-8 lg:py-12">
        @if (session('success'))
            <div class="mb-6 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-lg p-3 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4 border-b border-zinc-800 pb-5">
            <div>
                <h1 class="text-2xl lg:text-4xl font-bold text-white tracking-tight mb-2">Saved Spaces</h1>
                <p class="text-zinc-400 text-sm lg:text-base flex items-center gap-2">
                    <i class="fa-solid fa-bookmark text-white"></i>
                    You have <span class="text-white font-bold">{{ $favoriteCount }}</span> favorited facilities
                </p>
            </div>
        </div>

        @if ($favoriteSalles->isEmpty())
            <div class="bg-[#111111] border border-zinc-800/80  p-8 sm:p-12 text-center">
                
                <h2 class="text-xl font-bold text-white mb-2">No favorites yet</h2>
                <p class="text-zinc-400 mb-6">Save your favorite gyms while exploring to see them here.</p>
                
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach ($favoriteSalles as $salle)
                    @php
                        $coverPath = $salle->galleries->first()?->content;
                        $coverUrl = $coverPath
                            ? $resolveImageUrl($coverPath, $defaultBackgroundImage)
                            : $resolveImageUrl($salle->background, $defaultBackgroundImage);
                    @endphp

                    <a href="{{ route('salles.show', $salle) }}"
                        class="bg-[#111111] border border-zinc-800/80 rounded-xl overflow-hidden hover:border-zinc-600 transition-colors group flex flex-col relative">
                        <div class="relative h-32 sm:h-40 cursor-pointer lg:h-48 overflow-hidden bg-[#1c1c1c]">
                            <img src="{{ $coverUrl }}" alt="{{ $salle->name }}"
                                onerror="this.onerror=null;this.src='{{ $defaultBackgroundImage }}';"
                                class="w-full h-full object-cover  transition-transform duration-500">

                            <form action="{{ route('favorites.destroy', $salle) }}" method="POST"
                                class="absolute top-2 right-2 z-10">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-black/60 backdrop-blur-md border border-zinc-700 text-[#ff5520] hover:text-white hover:bg-[#ff5520] hover:border-[#ff5520] w-8 h-8 rounded-full flex items-center justify-center transition-all"
                                    title="Remove from favorites">
                                    <i class="fa-solid fa-heart text-sm"></i>
                                </button>
                            </form>
                        </div>

                        <div class="p-3 sm:p-5 flex flex-col flex-1">
                            <div class="mb-2">
                                <div class="flex justify-between items-start gap-2">
                                    <h3 class="text-sm sm:text-lg font-bold text-white leading-tight truncate"
                                        title="{{ $salle->name }}">
                                        {{ $salle->name }}
                                    </h3>
                                </div>
                                <p class="text-[10px] sm:text-xs text-zinc-500 mt-1">
                                    <i
                                        class="fa-solid fa-location-dot mr-1 text-zinc-600"></i>{{ $salle->city ?: 'Unknown city' }}
                                </p>
                            </div>

                            <p class="text-[10px] sm:text-sm text-zinc-400 italic line-clamp-2 mb-3">
                                "{{ $salle->tagline ?: ($salle->description ?: 'No description available.') }}"
                            </p>

                            <div class="flex flex-wrap gap-1 mb-4 sm:mb-5 mt-auto">
                                <span
                                    class="bg-[#1c1c1c] border border-zinc-800 text-zinc-300 text-[9px] sm:text-[11px] px-1.5 sm:px-2 py-0.5 rounded-md">
                                    {{ $salle->sport?->title ?: 'General' }}
                                </span>
                                <span
                                    class="bg-[#1c1c1c] border border-zinc-800 text-zinc-300 text-[9px] sm:text-[11px] px-1.5 sm:px-2 py-0.5 rounded-md">
                                    {{ $salle->galleries->count() }} Photos
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</body>

</html>