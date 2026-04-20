@php
    $adminUser = auth()->user();
    $adminAvatar = $adminUser?->avatar
        ? asset('/storage/users/profiles/' . ltrim($adminUser->avatar, '/'))
        : asset('assets/images/profile.jpeg');

    $navItems = [
        [
            'label' => 'Dashboard',
            'href' => url('/admin/dashboard'),
            'active' => request()->is('admin/dashboard'),
        ],
        [
            'label' => 'Users',
            'href' => url('/admin/users'),
            'active' => request()->is('admin/users') || request()->is('admin/users/*'),
        ],
        [
            'label' => 'Management',
            'href' => url('/admin/management'),
            'active' => request()->is('admin/management') || request()->is('admin/management/*'),
        ],
        [
            'label' => 'Reports',
            'href' => url('/admin/reports'),
            'active' => request()->is('admin/reports') || request()->is('admin/reports/*'),
        ],
        [
            'label' => 'Verifications',
            'href' => url('/admin/verifications'),
            'active' => request()->is('admin/verifications') || request()->is('admin/verifications/*'),
        ],
        [
            'label' => 'Communities',
            'href' => url('/admin/communities'),
            'active' => request()->is('admin/communities') || request()->is('admin/communities/*'),
        ],
    ];
@endphp

<header class="lg:hidden sticky top-0 z-40 h-16 bg-[#111111] border-b border-zinc-800/80 px-4">
    <div class="h-full grid grid-cols-3 items-center">
        <div class="flex justify-start">
            <button id="admin-sidebar-toggle" type="button" aria-controls="admin-sidebar" aria-expanded="false"
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg border border-zinc-700 bg-[#111111] text-zinc-200 hover:text-white hover:border-zinc-500">
                <span class="sr-only">Open admin menu</span>
                <i class="fa-solid fa-bars text-base"></i>
            </button>
        </div>

        <div class="flex justify-center">
            <x-app-logo />
        </div>

        <div class="flex justify-end">
            <x-profile-icon />
        </div>
    </div>
</header>

<div id="admin-sidebar-overlay" class="hidden fixed inset-0 z-40 bg-black/60 lg:hidden" aria-hidden="true"></div>

<aside id="admin-sidebar"
    class="hidden lg:flex w-64 h-full bg-[#111111] border-r border-zinc-800/80 flex-col fixed left-0 top-0 bottom-0 z-50 shadow-2xl">

    <div class="h-20 flex items-center justify-between px-6 border-b border-zinc-800/50 shrink-0">
        <x-app-logo />
        <div class="flex items-center gap-3">
            <span class="text-yellow-500 text-[9px] font-bold rounded-md">
                Admin
            </span>
            <button id="admin-sidebar-close" type="button"
                class="lg:hidden inline-flex items-center justify-center w-8 h-8 rounded-lg border border-zinc-700 text-zinc-300 hover:text-white hover:border-zinc-500">
                <span class="sr-only">Close admin menu</span>
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    </div>

    <nav class="flex-1 px-4 py-8 space-y-2 overflow-y-auto">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"
                class="flex items-center gap-3 px-3 py-3 rounded-xl font-medium group {{ $item['active'] ? 'bg-[#111] text-yellow-500 font-semibold' : 'text-zinc-400 hover:text-white hover:bg-[#1c1c1c]' }}">
                {{  $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="p-4 border-t border-zinc-800/50 shrink-0">
        <div class="flex items-center justify-between p-3 ">
            <div class="flex items-center gap-3">
                <img src="{{ $adminAvatar }}" alt="Admin Avatar"
                    class="w-9 h-9 rounded-full object-cover border border-zinc-700">
                <div>
                    <p class="text-xs font-bold text-white leading-tight truncate max-w-28">
                        {{ $adminUser?->name ?? 'Admin' }}
                    </p>
                    <p class="text-[10px] text-zinc-500 leading-tight">Admin</p>
                </div>
            </div>
            <form action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-zinc-500 hover:text-[#ff5520] tooltip" title="Log Out">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

<script>
    (function () {
        const sidebar = document.getElementById('admin-sidebar');
        const toggleButton = document.getElementById('admin-sidebar-toggle');
        const closeButton = document.getElementById('admin-sidebar-close');
        const overlay = document.getElementById('admin-sidebar-overlay');

        if (!sidebar || !toggleButton || !closeButton || !overlay) {
            return;
        }

        const openMenu = () => {
            sidebar.classList.remove('hidden');
            sidebar.classList.add('flex');
            overlay.classList.remove('hidden');
            toggleButton.setAttribute('aria-expanded', 'true');
            document.body.classList.add('overflow-hidden');
        };

        const closeMenu = () => {
            sidebar.classList.add('hidden');
            sidebar.classList.remove('flex');
            overlay.classList.add('hidden');
            toggleButton.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('overflow-hidden');
        };

        toggleButton.addEventListener('click', () => {
            if (sidebar.classList.contains('hidden')) {
                openMenu();
                return;
            }

            closeMenu();
        });

        closeButton.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !sidebar.classList.contains('hidden')) {
                closeMenu();
            }
        });
    })()
</script>