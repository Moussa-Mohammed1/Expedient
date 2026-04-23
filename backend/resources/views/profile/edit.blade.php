<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient User Profile</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>

<body class="bg-black text-white">
    @include('layouts.header')
    <x-notification-popup />
    @php
        $user = $profileUser ?? auth()->user();
        $avatarUrl = $user->avatar
            ? asset('/storage/users/profiles/' . $user->avatar)
            : asset('assets/images/profile.jpeg');

        $selectedSpecialityIds = collect(old('speciality_ids', $user->isCoach() ? $user->coach->specialities->pluck('id')->all() : []))
            ->map(fn($id) => (int) $id)
            ->all();
    @endphp

    <div class="max-w-4xl mx-auto px-4">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">Profile Settings</h1>
            <p class="text-gray-400 mt-1 text-sm">Manage your Expedient account details and preferences. Each field can
                be updated independently.</p>
        </div>


        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-500/20 bg-red-900/20 px-4 py-3 text-sm text-red-300">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-[#1c1c1c] shadow-sm ring-1 ring-white/5 overflow-hidden rounded-lg">
            <form action="{{ route('profile.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="divide-y divide-zinc-800">
                @csrf
                @method('PATCH')

                <div class="p-6 flex flex-col lg:flex-row lg:items-center gap-6 hover:bg-zinc-700/30 transition-colors">
                    <div class="lg:w-1/3 flex flex-col items-center lg:items-start gap-3">
                        <img src="{{ $avatarUrl }}" alt="Avatar"
                            class="h-20 w-20 rounded-full object-cover shadow-md ring-2 ring-[#ff5520]">
                        <label for="avatar" class="block text-xs font-medium text-gray-300">Profile Avatar</label>
                        <input type="file" id="avatar" name="avatar"
                            class="cursor-pointer block w-full text-xs text-gray-400 file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#ff5520] file:text-white hover:file:bg-orange-600">
                        <p class="text-xs text-gray-400">PNG, JPG up to 2MB</p>
                        @error('avatar')
                            <p class="text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="lg:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-4 w-full">
                        <div>
                            <label for="name" class="block text-xs font-medium text-gray-300 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520]">
                            @error('name')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-medium text-gray-300 mb-2">Email
                                Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520]">
                            @error('email')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-medium text-gray-300 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                placeholder="e.g. +1 234 567 8900"
                                class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] placeholder-gray-500">
                            @error('phone')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="localisation"
                                class="block text-xs font-medium text-gray-300 mb-2">Localisation</label>
                            <input type="text" id="localisation" name="localisation"
                                value="{{ old('localisation', $user->localisation) }}"
                                placeholder="City, Region, or Hub"
                                class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] placeholder-gray-500">
                            @error('localisation')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @if ($user->isCoach())
                    <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-4 hover:bg-zinc-700/30 transition-colors">
                        <div>
                            <label class="block text-xs font-medium text-gray-300 mb-2">Coach Specialities</label>
                            <p class="text-xs text-gray-400">Select one or more specialities. Unchecked items are removed
                                when you save.</p>
                        </div>
                        <div class="lg:col-span-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @forelse (($allSpecialities ?? collect()) as $speciality)
                                    <label
                                        class="flex items-center gap-2 rounded-md border border-zinc-700 bg-zinc-800 px-3 py-2 text-sm text-white cursor-pointer hover:border-[#ff5520]">
                                        <input type="checkbox" name="speciality_ids[]" value="{{ $speciality->id }}"
                                            @checked(in_array($speciality->id, $selectedSpecialityIds, true))
                                            class="h-4 w-4 rounded border-zinc-600 bg-zinc-900 text-[#ff5520] focus:ring-[#ff5520]">
                                        <span>{{ $speciality->title }}</span>
                                    </label>
                                @empty
                                    <p class="text-xs text-gray-400">No specialities are available yet.</p>
                                @endforelse
                            </div>

                            @error('speciality_ids')
                                <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                            @error('speciality_ids.*')
                                <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="p-6 grid grid-cols-1 lg:grid-cols-3 gap-4 hover:bg-zinc-700/30 transition-colors">
                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-300 mb-2">Password
                            Modification</label>
                        <p class="text-xs text-gray-400">Leave blank to keep your current password. Current password is
                            required to make any changes.</p>
                    </div>
                    <div class="lg:col-span-2 grid grid-cols-1 gap-4">
                        <div>
                            <label for="current_password" class="block text-xs font-medium text-gray-300 mb-2">Current
                                Password</label>
                            <input type="password" id="current_password" name="current_password"
                                autocomplete="current-password"
                                class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] placeholder-gray-500"
                                placeholder="Enter your current password">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-xs font-medium text-gray-300 mb-2">New
                                    Password</label>
                                <input type="password" id="password" name="password" autocomplete="new-password"
                                    class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] placeholder-gray-500"
                                    placeholder="New password">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="password_confirmation"
                                    class="block text-xs font-medium text-gray-300 mb-2">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    autocomplete="new-password"
                                    class="w-full rounded-md border-zinc-700 border py-2 px-3 text-sm text-white bg-zinc-800 shadow-sm focus:border-[#ff5520] focus:ring-1 focus:ring-[#ff5520] placeholder-gray-500"
                                    placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-6 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                    <div class="text-xs text-gray-400">
                        <span class="text-[#FBBF24] font-semibold">Note:</span> password changes use your current
                        password for confirmation.
                    </div>
                    <button type="submit"
                        class="bg-[#ff5520] cursor-pointer border border-[#ff5520] text-white hover:bg-orange-600 font-medium py-2 px-4 rounded-md shadow-sm text-sm w-full lg:w-auto transition-colors">
                        Save Changes
                    </button>
                </div>
            </form>
            <div class="mt-10 px-6  py-6 border-t border-gray-800">
                <div class="flex flex-col gap-2">
                    <h3 class="text-sm font-semibold text-white">Danger Zone</h3>
                    <p class="text-xs text-gray-500 mb-2">Once you delete your account, there is no going back. Please
                        be certain.</p>

                    <form action="{{ route('profile.destroy', $user->id) }}" method="POST" >
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="group relative flex items-center justify-center gap-2 w-full sm:w-auto px-6 py-2.5 bg-transparent border border-red-900/50 text-red-500 text-sm font-medium rounded-lg hover:bg-red-600 hover:text-white hover:border-red-600 transition-all duration-200">
                            Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

</body>

</html>