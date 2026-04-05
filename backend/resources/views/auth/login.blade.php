<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Expedient - login</title>
</head>

<body>
    <div class="min-h-screen w-full bg-black text-gray-900 text-sm flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-sm sm:max-w-md">
            <a href="{{ route('welcome') }}">
                <x-app-logo/>
            </a>
            <x-notification-popup/>
            <div
                class="w-full bg-white p-5 sm:p-8 rounded-lg shadow-yellow-400 shadow-2xl flex items-center justify-center">
                <div class="w-full">
                    <div class="text-black font-bold text-center">
                        Login 
                    </div>
                    <div class="mt-6 flex flex-col items-center">
                        <div class="w-full flex-1">
                            <form class="mx-auto w-full max-w-md" method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700" for="email"><span
                                            class="text-red-600 font-bold">* </span>Email</label>
                                    <input id="email" name="email"
                                        class="w-full px-6 py-3 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        type="email" placeholder="" value="{{ old('email') }}" />
                                    @error('email')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-600 font-semibold">Format: exemple@mail.com</p>
                                </div>
                                <div class="mt-4 space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700" for="password"><span
                                            class="text-red-600 font-bold">* </span>Password</label>
                                    <input id="password" name="password"
                                        class="w-full px-6 py-3 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        type="password" placeholder="" />
                                    @error('password')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <input id="show-password" type="checkbox" class="h-4 w-4 rounded border-gray-300" />
                                    <label for="show-password" class="text-sm text-gray-500">Show Password</label>
                                </div>
                                <div class="mt-4 flex justify-center">
                                    <button type="submit"
                                        class="text-white bg-black cursor-pointer  rounded-xl px-4 py-2 w-full inline-flex items-center justify-center gap-2">
                                        <span class="font-semibold">Login</span>
                                    </button>
                                </div>
                                <p class="mt-4 text-xs text-gray-600 text-center">
                                    don't have an account yet ?
                                    <a href="{{ route('register') }}"
                                        class="text-blue-600 font-sans hover:underline ml-1">Sign up</a>
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>