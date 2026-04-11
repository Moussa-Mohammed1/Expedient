@php
    $buildCssFile = collect(glob(public_path('build/assets/app-*.css')))->first();
    $buildJsFile = collect(glob(public_path('build/assets/app-*.js')))->first();
@endphp

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@elseif ($buildCssFile)
    <link rel="stylesheet" href="{{ asset('build/assets/' . basename($buildCssFile)) }}">
    @if ($buildJsFile)
        <script type="module" src="{{ asset('build/assets/' . basename($buildJsFile)) }}"></script>
    @endif
@endif
