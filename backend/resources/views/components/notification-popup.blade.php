<div id="popup">
    @if(session('message'))
        <div class="fixed top-5 right-5 bg-yellow-500 text-black text-sm sm:text-base px-4 py-2 rounded shadow">
            {{ session('message') }}
        </div>
    @endif
</div>

@if ($errors->any())
    <div id="validation-notification" class="fixed top-6 z-50 pointer-events-none left-1/2 text-sm sm:text-lg -translate-x-1/2
                        bg-red-900/80 text-red-100 px-7 py-3 rounded-full shadow-lg
                        backdrop-blur-md border border-red-300/30 max-w-[90vw] whitespace-nowrap overflow-hidden text-ellipsis
                        opacity-0 -translate-y-5
                        transition-all duration-700 ease-in-out">
        {{ $errors->first() }}
    </div>
@endif

@if (session('success'))
    <div id="success-notification" class="fixed top-6 z-50 pointer-events-none left-1/2 text-sm sm:text-lg -translate-x-1/2
                        bg-yellow-500 backdrop-blur-sm text-black font-semibold px-7 py-3 rounded-full shadow-lg
                         border border-white/10
                        opacity-0 -translate-y-5
                        transition-all duration-700 ease-in-out truncate">

        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="error-notification" class="fixed top-6 w-fit z-50 pointer-events-none left-1/2 text-sm sm:text-lg -translate-x-1/2
                        bg-red-900/80 text-red-100 px-7 py-3 rounded-full shadow-lg
                        backdrop-blur-md border border-red-300/30
                        opacity-0 -translate-y-5
                        transition-all duration-700 ease-in-out">

        {{ session('error') }}
    </div>
@endif


<script>
    const animateNotification = (elementId) => {
        const notification = document.getElementById(elementId);

        if (!notification) {
            return;
        }

        requestAnimationFrame(() => {
            notification.classList.remove('opacity-0', '-translate-y-5', 'pointer-events-none');
            notification.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
        });

        setTimeout(() => {
            notification.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
            notification.classList.add('opacity-0', '-translate-y-5', 'pointer-events-none');
        }, 3000);
    };

    animateNotification('success-notification');
    animateNotification('error-notification');
    animateNotification('validation-notification');
</script>