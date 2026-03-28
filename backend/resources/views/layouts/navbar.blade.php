@php
    $navLinks = [
        ['name' => 'Home', 'path' => 'home'],
        ['name' => 'Explore', 'path' => 'explore'],
        ['name' => 'Coaches', 'path' => 'coaches'],
        ['name' => 'Communities', 'path' => 'communities'],
        ['name' => 'About', 'path' => 'about']
    ];
@endphp

<div>
    <header class="relative flex w-full items-center justify-between bg-[#111111] px-6 lg:px-8 py-5 font-sans antialiased">

        <div class="z-10">
            <a href="{{ url('/home') }}" class="text-white font-bold text-xl tracking-wide">Expedient</a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex absolute w-auto min-w-[50%] hover:shadow-sm left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-[#1c1c1c] px-8 py-3 cursor-pointer shadow-lime-500 shadow-xs ring-1 ring-white/5 transition">
            <ul class="flex items-center justify-around gap-8 text-sm font-medium tracking-wide">
                @auth
                    @if(auth()->user()->role && auth()->user()->role->title === 'admin')
                        <li class="relative">
                            <a href="{{ url('/dashboard') }}" class="text-white hover:text-gray-300 transition-colors {{ request()->is('dashboard') ? 'font-bold' : '' }}">Dashboard</a>
                            @if(request()->is('dashboard'))
                                <span class="absolute -bottom-1.5 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-[#ff5520]"></span>
                            @endif
                        </li>
                    @else
                        @foreach($navLinks as $link)
                            <li class="relative">
                                <a href="{{ url('/' . $link['path']) }}" class="text-white hover:text-gray-300 transition-colors {{ request()->is($link['path']) ? 'font-bold' : '' }}">
                                    {{ $link['name'] }}
                                </a>
                                @if(request()->is($link['path']))
                                    <span class="absolute -bottom-1.5 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-[#ff5520]"></span>
                                @endif
                            </li>
                        @endforeach
                    @endif
                @else
                    @foreach($navLinks as $link)
                        <li class="relative">
                            <a href="{{ url('/' . $link['path']) }}" class="text-white hover:text-gray-300 transition-colors {{ request()->is($link['path']) ? 'font-bold' : '' }}">
                                {{ $link['name'] }}
                            </a>
                            @if(request()->is($link['path']))
                                <span class="absolute -bottom-1.5 left-1/2 h-1 w-1 -translate-x-1/2 rounded-full bg-[#ff5520]"></span>
                            @endif
                        </li>
                    @endforeach
                @endauth
            </ul>
            @auth
                <a href="{{ url('/profile') }}" class="ml-5 pl-4 border-l  border-gray-600 text-white whitespace-nowrap"><i class="fas fa-user"></i> Profile</a>
            @endauth
        </nav>

        <!-- Desktop Right Side Actions -->
        <div class="hidden lg:flex items-center gap-3 z-10">
            @auth
                @if (auth()->user()->role && auth()->user()->role->title === 'coach' && auth()->user()->coach && !auth()->user()->coach->hasBadge())
                <button
                    class="flex border-2 border-yellow-500 cursor-pointer h-12 px-3.5 w-fit items-center justify-center rounded-full bg-[#333333] text-white transition-colors hover:bg-[#444444]">
                    <span class="text-sm px-1.5">Request badge</span>
                </button>
                @endif
                <form action="{{ route('logout') ?? url('/logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-yellow-500 text-white transition-colors hover:bg-[#e04a1b] cursor-pointer">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') ?? url('/login') }}"
                    class="flex h-10 px-4 items-center justify-center rounded-xl bg-[#ff5520] text-white transition-colors hover:bg-[#e04a1b]">
                    Login
                </a>
            @endauth
        </div>

        <!-- Mobile Hamburger Button -->
        <div class="flex lg:hidden z-10">
            <button id="mobile-menu-btn" class="text-white hover:text-gray-300 focus:outline-none p-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </header>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden lg:hidden bg-[#1c1c1c] w-full absolute z-50 border-t border-white/10 shadow-lg">
        <div class="px-6 py-4 flex flex-col gap-4">
            <ul class="flex flex-col gap-4 text-white text-base">
                @auth
                    @if(auth()->user()->role && auth()->user()->role->title === 'admin')
                        <li><a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'text-[#ff5520] font-bold' : '' }}">Dashboard</a></li>
                    @else
                        @foreach($navLinks as $link)
                            <li><a href="{{ url('/' . $link['path']) }}" class="{{ request()->is($link['path']) ? 'text-[#ff5520] font-bold' : '' }}">{{ $link['name'] }}</a></li>
                        @endforeach
                    @endif
                @else
                    @foreach($navLinks as $link)
                        <li><a href="{{ url('/' . $link['path']) }}" class="{{ request()->is($link['path']) ? 'text-[#ff5520] font-bold' : '' }}">{{ $link['name'] }}</a></li>
                    @endforeach
                @endauth
            </ul>
            
            <!-- Mobile Actions -->
            <div class="border-t border-white/10 pt-4 flex flex-col gap-4">
                @auth
                    <div class="text-white font-medium">Hello, {{ auth()->user()->name }}</div>
                    
                    @if (auth()->user()->role && auth()->user()->role->title === 'coach' && auth()->user()->coach && !auth()->user()->coach->hasBadge())
                    <button class="w-full border-2 border-yellow-500 py-2 rounded-full bg-[#333333] text-white">
                        Request badge
                    </button>
                    @endif

                    <form action="{{ route('logout') ?? url('/logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="w-full bg-yellow-500 text-white py-2 rounded-xl text-center">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') ?? url('/login') }}" class="w-full bg-[#ff5520] text-white py-2 rounded-xl text-center">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function() {
        const menu = document.getElementById('mobile-menu');
        menu.classList.toggle('hidden');
    });
</script>