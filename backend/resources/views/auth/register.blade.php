<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Expedient - login</title>
</head>
<body>
    <div class="flex h-screen w-full overflow-hidden bg-white">
        <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-6 h-full overflow-y-auto">

            <div class="w-full max-w-xs flex flex-col my-auto">
                
                <h1 class="text-2xl font-black tracking-tight text-gray-900 text-center mb-4">
                    Create account 
                </h1>

                <form class="flex flex-col gap-1.5" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-0.5 uppercase tracking-wide">Full Name</label>
                        <input 
                            type="text" 
                            placeholder="Your name" 
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-900 bg-gray-50 placeholder-gray-400 outline-none focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all" />
                        <div class="min-h-4 pt-1">
                            @error('name')
                                <p class="text-[11px] text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-0.5 uppercase tracking-wide">Email</label>
                        <input 
                            name="email"
                            value="{{ old('email') }}"
                            type="email" placeholder="email@example.com"
                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-900 bg-gray-50 placeholder-gray-400 outline-none focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all" />
                        <div class="min-h-4 pt-1">
                            @error('email')
                                <p class="text-[11px] text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-0.5 uppercase tracking-wide">Password</label>
                        <input 
                            name="password"
                            type="password" placeholder="********"
                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-900 bg-gray-50 placeholder-gray-400 outline-none focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all" />
                        <div class="min-h-4 pt-1">
                            @error('password')
                                <p class="text-[11px] text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-0.5 uppercase tracking-wide">Confirm Password</label>
                        <input 
                            name="password_confirmation"
                            type="password" placeholder="********"
                            class="w-full border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-900 bg-gray-50 placeholder-gray-400 outline-none focus:bg-white focus:ring-2 focus:ring-black focus:border-black transition-all" />
                        <div class="min-h-4"></div> 
                    </div>

                    <div class="flex items-start bg-gray-50 p-2.5 border border-gray-100 rounded-lg">
                        <div class="flex items-center h-4">
                            <input 
                                id="is-coach-checkbox" 
                                name="isCoach"
                                value="1"
                                type="checkbox" 
                                class="w-4 h-4 text-black bg-white border-gray-300 rounded focus:ring-black cursor-pointer peer" />
                        </div>
                        <div class="ml-2 flex flex-col">
                            <label for="is-coach-checkbox" class="text-[13px] font-medium text-gray-800 cursor-pointer leading-tight pt-0.5">
                                I am a specialized <span class="text-red-600 font-bold uppercase text-[11px]">coach</span>  
                            </label>
                            <p class="text-gray-500 text-[11px] mt-1 transition-all hidden peer-checked:block"> 
                                You can request your verification badge after registration.
                            </p>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-black cursor-pointer text-white font-bold text-sm rounded-lg py-2 mt-2 transition-colors hover:bg-gray-800 focus:ring-2 focus:ring-offset-2 focus:ring-black disabled:bg-gray-300 disabled:cursor-not-allowed">
                        <span>Sign Up</span>
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Already have an account?
                    <a href="{{ route('login') }}" class="font-bold text-black hover:text-gray-700 underline underline-offset-4 ml-1 transition-colors">
                        Log In
                    </a>
                </p>

            </div>
        </div>

        <div class="hidden md:block md:w-1/2 relative h-full overflow-hidden bg-gray-200">
            <div class="absolute inset-0 bg-cover bg-center" style="background: url('https://i.pinimg.com/736x/0e/e6/23/0ee62381051e0f0be8a2cec01e6baf39.jpg') no-repeat center center; background-size: cover;">
                <div class="absolute inset-0 bg-linear-to-br from-black/20 to-black/60"></div>
            </div>

            <div class="absolute top-10 right-10">
                <p class="text-white font-black pl-6 text-xl tracking-widest uppercase drop-shadow-md"
                    style="font-family:'Barlow Condensed', sans-serif;">
                    SHARP. FAST. That's what expedient meant for
                </p>
            </div>
        </div>
    </div>
</body>
</html>
