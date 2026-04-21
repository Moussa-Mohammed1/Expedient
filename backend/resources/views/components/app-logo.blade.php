<div>
    <h1 class="my-4 text-center text-2xl font-bold text-white">
    @if(Auth::check())
        <a href="{{ route('home') }}">Expedient.</a>
    @else
    <a href="{{ route('welcome') }}">Expedient.</a>
    @endif
    


</h1>
</div>