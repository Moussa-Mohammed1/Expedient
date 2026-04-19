<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Expedient - Specialities</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    @include('layouts.coachNavbar')
    <x-notification-popup />

    <section class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-[#222222] border border-zinc-800 rounded-md p-6 sm:p-8">

            <div class="mb-6 border-b border-zinc-800/50 pb-4">
                <h2 class="text-lg font-bold text-white tracking-tight">Manage Specialities</h2>
                <p class="text-xs text-zinc-500 mt-1">Update your expertise and keep your coaching profile current.
                </p>
            </div>

            <div class="space-y-4 mb-8">

                @forelse ($coach->specialities as $speciality)
                    <div class="bg-[#1c1c1c] border border-zinc-700 rounded-md p-4">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <p class="text-sm text-zinc-400">Speciality</p>
                                <h3 class="text-base font-bold text-white">{{ $speciality->title }}</h3>
                            </div>

                            <form action="{{ route('coach.specialities.destroy', $speciality) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-[#222222] border border-zinc-700 text-[#ff5520] w-9 h-9 rounded-full flex items-center justify-center shrink-0"
                                    title="Remove speciality">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>

                        <form action="{{ route('coach.specialities.update', $speciality) }}" method="POST"
                            class="flex flex-col sm:flex-row sm:items-end gap-4">
                            @csrf
                            @method('PUT')

                            <div class="w-full sm:flex-1">
                                <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1.5">Level</label>
                                <select name="level"
                                    class="w-full bg-[#222222] border border-zinc-700 rounded-md px-3 py-2 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                                    @foreach (['beginner', 'intermediate', 'advanced'] as $level)
                                        <option value="{{ $level }}" @selected($speciality->pivot->level === $level)>
                                            {{ ucfirst($level) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full sm:w-36">
                                <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1.5">Years Exp.</label>
                                <input type="number" name="experienceYears" min="0" max="80"
                                    value="{{ $speciality->pivot->experienceYears }}"
                                    class="w-full bg-[#222222] border border-zinc-700 rounded-md px-3 py-2 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                            </div>

                            <div class="w-full sm:w-auto">
                                <button type="submit"
                                    class="w-full sm:w-auto bg-zinc-700 text-white text-xs font-bold px-4 py-2.5 rounded-full shrink-0">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                @empty
                    <div class="bg-[#1c1c1c] border border-zinc-700 rounded-md p-6 text-center">
                        <p class="text-sm text-zinc-400">No specialities added yet.</p>
                    </div>
                @endforelse
            </div>

            <div class="border-t border-zinc-800/50 pt-6">
                <h3 class="text-sm font-bold text-white mb-4">Add Existing Speciality</h3>
                <form action="{{ route('coach.specialities.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1.5">Speciality</label>
                            <select name="speciality_id"
                                class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-2.5 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                                <option value="">Select admin-defined speciality</option>
                                @foreach ($allSpecialities as $available)
                                    <option value="{{ $available->id }}" @selected((string) old('speciality_id') === (string) $available->id)>
                                        {{ $available->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1.5">Level</label>
                            <select name="level"
                                class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-2.5 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                                @foreach (['beginner', 'intermediate', 'advanced'] as $level)
                                    <option value="{{ $level }}" @selected(old('level', 'beginner') === $level)>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1.5">Years Exp.</label>
                            <input type="number" name="experienceYears" min="0" max="80"
                                value="{{ old('experienceYears', 1) }}"
                                class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-2.5 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                        </div>

                        <div>
                            <button type="submit"
                                @disabled($allSpecialities->isEmpty())
                                class="w-full sm:w-auto bg-[#d1fa48] text-black text-sm font-bold py-2.5 px-6 rounded-full shrink-0 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-plus"></i> Add
                            </button>
                        </div>
                    </div>

                    @if ($allSpecialities->isEmpty())
                        <p class="text-xs text-zinc-500">All available specialities are already assigned. Ask an admin to create new ones.</p>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-md border border-red-400/40 bg-red-900/20 p-3 text-sm text-red-200">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>

        </div>
    </section>
</body>

</html>