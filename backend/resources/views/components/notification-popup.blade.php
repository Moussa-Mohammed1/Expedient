<div id="popup">
    @if(session('success'))
        <div class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-5 right-5 bg-yellow-500 text-black px-4 py-2 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    @if(session('message'))
        <div class="fixed top-5 right-5 bg-yellow-500 text-black px-4 py-2 rounded shadow">
            {{ session('message') }}
        </div>
    @endif
</div>
