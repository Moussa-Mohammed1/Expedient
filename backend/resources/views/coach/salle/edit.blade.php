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

<body class="bg-black text-gray-300 min-h-screen">
    @include('layouts.coachNavbar')

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
    @endphp

    <div class="relative w-full min-h-[calc(100vh-72px)] px-4 sm:px-6 lg:px-10 py-8 lg:py-10">

        <div class="relative max-w-350 mx-auto">
            <form id="update-salle-form" action="{{ route('coach.salles.update', $salle) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                @csrf
                @method('PUT')

                @if ($errors->any())
                    <div class="xl:col-span-12 bg-[#2a1414] border border-red-500/40 rounded-xl p-4 sm:p-5">
                        <div class="flex items-center gap-2 text-red-300 font-semibold text-sm mb-2">
                            Please fix the following errors:
                        </div>
                        <ul class="list-disc list-inside text-red-200 text-xs sm:text-sm space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class=" rounded-xl p-6 sm:p-8 xl:col-span-8">
                    <h2 class="text-lg font-bold text-white mb-6 border-b border-zinc-800/50 pb-2">1. Basic Information
                    </h2>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Salle
                                    Name</label>
                                <input type="text" name="name" value="Atlas Power Gym"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">City</label>
                                <input type="text" name="city" value="Safi"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Tagline</label>
                            <input type="text" name="tagline" value="{{ $salle->tagline }}"
                                class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div>
                                <label
                                    class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Primary
                                    Sport</label>
                                <select name="sport_id"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] appearance-none">
                                    @foreach ($sports as $sport)
                                        <option value="{{ $sport->id }}" {{ (string) old('sport_id', $salle->sport_id) === (string) $sport->id ? 'selected' : '' }}>
                                            {{ $sport->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label
                                    class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Session
                                    Type</label>
                                <select name="sessionType"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] appearance-none">
                                    <option value="mixed" selected>Mixed</option>
                                    <option value="women only">Women Only</option>
                                    <option value="men only">Men Only</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Years
                                    of
                                    Existence</label>
                                <input type="number" name="existenceYears" value="5"
                                    class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Description</label>
                            <textarea name="description" rows="4"
                                class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] resize-none">Atlas Power Gym is a premium fitness facility located in the heart of Safi. We provide top-tier equipment for powerlifters, bodybuilders, and general fitness enthusiasts.</textarea>
                        </div>
                    </div>
                </div>

                @include('coach.salle.partials.media')

                <div class="bg-[#17181b] border border-zinc-800/80 rounded-xl p-6 sm:p-8 xl:col-span-12">
                    <h2 class="text-lg font-bold text-white mb-6 border-b border-zinc-800/50 pb-2">3. Amenities &
                        Services
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        @php
                            $selectedServiceIds = collect(old('services', $salle->services->pluck('id')->all()))
                                ->map(fn($id) => (string) $id)
                                ->all();
                        @endphp

                        @forelse ($services as $service)
                            <label class="flex items-center gap-3 bg-[#1c1c1c] border border-zinc-700 p-3 rounded-md">
                                <input type="checkbox" name="services[]" value="{{ $service->id }}"
                                    {{ in_array((string) $service->id, $selectedServiceIds, true) ? 'checked' : '' }}>
                                <span class="text-sm text-zinc-300 font-medium">{{ $service->title }}</span>
                            </label>
                        @empty
                            <p class="text-white text-center">empty, plateform under dev</p>
                        @endforelse
                    </div>
                </div>

                @include('coach.salle.partials.opening-hours')

                @include('coach.salle.partials.equipments')

                <div
                    class="xl:col-span-12 sticky bottom-0 z-20 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-4 pb-3 bg-[#101113]/95 backdrop-blur-md border-t border-zinc-800/70">
                    
                    <button type="button"
                        class="bg-transparent border border-zinc-700 text-zinc-300 text-sm font-bold py-3 px-6 rounded-full">
                        Cancel
                    </button>
                    <button type="submit"
                        class="bg-yellow-500 active:scale-95 duration-300 text-black text-sm font-bold py-3 px-8 rounded-full flex items-center justify-center gap-2">Save
                        All Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
    

</body>

</html>