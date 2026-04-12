<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - home</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
    @include('layouts.header')
    <div class="min-h-screen bg-black pt-10 pb-24 font-sans">
    
    <section class="max-w-7xl mx-auto px-6 lg:px-10 mb-16">
        <div class="relative max-w-4xl mx-auto group">
            
            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-zinc-500 group-focus-within:text-yellow-500 transition-colors duration-200"></i>
            </div>
            <input 
                type="text" 
                placeholder="Search coaches, sports, or salles..." 
                class="w-full bg-[#111111] border-2 border-zinc-800 text-white text-sm rounded-2xl pl-12 pr-6 py-4 focus:outline-none focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/20 transition-all shadow-lg placeholder:text-zinc-600"
            >
            <div class="absolute inset-y-0 right-2 flex items-center">
                <button class="bg-[#1c1c1c] cursor-pointer hover:bg-yellow-500 hover:text-black text-white text-xs font-bold py-2 px-4 rounded-xl border border-zinc-700 transition-colors">
                    Search
                </button>
            </div>
        </div>
</section>

    <section class="max-w-7xl mx-auto px-6 lg:px-10 mb-20">
        <div class="flex items-end justify-between mb-6">
            <h2 class="text-lg md:text-xl font-bold text-white ">
                Top-Rated <span class="text-[#FBBF24]">Coaches</span>
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($topCoaches->take(8) as $coach)
                <div class="flex flex-col bg-[#111111] cursor-pointer border border-zinc-800 rounded-xl p-5  duration-300 group">
                    <div class="flex justify-center mb-4 relative">
                        <img 
                            src="{{ $coach->user?->avatar ? asset('/storage/users/profiles/' . $coach->user->avatar) : asset('assets/images/profile.jpeg')}}" 
                            alt="{{ $coach->user?->name ?? 'Coach' }}" 
                            class="w-16 h-16 rounded-full object-cover border-2 border-zinc-800 group-hover:border-yellow-500 transition-colors duration-200"
                        >
                        @if($coach->hasBadge())
                            <span class="absolute bottom-0 right-[35%] bg-yellow-500 text-black text-[9px] w-4 h-4 flex items-center justify-center rounded-full border-2 border-[#111111]">
                                <i class="fa-solid fa-check"></i>
                            </span>
                        @endif
                    </div>
                    
                    <div class="text-center flex-1">
                        <h3 class="text-white font-bold text-sm truncate">{{ $coach->user->name }}</h3>
                        <p class="text-zinc-500 text-xs mt-0.5 truncate">{{ $coach->specialty ?? 'General Fitness' }}</p>
                        
                        <div class="flex items-center justify-center gap-1 mt-2 mb-4">
                            <div class="flex text-yellow-500 text-[10px]">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                            <span class="text-zinc-500 text-[10px] font-medium">({{ $coach->reviews_count ?? 0 }})</span>
                        </div>
                    </div>

                    <a href="{{route('coaches.show', $coach->id) }}" class="w-full block text-center bg-[#1c1c1c] text-white text-xs font-bold py-2.5 rounded-lg border border-zinc-800 group-hover:bg-yellow-500 cursor-pointer group-hover:text-black group-hover:border-yellow-500 ">
                        View Profile
                    </a>
                </div>
            @empty
                
            @endforelse
        </div>
    </section>

    

</div>
</body>
</html>