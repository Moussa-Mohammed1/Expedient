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

<body class="bg-[#18181b] text-gray-300 min-h-screen">
    @include('layouts.coachNavbar')
    <div class="w-full min-h-screen px-4 sm:px-6 lg:px-10 py-10 lg:py-16">
        <form action="{{ route('coach.salles.store') }}" method="POST"
            class="w-full min-h-screen bg-[#222222] border border-zinc-800 rounded-md p-6 sm:p-8">
            @csrf

            <input type="hidden" name="coach_id" value="{{ $coach->id }}">

            @if ($errors->any())
                <div class="mb-6 rounded-md border border-red-500/40 bg-red-500/10 p-4 text-sm text-red-200">
                    <p class="font-semibold">Please fix the form errors and try again.</p>
                </div>
            @endif

            <div class="space-y-6">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="name"
                            class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Salle Name <span
                                class="text-[#ff5520]">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            placeholder="e.g., Iron Impact Dojo"
                            class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24]">
                    </div>
                    <div>
                        <label for="city"
                            class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">City <span
                                class="text-[#ff5520]">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city') }}" required
                            placeholder="e.g., Safi"
                            class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24]">
                    </div>
                </div>

                <div>
                    <label for="tagline"
                        class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Tagline <span
                            class="text-zinc-600 font-normal normal-case ml-1">(Optional)</span></label>
                    <input type="text" id="tagline" name="tagline" value="{{ old('tagline') }}"
                        placeholder="A short, catchy phrase for your space"
                        class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="sport_id"
                            class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Primary Sport
                            <span class="text-[#ff5520]">*</span></label>
                        <select id="sport_id" name="sport_id" required
                            class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] appearance-none">
                            <option value="" disabled {{ old('sport_id') ? '' : 'selected' }}>Select primary focus...
                            </option>
                            @foreach($sports as $sport)
                                <option value="{{ $sport->id }}" {{ (string) old('sport_id') === (string) $sport->id ? 'selected' : '' }}>
                                    {{ $sport->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="sessionType"
                            class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Session Type
                            <span class="text-zinc-600 font-normal normal-case ml-1">(Optional)</span></label>
                        <select id="sessionType" name="sessionType"
                            class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] appearance-none">
                            <option value="" {{ old('sessionType') ? '' : 'selected' }}>Select session type...</option>
                            <option value="Mixed" {{ old('sessionType') === 'mixed' ? 'selected' : '' }}>Mixed</option>
                            <option value="Women Only" {{ old('sessionType') === 'women_only' ? 'selected' : '' }}>Women
                                Only</option>
                            <option value="Men Only" {{ old('sessionType') === 'men_only' ? 'selected' : '' }}>Men Only
                            </option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="existenceYears"
                        class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Years of Existence
                        <span class="text-zinc-600 font-normal normal-case ml-1">(Optional)</span></label>
                    <input type="number" id="existenceYears" name="existenceYears" value="{{ old('existenceYears') }}"
                        min="0" placeholder="e.g., 5"
                        class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24]">
                </div>

                <div>
                    <label for="description"
                        class="block text-xs font-bold text-zinc-400 uppercase tracking-wide mb-2">Description <span
                            class="text-zinc-600 font-normal normal-case ml-1">(Optional)</span></label>
                    <textarea id="description" name="description" rows="4"
                        placeholder="Describe the atmosphere, equipment, and community..."
                        class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-md px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] resize-none">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-zinc-800 flex flex-col sm:flex-row items-center justify-end gap-4">
                <a href="{{ route('coach.salles') }}"
                    class="w-full sm:w-auto text-center bg-transparent border border-zinc-700 text-zinc-300 text-sm font-bold py-3 px-6 rounded-full">
                    Cancel
                </a>
                <button type="submit"
                    class="w-full sm:w-auto bg-[#d1fa48] text-black text-sm font-bold py-3 px-8 rounded-full flex items-center justify-center gap-2">
                    <i class="fa-solid fa-check"></i> Create Space
                </button>
            </div>

        </form>
    </div>

</body>

</html>