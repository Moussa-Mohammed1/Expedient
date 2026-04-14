@php
    $navLinks = [
        ['name' => 'Home', 'path' => 'home'],
        ['name' => 'Explore', 'path' => 'explore'],
        ['name' => 'Coaches', 'path' => 'coaches'],
        ['name' => 'Salles', 'path' => 'salles'],
        ['name' => 'Communities', 'path' => 'communities'],
        ['name' => 'Updates', 'path' => 'about']
    ];
@endphp

<div>
    <header
        class="fixed top-0 left-0 right-0 flex w-full items-center justify-between bg-[#111111] px-6 lg:px-8 py-3 z-50">
        <button id="mobile-menu-btn" class="text-white md:hidden hover:text-gray-300 focus:outline-none p-2">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="z-10">
            <a href="{{ url('/home') }}" class="text-white font-bold text-xl tracking-wide">Expedient</a>
        </div>

        <nav
            class="hidden lg:flex  w-auto min-w-[50%] left-1/2 top-5  items-center justify-center rounded-full bg-[#373636] px-8 py-3 cursor-pointer ring-white/5 transition">
            <ul class="flex items-center justify-around gap-8 text-sm font-medium tracking-wide">
                @foreach($navLinks as $link)
                    @php
                        $isActive = request()->is($link['path']) || request()->is($link['path'] . '/*');
                    @endphp
                    <li class="relative">
                        <a href="{{ url('/' . $link['path']) }}"
                            class="text-white hover:text-gray-300 transition-colors {{ $isActive ? 'font-bold' : '' }}">
                            {{ $link['name'] }}
                        </a>
                        @if($isActive)
                            <span
                                class="absolute -bottom-1.5 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-[#ff5520]"></span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="hidden lg:flex items-center  px-3 justify-center rounded-full gap-3 box-border z-10">
            @auth
                @if (auth()->user()->role && auth()->user()->role->title === 'coach' && auth()->user()->coach && !auth()->user()->coach->hasBadge())
                    <x-coach-badge :coach="auth()->user()->coach" />
                @endif
            @endauth
            @auth
                <x-profile-icon />
            @endauth
        </div>

        <div class="flex lg:hidden z-10">
            @auth
                @if (auth()->user()->role && auth()->user()->role->title === 'coach' && auth()->user()->coach && !auth()->user()->coach->hasBadge())
                    <x-coach-badge :coach="auth()->user()->coach" />
                @endif
            @endauth

            <x-profile-icon />
        </div>
    </header>

    <div class="h-26"></div>

    <div id="mobile-menu"
        class="hidden lg:hidden bg-[#1c1c1c] fixed top-26 left-0 right-0 z-50 border-t border-white/10 shadow-lg max-h-[calc(100vh-6.5rem)] overflow-y-auto">
        <div class="px-6 py-4 flex flex-col gap-4">
            <ul class="flex flex-col gap-4 text-white">
                @foreach($navLinks as $link)
                    @php
                        $isActive = request()->is($link['path']) || request()->is($link['path'] . '/*');
                    @endphp
                    <li><a href="{{ url('/' . $link['path']) }}"
                            class="{{ $isActive ? 'text-[#ff5520] font-bold' : '' }}">{{ $link['name'] }}</a>
                    </li>
                @endforeach
                <li><a href="{{ route('profile.show', auth()->id()) }}"
                        class="{{ $isActive ? 'text-[#ff5520] font-bold' : '' }}">Profile</a>
                </li>
                @auth
                    @if(auth()->user()->role && auth()->user()->role->title === 'admin')
                        <li><a href="{{ url('/dashboard') }}"
                                class="{{ request()->is('dashboard') ? 'text-[#ff5520] font-bold' : '' }}">Admin</a></li>
                    @endif
                @endauth
            </ul>

            <div class="border-t border-white/10 pt-4 flex flex-col gap-4">
                @auth
                    <a href="{{ route('logout')}}"
                        class="w-full bg-yellow-500 text-black font-semibold py-2 rounded-lg text-center">
                        Logout
                    </a>
                @else
                    <a href="{{ route('login') ?? url('/login') }}"
                        class="w-full bg-[#ff5520] text-white py-2 rounded-xl text-center">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>

</div>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function () {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>