<div class="bg-[#17181b] border border-zinc-800/80 rounded-xl p-6 sm:p-8 xl:col-span-5">
    @php
        $defaultEquipmentImage = asset('assets/images/equipment_default.jpeg');
        $equipmentCatalog = $equipments->keyBy('id');
        $conditionOptions = ['excellent' => 'Excellent', 'good' => 'Good', 'fair' => 'Fair', 'needs_repair' => 'Needs Repair'];

        $selectedEquipmentsInput = collect(old('equipment', []));

        if ($selectedEquipmentsInput->isEmpty()) {
            $selectedEquipmentsInput = $salle->equipments->values()->map(fn($equipment) => [
                'equipment_id' => (string) $equipment->id,
                'condition' => $equipment->pivot?->condition ?: 'good',
            ]);
        }

        $selectedEquipments = $selectedEquipmentsInput
            ->values()
            ->map(fn($item) => [
                'equipment_id' => (string) ($item['equipment_id'] ?? ''),
                'condition' => (string) ($item['condition'] ?? 'good'),
            ])
            ->filter(fn($item) => $item['equipment_id'] !== '')
            ->values();
    @endphp

    <div class="flex items-center justify-between mb-6 border-b border-zinc-800/50 pb-2">
        <h2 class="text-lg font-bold text-white">5. Equipment Inventory</h2>
        <button id="open-equipment-modal" type="button"
            class="text-black bg-[#FBBF24] font-bold text-xs px-3 py-1.5 rounded-full">
            + Add Item
        </button>
    </div>

    <div id="selected-equipment-list" class="space-y-4">
        @forelse ($selectedEquipments as $index => $selectedEquipment)
            @php
                $equipmentModel = $equipmentCatalog->get((int) $selectedEquipment['equipment_id']);
                $equipmentName = $equipmentModel?->name ?: 'Unknown equipment';
                $equipmentImage = $resolveImageUrl($equipmentModel?->image, $defaultEquipmentImage);
            @endphp
            <div data-selected-equipment-row data-equipment-id="{{ $selectedEquipment['equipment_id'] }}"
                class="bg-[#1c1c1c] border border-zinc-700 rounded-md p-4 flex flex-col sm:flex-row sm:items-center gap-4">
                <input type="hidden" name="equipment[{{ $index }}][equipment_id]" value="{{ $selectedEquipment['equipment_id'] }}">
                <div class="w-14 h-14 rounded-md border border-zinc-700 overflow-hidden bg-[#222222] shrink-0">
                    <img src="{{ $equipmentImage }}" alt="{{ $equipmentName }}"
                        onerror="this.onerror=null;this.src='{{ $defaultEquipmentImage }}';"
                        class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Equipment</label>
                    <p class="text-sm text-white font-medium">{{ $equipmentName }}</p>
                </div>
                <div class="sm:w-1/3">
                    <label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Condition</label>
                    <select name="equipment[{{ $index }}][condition]"
                        class="w-full bg-[#222222] border border-zinc-700 rounded-md px-3 py-1.5 text-white text-sm focus:outline-none focus:border-[#FBBF24] appearance-none">
                        @foreach ($conditionOptions as $conditionValue => $conditionLabel)
                            <option value="{{ $conditionValue }}" {{ $selectedEquipment['condition'] === $conditionValue ? 'selected' : '' }}>
                                {{ $conditionLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="pt-4 sm:pt-0">
                    <button type="button" data-remove-equipment
                        class="text-zinc-500 w-8 h-8 flex items-center justify-center rounded-full border border-zinc-700 bg-[#222222]">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        @empty
        @endforelse
    </div>

    <div id="empty-equipment-message" class="text-xs text-zinc-500 {{ $selectedEquipments->isNotEmpty() ? 'hidden' : '' }}">
        No equipment selected yet.
    </div>
</div>

<div id="equipment-modal"
    class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm px-4 py-6 sm:py-10 overflow-y-auto">
    <div class="max-w-4xl mx-auto bg-[#17181b] border border-zinc-800 rounded-xl p-5 sm:p-6">
        <div class="flex items-center justify-between mb-5 border-b border-zinc-800 pb-3">
            <h3 class="text-lg font-bold text-white">Select Equipment</h3>
            <button id="close-equipment-modal" type="button"
                class="w-8 h-8 rounded-full border border-zinc-700 text-zinc-400 hover:text-white flex items-center justify-center">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div id="equipment-catalog-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($equipments as $equipment)
                @php
                    $equipmentImage = $resolveImageUrl($equipment->image, $defaultEquipmentImage);
                @endphp
                <button type="button" data-pick-equipment data-equipment-id="{{ $equipment->id }}"
                    data-equipment-name="{{ $equipment->name }}"
                    data-equipment-image="{{ $equipmentImage }}"
                    class="text-left bg-[#1c1c1c] border border-zinc-700 rounded-md p-3 hover:border-[#FBBF24] transition-colors">
                    <div class="w-full h-28 rounded-md overflow-hidden bg-[#222222] border border-zinc-700 mb-3">
                        <img src="{{ $equipmentImage }}" alt="{{ $equipment->name }}"
                            onerror="this.onerror=null;this.src='{{ $defaultEquipmentImage }}';"
                            class="w-full h-full object-cover">
                    </div>
                    <p class="text-sm font-semibold text-white">{{ $equipment->name }}</p>
                    <p class="text-xs text-zinc-500 mt-1" data-pick-status>Click to select</p>
                </button>
            @endforeach
        </div>
    </div>
</div>

<script>
    const equipmentModal = document.getElementById('equipment-modal');
    const openEquipmentModalButton = document.getElementById('open-equipment-modal');
    const closeEquipmentModalButton = document.getElementById('close-equipment-modal');
    const selectedEquipmentList = document.getElementById('selected-equipment-list');
    const emptyEquipmentMessage = document.getElementById('empty-equipment-message');
    const equipmentCatalogButtons = document.querySelectorAll('[data-pick-equipment]');

    let nextEquipmentIndex = selectedEquipmentList ? selectedEquipmentList.querySelectorAll('[data-selected-equipment-row]').length : 0;

    const updateEquipmentEmptyState = () => {
        if (!selectedEquipmentList || !emptyEquipmentMessage) {
            return;
        }

        const hasRows = selectedEquipmentList.querySelectorAll('[data-selected-equipment-row]').length > 0;
        emptyEquipmentMessage.classList.toggle('hidden', hasRows);
    };

    const updateEquipmentCatalogState = () => {
        const selectedIds = new Set(Array.from(selectedEquipmentList.querySelectorAll('[data-selected-equipment-row]'))
            .map((row) => row.dataset.equipmentId));

        equipmentCatalogButtons.forEach((button) => {
            const statusLabel = button.querySelector('[data-pick-status]');
            const isSelected = selectedIds.has(button.dataset.equipmentId);

            button.disabled = isSelected;
            button.classList.toggle('opacity-50', isSelected);
            button.classList.toggle('cursor-not-allowed', isSelected);
            button.classList.toggle('hover:border-[#FBBF24]', !isSelected);

            if (statusLabel) {
                statusLabel.textContent = isSelected ? 'Already selected' : 'Click to select';
            }
        });
    };

    const removeEquipmentRow = (row) => {
        row.remove();
        updateEquipmentEmptyState();
        updateEquipmentCatalogState();
    };

    const attachRemoveBehavior = (button) => {
        button.addEventListener('click', () => {
            const row = button.closest('[data-selected-equipment-row]');

            if (!row) {
                return;
            }

            removeEquipmentRow(row);
        });
    };

    selectedEquipmentList.querySelectorAll('[data-remove-equipment]').forEach((button) => {
        attachRemoveBehavior(button);
    });

    const buildEquipmentRow = (equipmentId, equipmentName, equipmentImage) => {
        const row = document.createElement('div');
        row.dataset.selectedEquipmentRow = 'true';
        row.dataset.equipmentId = equipmentId;
        row.className = 'bg-[#1c1c1c] border border-zinc-700 rounded-md p-4 flex flex-col sm:flex-row sm:items-center gap-4';

        row.innerHTML =
            '<input type="hidden" name="equipment[' + nextEquipmentIndex + '][equipment_id]" value="' + equipmentId + '">' +
            '<div class="w-14 h-14 rounded-md border border-zinc-700 overflow-hidden bg-[#222222] shrink-0">' +
            '<img src="' + equipmentImage + '" alt="' + equipmentName + '" class="w-full h-full object-cover">' +
            '</div>' +
            '<div class="flex-1">' +
            '<label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Equipment</label>' +
            '<p class="text-sm text-white font-medium">' + equipmentName + '</p>' +
            '</div>' +
            '<div class="sm:w-1/3">' +
            '<label class="block text-[10px] font-bold text-zinc-500 uppercase mb-1">Condition</label>' +
            '<select name="equipment[' + nextEquipmentIndex + '][condition]" class="w-full bg-[#222222] border border-zinc-700 rounded-md px-3 py-1.5 text-white text-sm focus:outline-none focus:border-[#FBBF24] appearance-none">' +
            '<option value="excellent">Excellent</option>' +
            '<option value="good" selected>Good</option>' +
            '<option value="fair">Fair</option>' +
            '<option value="needs_repair">Needs Repair</option>' +
            '</select>' +
            '</div>' +
            '<div class="pt-4 sm:pt-0">' +
            '<button type="button" data-remove-equipment class="text-zinc-500 w-8 h-8 flex items-center justify-center rounded-full border border-zinc-700 bg-[#222222]">' +
            '<i class="fa-solid fa-trash"></i>' +
            '</button>' +
            '</div>';

        nextEquipmentIndex += 1;

        const removeButton = row.querySelector('[data-remove-equipment]');
        if (removeButton) {
            attachRemoveBehavior(removeButton);
        }

        return row;
    };

    const openEquipmentModal = () => {
        if (!equipmentModal) {
            return;
        }

        updateEquipmentCatalogState();
        equipmentModal.classList.remove('hidden');
    };

    const closeEquipmentModal = () => {
        if (!equipmentModal) {
            return;
        }

        equipmentModal.classList.add('hidden');
    };

    if (openEquipmentModalButton) {
        openEquipmentModalButton.addEventListener('click', openEquipmentModal);
    }

    if (closeEquipmentModalButton) {
        closeEquipmentModalButton.addEventListener('click', closeEquipmentModal);
    }

    if (equipmentModal) {
        equipmentModal.addEventListener('click', (event) => {
            if (event.target === equipmentModal) {
                closeEquipmentModal();
            }
        });
    }

    equipmentCatalogButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (button.disabled) {
                return;
            }

            const equipmentId = button.dataset.equipmentId || '';
            const equipmentName = button.dataset.equipmentName || 'Equipment';
            const equipmentImage = button.dataset.equipmentImage || '';

            if (!equipmentId || !selectedEquipmentList) {
                return;
            }

            const row = buildEquipmentRow(equipmentId, equipmentName, equipmentImage);
            selectedEquipmentList.appendChild(row);
            updateEquipmentEmptyState();
            updateEquipmentCatalogState();
            closeEquipmentModal();
        });
    });

    updateEquipmentEmptyState();
    updateEquipmentCatalogState();
</script>
