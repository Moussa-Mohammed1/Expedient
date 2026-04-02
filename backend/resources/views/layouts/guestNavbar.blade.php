@php
    $navLinks = [
        ['name' => 'Coaches', 'id' => 'coaches'],
        ['name' => 'Communities', 'id' => 'communities'],
        ['name' => 'About Us', 'id' => 'about']
    ];
@endphp

<div class="relative w-full z-50">
    <header class="relative flex w-full items-center justify-between bg-[#111111] px-6 lg:px-8 py-5 font-sans antialiased">
        
        <div class="z-10">
            <x-app-logo/>
        </div>

        <nav class="hidden lg:flex fixed w-auto min-w-[50%] hover:shadow-sm left-1/2 top-4 -translate-x-1/2 shadow-2xs items-center justify-center rounded-full bg-[#1c1c1c] px-8 py-3 shadow-lime-500 shadow-xs ring-1 ring-white/5 transition z-[70]">
            <ul class="flex items-center justify-around gap-8 text-sm font-medium tracking-wide w-full">
                @foreach($navLinks as $link)
                    <li class="relative">
                        <a href="#{{ $link['id'] }}" class="text-white hover:text-gray-300 transition-colors">
                            {{ $link['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="hidden lg:flex items-center gap-3 z-10">
            <a href="{{ route('login') }}" class="flex h-10 px-4 items-center justify-center font-bold text-xs rounded-lg bg-yellow-500 text-black transition-colors hover:bg-yellow-600">
                Login / Sign Up 
            </a>
        </div>

        <div class="flex lg:hidden z-10">
            <button id="mobile-menu-btn" class="text-white hover:text-gray-300 focus:outline-none p-2">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
    </header>

    <div id="mobile-menu" class="hidden lg:hidden bg-[#1c1c1c] w-full absolute z-40 border-t border-white/10 shadow-lg">
        <div class="px-6 py-4 flex flex-col gap-4">
            <ul class="flex flex-col gap-4 text-white text-base">
                @foreach($navLinks as $link)
                    <li>
                        <a href="#" class="hover:text-gray-300 transition-colors">{{ $link['name'] }}</a>
                    </li>
                @endforeach
            </ul>

            <div class="border-t border-white/10 pt-4 flex flex-col gap-4">
                <a href="#" class="w-full bg-yellow-500 text-black font-bold py-2 rounded-xl text-center transition-colors hover:bg-yellow-600">
                    Login / Sign Up
                </a>
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