<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - home</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 pb-16">
    @include('layouts.header')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-6">
        <a href="{{ route('communities.show', $community) }}"
            class="inline-flex items-center text-sm font-medium text-zinc-400 hover:text-white transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Back to {{ $community->title }}
        </a>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 space-y-6">

        <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-5 sm:p-7 shadow-lg">
            <div class="flex justify-between items-start mb-5">
                <div class="flex items-center gap-3 sm:gap-4">
                    <img src="{{ $post->user?->avatar ? asset('storage/users/profiles/' . ltrim($post->user->avatar, '/')) : asset('assets/images/profile.jpeg') }}"
                        alt="Author Avatar" class="w-12 h-12 rounded-full border border-zinc-700">
                    <div>
                        <h4 class="text-white font-bold text-base flex items-center gap-2">
                            {{ $post->user?->name ?? 'Unknown' }}
                            <span
                                class="bg-[#1c1c1c] text-[#FBBF24] text-[10px] px-2 py-0.5 rounded uppercase tracking-wider border border-zinc-700">{{ $post->user->communityRole($community) }}</span>
                        </h4>
                        <span class="text-xs text-zinc-500">{{ optional($post->created_at)->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="relative group" tabindex="0">
                    <button
                        class="text-zinc-500 hover:text-white p-2 rounded-full outline-none cursor-pointer transition-colors">
                        <i class="fa-solid fa-ellipsis"></i>
                    </button>
                    <div
                        class="absolute right-0 mt-1 w-36 bg-[#1c1c1c] border border-zinc-700 rounded-xl shadow-2xl invisible opacity-0 group-focus-within:visible group-focus-within:opacity-100 transition-all z-10 overflow-hidden">
                        <ul class="py-1 text-sm text-zinc-300">
                            <li><a href="#" class="block px-4 py-2 hover:bg-zinc-800 transition-colors">Copy Link</a>
                            </li>
                            <li><a href="#" class="block px-4 py-2 hover:bg-zinc-800 transition-colors">Report Post</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <p class="text-white text-base sm:text-lg leading-relaxed mb-5 whitespace-pre-line">
                {{ $post->content }}
            </p>

            @if ($post->images->isNotEmpty())
                <div
                    class="grid {{ $post->images->count() > 1 ? 'grid-cols-2' : 'grid-cols-1' }} gap-2 mb-6 rounded-xl overflow-hidden border border-zinc-800">
                    @foreach ($post->images as $image)
                        <img src="{{ asset('storage/' . ltrim($image->content, '/')) }}" alt="Post image"
                            class="w-full h-48 sm:h-64 object-cover">
                    @endforeach
                </div>
            @endif

            <div class="flex items-center justify-between pt-4 border-t border-zinc-800/80">
                <div class="flex items-center gap-6">
                    <button class="flex items-center gap-2 text-[#ff5520] transition-colors group">
                        <i class="fa-solid fa-fire text-lg group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-bold">{{ $post->likes->count() }}</span>
                    </button>
                    <div class="flex items-center gap-2 text-zinc-300">
                        <i class="fa-regular fa-comment text-lg"></i>
                        <span class="text-sm font-bold">{{ $post->comments->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#111111] border border-zinc-800/80 rounded-2xl p-5 sm:p-7">

            <form action="{{ route('comments.store', $post) }}" method="POST" class="flex gap-3 sm:gap-4 mb-8">
                @csrf
                <img src="{{ auth()->user()?->avatar ? asset('storage/users/profiles/' . ltrim(auth()->user()->avatar, '/')) : asset('assets/images/profile.jpeg') }}"
                    alt="Your Avatar" class="w-10 h-10 rounded-full border border-zinc-700 shrink-0">
                <div class="flex-1 flex flex-col items-end">
                    <textarea name="content" rows="2" placeholder="Add to the discussion..."
                        class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] resize-none transition-colors mb-3">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="w-full mb-3 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                        class="bg-[#d1fa48] hover:bg-[#b4d83d] text-black font-bold px-5 py-2 rounded-xl text-sm transition-colors">
                        Post Comment
                    </button>
                </div>
            </form>

            <div class="space-y-6">

                @forelse ($post->comments as $comment)
                    @php
                        $canManageComment = auth()->id() === $comment->user_id;
                    @endphp

                    <div class="flex gap-3 sm:gap-4" data-comment-card data-comment-id="{{ $comment->id }}">
                        <img src="{{ $comment->user?->avatar ? asset('storage/users/profiles/' . ltrim($comment->user->avatar, '/')) : asset('assets/images/profile.jpeg') }}"
                            alt="Commenter Avatar" class="w-10 h-10 rounded-full border border-zinc-700 shrink-0">
                        <div class="flex-1">
                            <div class="bg-[#1c1c1c] rounded-2xl rounded-tl-sm p-4 border border-zinc-800/50"
                                data-comment-shell>
                                <div class="flex justify-between items-start mb-1 gap-3">
                                    <h5 class="text-white font-bold text-sm">{{ $comment->user?->name ?? 'Unknown' }}</h5>

                                    @if ($canManageComment)
                                        <div class="relative group shrink-0" tabindex="0">
                                            <button type="button"
                                                class="text-zinc-500 hover:text-white px-2 outline-none cursor-pointer">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </button>
                                            <div
                                                class="absolute right-0 mt-1 w-36 bg-[#1c1c1c] border border-zinc-700 rounded-xl shadow-2xl invisible opacity-0 group-focus-within:visible group-focus-within:opacity-100 group-hover:visible group-hover:opacity-100 transition-all z-20 overflow-hidden">
                                                <ul class="py-1 text-sm text-zinc-300">
                                                    <li>
                                                        <button type="button"
                                                            class="comment-update-trigger block w-full text-left px-4 py-2 hover:bg-zinc-800 transition-colors"
                                                            data-comment-id="{{ $comment->id }}"
                                                            data-comment-content="{{ e($comment->content) }}"
                                                            data-update-url="{{ route('comments.update', ['post' => $post, 'comment' => $comment]) }}">
                                                            Update
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('comments.destroy', ['post' => $post, 'comment' => $comment]) }}"
                                                            method="POST" onsubmit="return confirm('Delete this comment?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2 hover:bg-zinc-800 transition-colors text-red-300">
                                                                Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <p class="comment-display text-zinc-300 text-sm leading-relaxed whitespace-pre-line">
                                    {{ $comment->content }}
                                </p>

                                <form class="comment-edit-form hidden mt-3"
                                    action="{{ route('comments.update', ['post' => $post, 'comment' => $comment]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <textarea name="content" rows="3"
                                        class="comment-edit-input w-full bg-[#111111] border border-zinc-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] resize-none transition-colors"></textarea>
                                    @error('content')
                                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                                    @enderror
                                    <div class="mt-3 flex items-center justify-end gap-2">
                                        <button type="button"
                                            class="comment-cancel-edit bg-zinc-700 hover:bg-zinc-600 text-white font-semibold px-4 py-2 rounded-xl text-sm transition-colors">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="comment-save-edit bg-[#d1fa48] hover:bg-[#b4d83d] text-black font-bold px-5 py-2 rounded-xl text-sm transition-colors">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="flex items-center gap-4 mt-2 ml-2 text-xs font-medium text-zinc-500">
                                <span class="text-zinc-600">{{ optional($comment->created_at)->diffForHumans() }}</span>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center text-zinc-500 text-sm py-6 border border-dashed border-zinc-800 rounded-xl">
                        No comments yet. Start the discussion.
                    </div>
                @endforelse
            </div>

        </div>
    </div>

    <script>

        const commentCards = document.querySelectorAll('[data-comment-card]');

        const exitEditMode = (card) => {
            const display = card.querySelector('.comment-display');
            const editForm = card.querySelector('.comment-edit-form');
            display.classList.remove('hidden');
            editForm.classList.add('hidden');
        };

        const enterEditMode = (card, trigger) => {
            const display = card.querySelector('.comment-display');
            const editForm = card.querySelector('.comment-edit-form');
            const input = card.querySelector('.comment-edit-input');

            display.classList.add('hidden');
            editForm.classList.remove('hidden');
            input.value = trigger.dataset.commentContent || display.textContent.trim();
            input.focus();
        };

        commentCards.forEach((card) => {
            const updateTrigger = card.querySelector('.comment-update-trigger');
            const cancelButton = card.querySelector('.comment-cancel-edit');
            const editForm = card.querySelector('.comment-edit-form');

            if (updateTrigger) {
                updateTrigger.addEventListener('click', () => enterEditMode(card, updateTrigger));
            }

            if (cancelButton) {
                cancelButton.addEventListener('click', () => exitEditMode(card));
            }

            if (editForm) {
                editForm.addEventListener('submit', () => {
                    const display = card.querySelector('.comment-display');
                    const input = card.querySelector('.comment-edit-input');
                    display.textContent = input.value;
                });
            }
        });

    </script>

</body>

</html>