<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expedient - Coaches</title>
    @include('layouts.assets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="bg-black text-gray-300 font-sans antialiased min-h-screen ">
    @include('layouts.header')

    @php
        $defaultAvatarUrl = asset('assets/images/profile.jpeg');
        $avatarUrl = $coachUser->avatar
            ? asset('storage/users/profiles/' . ltrim($coachUser->avatar, '/'))
            : $defaultAvatarUrl;
        $specialities = $coach->specialities;
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="flex flex-col lg:flex-row gap-6">

            <div class="w-full lg:w-1/3 flex flex-col gap-6">

                <div class="bg-[#111111] border border-zinc-800/80 rounded-lg p-5 lg:p-6 relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="relative inline-block shrink-0">
                            <img src="{{ $avatarUrl }}" alt="Coach Avatar"
                                onerror="this.onerror=null;this.src='{{ $defaultAvatarUrl }}';"
                                class="h-24 w-24 rounded-full object-cover border border-zinc-700 shadow-sm">
                            @if($coach->hasBadge)
                                <div class="absolute -bottom-3 -right-3 bg-[#111111] rounded-full p-1.5">
                                    <div
                                        class="bg-[#FBBF24] text-black w-7 h-7 rounded-full flex items-center justify-center text-xs shadow-lg">
                                        <i class="fa-solid fa-check"></i>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 flex-wrap">
                            <h1 class="text-2xl font-bold text-white tracking-tight leading-none">{{ $coachUser->name }}</h1>
                            <p class="text-zinc-400 text-sm font-medium flex items-center gap-2">
                                <i class="fa-solid fa-location-dot text-xs text-white"></i>
                                {{ $coachUser->localisation ?: 'No localisation provided' }}
                            </p>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-between bg-[#1c1c1c] rounded-lg p-3 border border-zinc-800/50 mb-5">
                        <div class="flex items-center gap-2 text-[#FBBF24] text-base">
                            <i class="fa-solid fa-star"></i>
                            <span
                                class="text-white font-bold leading-none mt-0.5">{{ number_format($rating, 1) }}</span>
                        </div>
                        <div class="h-8 w-px bg-zinc-800"></div>
                        <div class="text-right">
                            <span class="text-xs text-zinc-400 block">Reviews</span>
                            <span class="text-xs text-green-400 font-medium">{{ $coach->reviews_count }} approved</span>
                        </div>
                    </div>

                    <div class="mb-5">
                        <h3 class="text-[11px] font-bold text-zinc-600 uppercase tracking-wider mb-2">Specialities</h3>
                        <div class="flex flex-wrap gap-2">
                            @forelse($specialities as $speciality)
                                <span
                                    class="inline-flex items-center rounded-md bg-yellow-500/10 px-2 py-1 text-[11px] font-medium text-[#FBBF24] ring-1 ring-inset ring-yellow-500/20">
                                    {{ $speciality->title }} ({{ $speciality->pivot->experienceYears ?? 0 }} years)
                                </span>
                            @empty
                                <span class="text-xs text-zinc-500">No speciality assigned yet.</span>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-6 space-y-3">
                        <h3 class="text-xs font-bold text-zinc-600 uppercase tracking-wider mb-2">Coach Information</h3>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 flex items-center justify-center text-white text-sm">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-[11px] text-zinc-500 font-medium uppercase">Email</p>
                                <p class="text-xs text-zinc-200 truncate">{{ $coachUser->email }}</p>
                            </div>
                            @if($coachUser->email_verified_at)
                                <div class="text-[#d1fa48]" title="Email Verified">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 flex items-center justify-center text-white text-sm">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-zinc-500 font-medium uppercase">Phone</p>
                                <p class="text-xs text-zinc-200">{{ $coachUser->phone ?: 'Not provided' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 flex items-center justify-center text-white text-sm">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <p class="text-[11px] text-zinc-500 font-medium uppercase ">Account Role
                                </p>
                                <p class="text-xs text-zinc-200 capitalize">{{ $coachUser->role->title ?? 'Coach' }}
                                    @if (auth()->user()->isAdmin())
                                        <span class="ml-1 text-[11px] text-zinc-500">(ID: {{ $coachUser->role_id }})</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="w-full lg:w-2/3 flex flex-col gap-6">

                <div class="border-b border-zinc-800 pb-4">
                    <h2 class="text-xl font-bold text-white">Trainees Reviews <span
                            class="text-zinc-500 text-base font-medium ml-2">({{ $coach->reviews_count }})</span></h2>
                </div>

                <div class="bg-[#111111] border border-zinc-800/80 rounded-lg p-4 shadow-sm">
                    @if(session('success'))
                        <div
                            class="mb-3 rounded-md border border-emerald-500/20 bg-emerald-900/20 px-3 py-2 text-xs text-emerald-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-3 rounded-md border border-red-500/20 bg-red-900/20 px-3 py-2 text-xs text-red-300">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('opinions.store', $coach->id) }}" method="POST">
                        @csrf
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-3 gap-3">
                            <h3 class="text-white font-semibold text-sm">Add a Review</h3>

                            <div class="flex items-center gap-2">
                                <span class="text-xs text-zinc-500 font-medium">Rate:</span>
                                <input type="hidden" name="rate" id="opinion-rate" value="5">
                                <div id="opinion-star-rating"
                                    class="star-rating flex flex-row-reverse justify-end gap-1 text-lg text-zinc-600 cursor-pointer">
                                    @for($i = 5; $i >= 1; $i--)
                                        <button type="button" data-rate="{{ $i }}" class="rating-star"
                                            aria-label="Rate {{ $i }} stars">
                                            <i class="fa-solid fa-star transition-colors"></i>
                                        </button>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <textarea name="content" rows="3"
                                placeholder="Share your experience working with this coach..."
                                class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-sm p-3 text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-[#FBBF24] focus:ring-1 focus:ring-[#FBBF24] transition-all resize-none"></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-[11px] leading-relaxed text-zinc-500 max-w-md">
                                <span class="text-white font-semibold">Note: </span> Respectful reviews are welcome.
                                Inappropriate or abusive content may be removed and may lead to account penalties.
                            </p>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-[#d1fa48] hover:bg-[#b4d83d] text-black font-bold py-2 px-5 rounded-lg text-xs transition-colors flex items-center gap-2">
                                    <i class="fa-solid fa-paper-plane"></i> Post Review
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="space-y-4 max-h-[70vh] overflow-y-auto [scrollbar-width:none] pr-1">
                    @forelse($reviews as $review)
                        <div id="review-{{ $review->id }}"
                            class="group bg-[#111111] border border-zinc-800/60 rounded-lg p-4 hover:border-zinc-700 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $review->author?->avatar ? asset('storage/users/profiles/' . ltrim($review->author->avatar, '/')) : $defaultAvatarUrl }}"
                                        onerror="this.onerror=null;this.src='{{ $defaultAvatarUrl }}';"
                                        alt="Author" class="h-9 w-9 rounded-full object-cover">
                                    <div>
                                        <h4 class="text-white font-semibold text-xs">
                                            {{ $review->author?->name ?? 'Anonymous' }}
                                        </h4>
                                        <span
                                            class="text-[11px] text-zinc-500">{{ optional($review->created_at)->diffForHumans() }}</span>
                                    </div>
                                </div>

                                @php
                                    $isOwner = (int) auth()->id() === (int) $review->author_id;
                                @endphp
                                <div class="relative {{ $isOwner ? 'visible' : 'invisible' }}">
                                    <button type="button" data-menu-toggle="review-menu-{{ $review->id }}"
                                        class="h-8 w-8 rounded-md border cursor-pointer border-zinc-700 bg-[#1c1c1c] text-zinc-300 hover:text-white hover:border-zinc-600">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <div id="review-menu-{{ $review->id }}"
                                        class="hidden absolute right-0 mt-2 w-36 rounded-md border border-zinc-700 bg-[#171717] p-1 z-10">
                                        <button type="button" data-edit-toggle="review-edit-{{ $review->id }}"
                                            class="block w-full text-left rounded px-3 py-2 text-xs text-zinc-200 hover:bg-zinc-800">Update</button>
                                        <form action="{{ route('opinions.destroy', $review->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="cursor-pointer block w-full text-left rounded px-3 py-2 text-xs text-red-300 hover:bg-zinc-800">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="flex text-[#FBBF24] text-[10px] mb-2 gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round((float) $review->rate))
                                        <i class="fa-solid fa-star"></i>
                                    @else
                                        <i class="fa-regular fa-star text-zinc-600"></i>
                                    @endif
                                @endfor
                            </div>

                            <p class="text-zinc-300 text-xs leading-relaxed">
                                {{ $review->content ?: 'No comment provided.' }}
                            </p>

                            @if((int) auth()->id() === (int) $review->author_id)
                                <form id="review-edit-{{ $review->id }}" action="{{ route('opinions.update', $review->id) }}"
                                    method="POST" class="hidden mt-3 border-t border-zinc-800 pt-3">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-2">
                                        <div class="sm:col-span-1">
                                            <label class="text-[11px] text-zinc-500">Rate</label>
                                            <select name="rate"
                                                class="mt-1 w-full bg-[#1c1c1c] border border-zinc-700 rounded-md p-2 text-xs text-zinc-100">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <option value="{{ $i }}" @selected((int) round((float) $review->rate) === $i)>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="sm:col-span-3">
                                            <label class="text-[11px] text-zinc-500">Content</label>
                                            <textarea name="content" rows="2"
                                                class="mt-1 w-full bg-[#1c1c1c] border border-zinc-700 rounded-md p-2 text-xs text-zinc-100 resize-none">{{ $review->content }}</textarea>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-end gap-2">
                                        <button type="button" data-edit-cancel="review-edit-{{ $review->id }}"
                                            class="px-3 py-1.5 text-xs rounded-md border border-zinc-700 text-zinc-300 hover:bg-zinc-800">Cancel</button>
                                        <button type="submit"
                                            class="px-3 py-1.5 text-xs rounded-md bg-[#d1fa48] text-black font-semibold hover:bg-[#b4d83d]">Save</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="bg-[#111111] border border-zinc-800/60 rounded-lg p-5 text-center">
                            <p class="text-zinc-400 text-sm">No reviews yet for this coach.</p>
                        </div>
                    @endforelse

                </div>



            </div>
        </div>
    </div>

    <script>

        const ratingInput = document.getElementById('opinion-rate');
        const ratingRoot = document.getElementById('opinion-star-rating');

        if (ratingInput && ratingRoot) {
            const stars = Array.from(ratingRoot.querySelectorAll('.rating-star'));
            const paintStars = (selectedRate) => {
                stars.forEach((button) => {
                    const rate = Number(button.dataset.rate || 0);
                    const icon = button.querySelector('i');
                    if (!icon) {
                        return;
                    }

                    if (rate <= selectedRate) {
                        icon.classList.add('text-[#FBBF24]');
                        icon.classList.remove('text-zinc-600');
                    } else {
                        icon.classList.remove('text-[#FBBF24]');
                        icon.classList.add('text-zinc-600');
                    }
                });
            };

            paintStars(Number(ratingInput.value));

            stars.forEach((button) => {
                button.addEventListener('click', () => {
                    const selectedRate = Number(button.dataset.rate || 5);
                    ratingInput.value = String(selectedRate);
                    paintStars(selectedRate);
                });
            });
        }

        document.querySelectorAll('[data-menu-toggle]').forEach((toggleButton) => {
            toggleButton.addEventListener('click', () => {
                const menuId = toggleButton.getAttribute('data-menu-toggle');
                if (!menuId) {
                    return;
                }

                const menu = document.getElementById(menuId);
                if (!menu) {
                    return;
                }

                document.querySelectorAll('[id^="review-menu-"]').forEach((node) => {
                    if (node !== menu) {
                        node.classList.add('hidden');
                    }
                });

                menu.classList.toggle('hidden');
            });
        });

        document.querySelectorAll('[data-edit-toggle]').forEach((editButton) => {
            editButton.addEventListener('click', () => {
                const formId = editButton.getAttribute('data-edit-toggle');
                if (!formId) {
                    return;
                }

                const form = document.getElementById(formId);
                if (form) {
                    form.classList.toggle('hidden');
                }
            });
        });

        document.querySelectorAll('[data-edit-cancel]').forEach((cancelButton) => {
            cancelButton.addEventListener('click', () => {
                const formId = cancelButton.getAttribute('data-edit-cancel');
                if (!formId) {
                    return;
                }

                const form = document.getElementById(formId);
                if (form) {
                    form.classList.add('hidden');
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (event.target.closest('[data-menu-toggle]') || event.target.closest('[id^="review-menu-"]')) {
                return;
            }

            document.querySelectorAll('[id^="review-menu-"]').forEach((node) => {
                node.classList.add('hidden');
            });
        });

    </script>

</body>

</html>