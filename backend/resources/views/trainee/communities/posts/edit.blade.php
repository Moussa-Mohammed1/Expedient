<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - edit post</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="bg-black text-gray-300 font-sans antialiased min-h-screen flex flex-col">
    @include('layouts.header')

    @php
        $currentUser = auth()->user();
        $cancelRoute = route('communities.show', $community->id);
        $avatarUrl = $currentUser?->avatar
            ? asset('storage/users/profiles/' . ltrim($currentUser->avatar, '/'))
            : asset('assets/images/profile.jpeg');
    @endphp

    <div class="max-w-3xl w-full mx-auto px-4 sm:px-6 py-6 flex items-center justify-between">
        <a href="{{ $cancelRoute }}"
            class="inline-flex items-center text-sm font-medium text-zinc-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to Community
        </a>
        <span class="text-white font-bold text-lg tracking-tight">Edit Post</span>
        <div class="w-16"></div>
    </div>

    <div class="max-w-3xl w-full mx-auto px-4 sm:px-6 pb-12 flex-1 flex flex-col">
        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data"
            class="bg-[#111111] border border-zinc-800/80 rounded-lg flex flex-col flex-1 shadow-2xl overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-5 sm:p-6 border-b border-zinc-800/50 flex flex-col sm:flex-row sm:items-center gap-4 bg-[#111111]">
                <div class="flex items-center gap-3">
                    <img src="{{ $avatarUrl }}" alt="User Avatar" class="w-12 h-12 rounded-full border border-zinc-700 object-cover">
                    <div>
                        <h3 class="text-white font-bold text-sm">{{ $currentUser?->name ?? 'You' }}</h3>
                        <p class="text-xs text-zinc-500">Editing as post owner</p>
                    </div>
                </div>

                <div class="hidden sm:block text-zinc-600"><i class="fa-solid fa-caret-right"></i></div>

                <div class="flex-1">
                    <div class="w-full sm:w-auto inline-flex items-center bg-[#1c1c1c] border border-zinc-700 text-white text-sm font-medium rounded-lg px-4 py-2.5">
                        {{ $community->title }}
                    </div>
                </div>
            </div>

            <div class="flex-1 p-5 sm:p-6 bg-[#111111] flex flex-col">
                <textarea name="content" placeholder="What's on your mind? Share your workout, PR, or ask for advice..."
                    class="w-full h-48 sm:h-auto sm:flex-1 bg-transparent border-none text-white text-base sm:text-lg resize-none focus:outline-none focus:ring-0 placeholder-zinc-600">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror

                @if ($post->images->isNotEmpty())
                    <div class="mt-6">
                        <p class="text-xs uppercase tracking-wider text-zinc-500 mb-3 font-bold">Current Images</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ($post->images as $image)
                                <div class="relative h-28 rounded-lg overflow-hidden border border-zinc-700">
                                    <img src="{{ asset('storage/' . ltrim($image->content, '/')) }}" alt="Current post image"
                                        class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div id="image-preview-grid" class="mt-6 grid grid-cols-2 sm:grid-cols-3 gap-3"></div>

                <div class="mt-3 flex items-center justify-between gap-3">
                    <label
                        class="h-24 w-24 bg-[#1c1c1c] border border-zinc-700 hover:border-zinc-500 rounded-lg flex flex-col items-center justify-center text-zinc-500 hover:text-white transition-colors cursor-pointer">
                        <i class="fa-solid fa-plus text-xl mb-1"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wider">Add</span>
                        <input id="images-input" type="file" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                    <p id="images-help" class="text-xs text-zinc-500">Max 3 images</p>
                </div>
                @error('images')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div class="p-4 sm:p-5 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <button id="attach-images-button" type="button"
                        class="text-zinc-400 hover:text-[#d1fa48] bg-[#222222] hover:bg-zinc-800 w-10 h-10 rounded-lg flex items-center justify-center transition-colors tooltip"
                        title="Attach Image">
                        <i class="fa-solid fa-image text-lg"></i>
                    </button>
                </div>

                <button type="submit"
                    class="bg-[#d1fa48] hover:bg-[#b4d83d] text-black font-bold px-8 py-2.5 rounded-lg text-sm transition-colors flex items-center gap-2">
                    Update Post <i class="fa-solid fa-pen-to-square"></i>
                </button>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const maxImages = 3;
            const fileInput = document.getElementById('images-input');
            const attachButton = document.getElementById('attach-images-button');
            const previewGrid = document.getElementById('image-preview-grid');
            const imagesHelp = document.getElementById('images-help');

            if (!fileInput || !previewGrid || !imagesHelp) {
                return;
            }

            const renderPreviews = () => {
                previewGrid.innerHTML = '';

                const files = Array.from(fileInput.files || []);
                files.forEach((file) => {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'relative h-24 rounded-lg overflow-hidden border border-zinc-700';

                        const image = document.createElement('img');
                        image.className = 'w-full h-full object-cover';
                        image.alt = file.name;
                        image.src = String(event.target && event.target.result ? event.target.result : '');

                        wrapper.appendChild(image);
                        previewGrid.appendChild(wrapper);
                    };
                    reader.readAsDataURL(file);
                });

                const count = files.length;
                imagesHelp.textContent = count + '/' + maxImages + ' images selected';
            };

            fileInput.addEventListener('change', () => {
                const files = Array.from(fileInput.files || []);
                if (files.length > maxImages) {
                    const dt = new DataTransfer();
                    files.slice(0, maxImages).forEach((file) => dt.items.add(file));
                    fileInput.files = dt.files;
                }
                renderPreviews();
            });

            if (attachButton) {
                attachButton.addEventListener('click', () => fileInput.click());
            }
        })();
    </script>
</body>

</html>
