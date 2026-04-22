<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-black text-gray-100 flex items-center justify-center min-h-screen p-6">
    <section class="py-4 font-sans">
        <div class="max-w-md w-full p-8 rounded-2xl shadow-2xl text-center">

            <h1 class="text-2xl font-bold tracking-tight text-white mb-2">Expedient</h1>
            <h2 class="text-xl font-semibold text-gray-200">Account Suspended</h2>
            <p class="mt-2 text-sm text-gray-400">Your access has been restricted by the Expedient Admin team.</p>

            <hr class="my-6 border-gray-800">

            <div class="text-left space-y-4">
                <div>
                    <label class="text-xs font-uppercase tracking-widest text-gray-500 uppercase">User Details</label>
                    <div class="mt-1 flex items-center space-x-3 bg-white/5 p-3 rounded-lg border border-white/10">
                        <div
                            class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center text-lg font-bold text-white">
                            JD
                        </div>
                        <div>
                            <p class="text-sm font-medium text-white">John Doe</p>
                            <p class="text-xs text-gray-400">johndoe@example.com</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-xs font-uppercase tracking-widest text-gray-500 uppercase">Reason for
                        Suspension</label>
                    <div class="mt-1 bg-red-500/5 border border-red-500/20 p-4 rounded-lg">
                        <p class="text-sm text-gray-300 leading-relaxed italic">
                            "Violation of community guidelines regarding professional conduct on the sports facility
                            booking platform."
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button
                    class="w-full py-3 px-4 bg-white text-black font-bold rounded-xl hover:bg-gray-200 transition-colors duration-200">
                    Log Out
                </button>
            </div>
        </div>
    </section>
</body>