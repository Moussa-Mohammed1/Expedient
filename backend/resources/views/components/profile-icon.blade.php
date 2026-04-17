<div>
    <div class="relative inline-block text-left group">
        <button
            class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden border  cursor-pointer focus:outline-none">

            <img src="{{ auth()->user()->avatar
    ? asset('/storage/users/profiles/' . auth()->user()->avatar)
    : asset('assets/images/profile.jpeg') }}" alt="User Avatar" class="h-full w-full object-cover">
        </button>

        <div
            class="absolute right-0 mt-2 w-70 bg-[#1c1c1c] rounded-md shadow-xl border border-gray-700 opacity-0 invisible group-focus-within:visible group-focus-within:opacity-100 transition-all duration-200 z-50 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-700 flex items-center justify-between">
                <div class="min-w-0 max-w-38">
                    <p class="text-xs text-gray-400">Signed in as</p>
                    <p class="block max-w-38 truncate text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 capitalize">
                        {{ auth()->user()->role->title ?? 'User' }}
                    </p>
                </div>
                <img src="{{ auth()->user()->avatar ? asset('/storage/users/profiles/' . auth()->user()->avatar) : asset('assets/images/profile.jpeg') }}"
                    alt="User Avatar" class="h-10 w-10 rounded-full object-cover border border-gray-600">
            </div>
            <div class="py-1 border-b border-gray-700">
                <a href="{{ url('/home') }}"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                    <i class="fa-solid fa-house w-5 text-center mr-1"></i>Home
                </a>
                <a href="{{ route('profile.show', auth()->id()) }}"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                    <i class="fa-regular fa-user w-5 text-center mr-1"></i>Profile
                </a>
                <a href="{{ url('/explore') }}"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                    <i class="fa-regular fa-compass w-5 text-center mr-1"></i>Explore
                </a>
                <a href="{{ url('/salles') }}"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                    <i class="fa-regular fa-building w-5 text-center mr-1"></i>Salles
                </a>

                <a href="{{ url('/communities') }}"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                    <i class="fa-regular fa-message w-5 text-center mr-1"></i> Communities
                </a>
                <a href="{{ url('/favorites') }}"
                    class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                    <i class="fa-regular fa-bookmark w-5 text-center mr-1"></i> Favorites
                </a>
                @if (auth()->user()->role->title === "admin")
                    <a href="{{ url('/dashboard') }}"
                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                        <i class="fa-solid fa-crown mr-1 text-center w-5"></i> Admin
                    </a>
                    <a href="{{ url('/coach/salles') }}"
                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                        <i class="fa-solid fa-crown mr-1 text-center w-5"></i> Coach
                    </a>
                @endif
                @if (auth()->user()->role->title === "coach")
                    <a href="{{ url('/coach/salles') }}"
                        class="block px-4 py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                        <i class="fa-solid fa-crown mr-1 text-center w-5"></i> Coach
                    </a>
                @endif

            </div>
            <a href="{{ url('/reports') }}"
                class="block px-4 py-2 mt-1 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white transition-colors">
                <i class="fa-solid fa-flag mr-1 text-center w-5"></i> Reports
            </a>
            <form action="{{ route('logout') ?? url('/logout') }}" method="GET" class="py-1">
                <button type="submit"
                    class="w-full text-left px-4 cursor-pointer py-2 text-sm text-gray-300 hover:bg-[#30363d] hover:text-white">
                    <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center mr-1"></i> Sign out
                </button>
            </form>
        </div>
    </div>
</div>