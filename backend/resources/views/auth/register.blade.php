<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <title>Expedient - login</title>
</head>

<body>
    <div
        class="h-screen w-full overflow-hidden bg-black text-gray-900 text-xs flex items-center justify-center p-3 sm:p-4">
        <div class="w-full max-w-sm sm:max-w-md">
            <x-app-logo />
            <div
                class="w-full bg-white p-4 sm:p-5 rounded-lg shadow-yellow-400 shadow-2xl flex items-center justify-center">
                <div class="w-full">
                    <div class="text-black font-bold text-center">Create account</div>
                    <div class="mt-4 flex flex-col items-center">
                        <div class="w-full flex-1">
                            <form class="mx-auto w-full max-w-md" method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700" for="name"><span
                                            class="text-red-600 font-bold">* </span>Full Name</label>
                                    <input id="name" name="name" type="text" value="{{ old('name') }}"
                                        class="w-full px-4 py-2 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        placeholder="" />
                                    @error('name')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-3 space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700" for="email"><span
                                            class="text-red-600 font-bold">* </span>Email</label>
                                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                                        class="w-full px-4 py-2 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        placeholder="" />
                                    @error('email')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-3 space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700" for="password"><span
                                            class="text-red-600 font-bold">* </span>Password</label>
                                    <input id="password" name="password" type="password"
                                        class="w-full px-4 py-2 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        placeholder="" />
                                    @error('password')
                                        <p class="text-xs text-red-600 font-semibold">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-3 space-y-1">
                                    <label class="block text-sm font-semibold text-gray-700"
                                        for="password_confirmation"><span class="text-red-600 font-bold">*
                                        </span>Confirm Password</label>
                                    <input id="password_confirmation" name="password_confirmation" type="password"
                                        class="w-full px-4 py-2 rounded-lg font-medium bg-gray-100 border border-gray-200 placeholder-gray-500 text-sm focus:outline-none focus:border-gray-400 focus:bg-white"
                                        placeholder="" />
                                </div>

                                <div class="mt-3 flex items-start gap-2">
                                    <input id="is-coach-checkbox" name="isCoach" value="1" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 mt-0.5" />
                                    <div>
                                        <label for="is-coach-checkbox" class="text-sm text-gray-700">I am a specialized
                                            <span class="text-red-600 font-bold">coach</span></label>
                                        <p class="text-xs text-gray-500 mt-1">You can request your verification badge
                                            after registration.</p>
                                    </div>
                                </div>

                                <div class="mt-3 flex justify-center">
                                    <button type="submit"
                                        class="text-white bg-black cursor-pointer rounded-xl px-4 py-1.5 w-full inline-flex items-center justify-center gap-2">
                                        <span class="font-semibold">Sign Up</span>
                                    </button>
                                </div>

                                <p class="mt-3 text-xs text-gray-600 text-center">
                                    already have an account?
                                    <a href="{{ route('login') }}"
                                        class="text-blue-600 font-sans hover:underline ml-1">Log in</a>
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