<div class="bg-[#17181b] border border-zinc-800/80 rounded-xl p-6 sm:p-8 xl:col-span-4">
    <div class="flex items-center justify-between mb-6 border-b border-zinc-800/50 pb-2">
        <h2 class="text-lg font-bold text-white">2. Media</h2>
        <span class="text-xs text-zinc-500">Logo + gallery</span>
    </div>

    <div class="space-y-6">
        <div class="bg-[#1c1c1c] border border-zinc-700 rounded-md p-4">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <p class="text-sm font-semibold text-white">Salle Logo</p>
                    <p class="text-xs text-zinc-500 mt-1">Square image recommended for the profile header.</p>
                </div>
                <label
                    class="inline-flex items-center gap-2 text-black bg-[#FBBF24] font-bold text-xs px-3 py-1.5 rounded-full cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    Upload
                    <input id="logoInput" type="file" name="logo" class="hidden" accept="image/*">
                </label>
            </div>

            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-lg border border-zinc-700 bg-[#222222] overflow-hidden shrink-0">
                    <img id="logoPreview" src="{{ $resolveImageUrl($salle->logo, $defaultLogoImage) }}"
                        alt="{{ $salle->name }} logo"
                        onerror="this.onerror=null;this.src='{{ $defaultLogoImage }}';"
                        class="w-full h-full object-cover">
                </div>
                <div class="text-xs text-zinc-500 leading-relaxed">
                    This logo will appear on the salle profile and in compact cards.
                </div>
            </div>
        </div>

        <div class="bg-[#1c1c1c] border border-zinc-700 rounded-md p-4">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <p class="text-sm font-semibold text-white">Background Image</p>
                    <p class="text-xs text-zinc-500 mt-1">Wide image used for the salle banner and cover sections.</p>
                </div>
                <label
                    class="inline-flex items-center gap-2 text-black bg-[#FBBF24] font-bold text-xs px-3 py-1.5 rounded-full cursor-pointer">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    Upload
                    <input id="backgroundInput" type="file" name="background" class="hidden" accept="image/*">
                </label>
            </div>

            <div class="flex items-center gap-4">
                <div class="w-28 h-20 rounded-lg border border-zinc-700 bg-[#222222] overflow-hidden shrink-0">
                    <img id="backgroundPreview" src="{{ $resolveImageUrl($salle->background, $defaultBackgroundImage) }}"
                        alt="{{ $salle->name }} background"
                        onerror="this.onerror=null;this.src='{{ $defaultBackgroundImage }}';"
                        class="w-full h-full object-cover">
                </div>
                <div class="text-xs text-zinc-500 leading-relaxed">
                    This background will be used as the main visual cover for the salle.
                </div>
            </div>
        </div>

        <div>
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wide">Photo Gallery</h3>
                <span class="text-xs text-zinc-500">Max 5 images</span>
            </div>

            <div id="galleryPreviewGrid" class="grid grid-cols-2 gap-4">
                @foreach ($salle->galleries as $gallery)
                    @php
                        $galleryImage = $resolveImageUrl($gallery->content, $defaultBackgroundImage);
                    @endphp
                    <div data-gallery-existing="true"
                        class="relative h-28 bg-[#1c1c1c] rounded-md border border-zinc-700 overflow-hidden">
                        <img src="{{ $galleryImage }}" alt="{{ $salle->name }} gallery"
                            onerror="this.onerror=null;this.src='{{ $defaultBackgroundImage }}';"
                            class="w-full h-full object-cover">
                        <button type="button"
                            class="absolute top-1 right-1 bg-black/80 text-white w-6 h-6 rounded-full text-xs flex items-center justify-center border border-zinc-700"
                            aria-label="Remove gallery image">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endforeach

                @if ($salle->galleries->count() < 5)
                    <label id="galleryUploadLabel"
                        class="h-28 bg-[#1c1c1c] rounded-md border-zinc-700 flex flex-col items-center justify-center text-zinc-500 cursor-pointer hover:border-[#FBBF24] hover:text-white transition-colors">
                        <i class="fa-solid fa-cloud-arrow-up text-xl mb-1"></i>
                        <span class="text-[10px] font-bold uppercase tracking-wide">Upload Images</span>
                        <span class="text-[10px] text-zinc-600 uppercase tracking-wide">Up to 5</span>
                        <input id="galleryInput" type="file" name="galleries[]" class="hidden"
                            multiple accept="image/*">
                    </label>
                @endif
            </div>

            <p id="galleryHint" class="mt-3 text-xs text-zinc-500">
                Choose up to 5 images. New selections preview instantly.
            </p>
        </div>
    </div>
</div>

<script>
    const logoInput = document.getElementById('logoInput');
    const logoPreview = document.getElementById('logoPreview');
    const backgroundInput = document.getElementById('backgroundInput');
    const backgroundPreview = document.getElementById('backgroundPreview');
    const galleryInput = document.getElementById('galleryInput');
    const galleryPreviewGrid = document.getElementById('galleryPreviewGrid');
    const galleryUploadLabel = document.getElementById('galleryUploadLabel');
    const galleryHint = document.getElementById('galleryHint');
    const maxGalleryImages = 5;

    let activeLogoUrl = null;
    let activeBackgroundUrl = null;
    let activeGalleryUrls = [];

    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            if (activeLogoUrl) {
                URL.revokeObjectURL(activeLogoUrl);
            }

            activeLogoUrl = URL.createObjectURL(file);
            logoPreview.src = activeLogoUrl;
        });
    }

    if (backgroundInput && backgroundPreview) {
        backgroundInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            if (activeBackgroundUrl) {
                URL.revokeObjectURL(activeBackgroundUrl);
            }

            activeBackgroundUrl = URL.createObjectURL(file);
            backgroundPreview.src = activeBackgroundUrl;
        });
    }

    const clearGalleryPreviewFiles = () => {
        activeGalleryUrls.forEach((previewUrl) => URL.revokeObjectURL(previewUrl));
        activeGalleryUrls = [];

        if (!galleryPreviewGrid) {
            return;
        }

        galleryPreviewGrid.querySelectorAll('[data-gallery-upload-preview="true"]').forEach((item) => item.remove());
    };

    if (galleryInput && galleryPreviewGrid && galleryUploadLabel && galleryHint) {
        const updateGalleryUploadState = () => {
            const existingCount = galleryPreviewGrid.querySelectorAll('[data-gallery-existing="true"]').length;
            const availableSlots = Math.max(0, maxGalleryImages - existingCount);
            const isLocked = availableSlots === 0;

            galleryUploadLabel.classList.toggle('pointer-events-none', isLocked);
            galleryUploadLabel.classList.toggle('opacity-40', isLocked);
            galleryInput.disabled = isLocked;

            return availableSlots;
        };

        galleryInput.addEventListener('change', function () {
            clearGalleryPreviewFiles();

            const availableSlots = updateGalleryUploadState();
            const selectedFiles = Array.from(this.files || []).slice(0, availableSlots);

            if (!availableSlots) {
                galleryHint.textContent = 'This gallery already has 5 images.';
                return;
            }

            if (selectedFiles.length < (this.files || []).length) {
                galleryHint.textContent = 'Only ' + availableSlots + ' new image' + (availableSlots === 1 ? '' : 's') + ' can be added. Maximum 5 images total.';
            } else {
                galleryHint.textContent = 'Choose up to 5 images. New selections preview instantly.';
            }

            selectedFiles.forEach((file) => {
                const previewUrl = URL.createObjectURL(file);
                activeGalleryUrls.push(previewUrl);

                const previewCard = document.createElement('div');
                previewCard.dataset.galleryUploadPreview = 'true';
                previewCard.className = 'relative h-28 bg-[#1c1c1c] rounded-md border border-zinc-700 overflow-hidden';
                previewCard.innerHTML = '<img src="' + previewUrl + '" alt="Gallery preview" class="w-full h-full object-cover">';

                galleryPreviewGrid.insertBefore(previewCard, galleryUploadLabel);
            });
        });

        updateGalleryUploadState();
    }
</script>