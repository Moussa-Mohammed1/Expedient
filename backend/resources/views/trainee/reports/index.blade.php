<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - home</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 font-sans antialiased min-h-screen">
    @include('layouts.header')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">

        <div class="mb-10 border-b border-zinc-800 pb-6">
            <h1 class="text-3xl font-bold text-white tracking-tight mb-2">My Reports</h1>
            <p class="text-zinc-400 text-sm">Track the status of the issues, profiles, or facilities you have reported
                to the Expedient admin team.</p>
        </div>

        <div class="space-y-6">
            @forelse ($reports as $report)
                @php
                    $proofName = basename($report->proof);
                    $proofUrl = asset('storage/' . ltrim($report->proof, '/'));
                @endphp

                <div
                    class="bg-[#111111] border border-zinc-800/80 rounded-xl p-5 sm:p-6 transition-colors hover:border-zinc-700">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-xs text-zinc-500 font-medium">
                                    Submitted {{ optional($report->created_at)->format('F d, Y') }}
                                </span>
                                <span class="text-xs text-zinc-600">Age: {{ optional($report->created_at)->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-white mt-2">{{ ucfirst($report->cause) }} Report</h3>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p
                            class="text-zinc-300 text-sm leading-relaxed bg-[#1c1c1c] p-4 rounded-lg border border-zinc-800/50 whitespace-pre-line">
                            {{ $report->description }}
                        </p>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-4 border-t border-zinc-800/50">

                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-zinc-500 uppercase tracking-wide">Evidence:</span>
                            <a href="{{ $proofUrl }}" download="{{ $proofName }}"
                                class="flex items-center gap-2 bg-[#1c1c1c] hover:bg-zinc-800 border border-zinc-700 text-zinc-300 text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                <i class="fa-solid fa-file-arrow-down text-zinc-500"></i> {{ $proofName }}
                            </a>
                        </div>

                        @if ($report->isCancelled)
                            <span class="text-xs font-semibold uppercase tracking-wide text-red-400">Cancelled</span>
                        @else
                            <form action="{{ route('reports.cancel', $report) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="text-[#ff5520] hover:text-white text-sm font-medium transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-xmark"></i> Cancel Report
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-[#111111] border border-zinc-800/80 rounded-xl p-8 text-center">
                    <i class="fa-solid fa-flag text-3xl text-zinc-600 mb-3"></i>
                    <h3 class="text-white font-semibold text-lg mb-2">No reports yet</h3>
                    <p class="text-zinc-500 text-sm">Submitted reports will appear here with their age and downloadable evidence.</p>
                </div>
            @endforelse
        </div>
    </div>

</body>

</html>