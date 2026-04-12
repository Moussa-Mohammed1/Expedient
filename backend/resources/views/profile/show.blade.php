<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Profile </title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-black text-white font-sans ">

    @include('layouts.header')

    <div class="max-w-4xl mx-auto pt-6 md:pt-10">

        @php
            $user = $profileUser ?? auth()->user();
            $avatarUrl = $user->avatar
                ? asset('/storage/users/profiles/' . $user->avatar)
                : asset('assets/images/profile.jpeg');
        @endphp

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl md:text-3xl font-bold text-white">Profile Overview</h1>
                <p class="text-sm text-zinc-400 mt-2">View your personal details, system role, and account status.</p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('profile.edit', $user->id) }}"
                    class="inline-flex items-center rounded-md bg-[#ff5520] px-3 py-1.5 text-xs font-semibold text-white shadow-sm border border-[#ff5520] hover:bg-orange-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#ff5520] transition-colors">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                    </svg>
                    Update Profile
                </a>
            </div>
        </div>

        <div class="overflow-hidden bg-[#111111] shadow-sm ring-1 ring-white/5 sm:rounded-xl border border-zinc-800">
            @if(session('success'))
                <div
                    class="mb-5 rounded-lg border border-emerald-500/20 bg-emerald-900/20 px-4 py-3 text-sm text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif
            <div class="px-4 py-5 sm:px-6 flex items-center gap-x-5 bg-[#111111]">
                <img src="{{ $avatarUrl }}" alt="User Avatar"
                    class="h-20 w-20 rounded-full ring-2 ring-[#ff5520] object-cover shadow-sm">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold  text-white">{{ $user->name }}</h2>
                    <div class="mt-1 flex items-center gap-x-2">
                        <p class="text-xs md:text-sm font-medium  text-zinc-400 capitalize">
                            {{ $user->role->title ?? 'User' }}
                        </p>
                        <svg class="h-1.5 w-1.5 fill-emerald-400" viewBox="0 0 6 6" aria-hidden="true">
                            <circle cx="3" cy="3" r="3" />
                        </svg>
                        <p class="text-[11px]  text-zinc-400">Active</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-zinc-800">
                <dl class="divide-y divide-zinc-800">

                    <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-[#1c1c1c] transition-colors">
                        <dt class="text-xs md:text-sm font-medium text-zinc-400 flex items-center">Email address</dt>
                        <dd class="mt-1 text-xs md:text-sm text-white sm:col-span-2 sm:mt-0 flex items-center gap-3">
                            {{ $user->email }}
                            @if($user->email_verified_at)
                                <span
                                    class="inline-flex items-center rounded-md bg-emerald-900/30 px-2 py-1 text-xs font-medium text-emerald-400 ring-1 ring-inset ring-emerald-700/20">Verified</span>
                            @else
                                <span
                                    class="inline-flex items-center rounded-md bg-zinc-800 px-2 py-1 text-xs font-medium text-zinc-300 ring-1 ring-inset ring-zinc-700">Unverified</span>
                            @endif
                        </dd>
                    </div>

                    <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-[#1c1c1c] transition-colors">
                        <dt class="text-xs md:text-sm font-medium text-zinc-400 flex items-center">Phone number</dt>
                        <dd class="mt-1 text-xs md:text-sm text-white sm:col-span-2 sm:mt-0">
                            {{ $user->phone ?: 'Not provided yet' }}
                        </dd>
                    </div>

                    <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-[#1c1c1c] transition-colors">
                        <dt class="text-xs md:text-sm font-medium text-zinc-400 flex items-center">System Role</dt>
                        <dd class="mt-1 text-xs md:text-sm text-white sm:col-span-2 sm:mt-0">
                            <span
                                class="inline-flex items-center rounded-md bg-yellow-500/10 px-2 py-0.5 text-[11px] font-medium text-[#FBBF24] ring-1 ring-inset ring-yellow-500/20">{{ ucfirst($user->role->title ?? 'Expedient User') }}
                            </span>
                        </dd>
                    </div>

                    <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-[#1c1c1c] transition-colors">
                        <dt class="text-xs md:text-sm font-medium text-zinc-400 flex items-center">Localisation</dt>
                        <dd class="mt-1 text-xs md:text-sm text-white sm:col-span-2 sm:mt-0">
                            {{ $user->localisation ?: 'Not specified yet' }}
                        </dd>
                    </div>

                    <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-[#1c1c1c] transition-colors">
                        <dt class="text-xs md:text-sm font-medium text-zinc-400 flex items-center">Coach Specialities</dt>
                        <dd class="mt-1 text-xs md:text-sm text-white sm:col-span-2 sm:mt-0">
                            @if ($user->coach && $user->coach->specialities->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($user->coach->specialities as $speciality)
                                        <span
                                            class="inline-flex items-center rounded-md bg-yellow-500/10 px-2 py-0.5 text-[11px] font-medium text-[#FBBF24] ring-1 ring-inset ring-yellow-500/20">
                                            {{ $speciality->title }} + {{ $speciality->experienceYears }}
                                        </span>
                                    @endforeach
                                </div>
                            @elseif($user->coach)
                                <span class="text-zinc-400">No speciality assigned yet.</span>
                            @else
                                <span class="text-zinc-500">This user is not registered as a coach.</span>
                            @endif
                        </dd>
                    </div>

                    <div class="px-4 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 hover:bg-[#1c1c1c] transition-colors">
                        <dt class="text-xs md:text-sm font-medium text-zinc-400 flex items-center">Password</dt>
                        <p
                            class="mt-1 text-xs md:text-sm text-white sm:col-span-2 sm:mt-0 flex items-center justify-between">
                            <span>••••••••••••</span>
                            <span class="text-[11px] text-zinc-500 italic">Hidden for security</span>
                        </p>
                    </div>

                </dl>
            </div>
        </div>

    </div>

</body>

</html>