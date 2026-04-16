<div class="bg-[#17181b] border border-zinc-800/80 rounded-xl p-6 sm:p-8 xl:col-span-7">
    <h2 class="text-lg font-bold text-white mb-6 border-b border-zinc-800/50 pb-2">4. Operating Hours
    </h2>

    @php
        $days = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
        ];

        $existingHoraires = $salle->horaires->keyBy(fn($horaire) => strtolower($horaire->day));
        $oldSchedule = old('horaires', []);
    @endphp

    <div class="space-y-4">
        @foreach ($days as $dayKey => $dayLabel)
            @php
                $oldDay = $oldSchedule[$dayKey] ?? null;
                $existingDay = $existingHoraires->get($dayKey);

                $openValue = $oldDay['open'] ?? ($existingDay?->openHour ? \Illuminate\Support\Carbon::parse($existingDay->openHour)->format('H:i') : '');
                $closeValue = $oldDay['close'] ?? ($existingDay?->closeHour ? \Illuminate\Support\Carbon::parse($existingDay->closeHour)->format('H:i') : '');

                $isOpen = filled($openValue) && filled($closeValue);
            @endphp

            <div data-day-row
                class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-[#1c1c1c] border border-zinc-700 p-4 rounded-md {{ $isOpen ? '' : 'opacity-60' }}">
                <span
                    class="w-24 text-sm font-bold uppercase tracking-wide {{ $isOpen ? 'text-white' : 'text-zinc-500' }}">{{ $dayLabel }}</span>

                <div data-hours-fields class="flex items-center gap-3 {{ $isOpen ? '' : 'hidden' }}">
                    <input data-open-input type="time" name="horaires[{{ $dayKey }}][open]"
                        value="{{ $openValue }}" {{ $isOpen ? '' : 'disabled' }}
                        class="bg-[#222222] border border-zinc-700 rounded-md px-3 py-2 text-sm text-white focus:outline-none focus:border-[#FBBF24]">
                    <span class="text-zinc-500 text-xs">TO</span>
                    <input data-close-input type="time" name="horaires[{{ $dayKey }}][close]"
                        value="{{ $closeValue }}" {{ $isOpen ? '' : 'disabled' }}
                        class="bg-[#222222] border border-zinc-700 rounded-md px-3 py-2 text-sm text-white focus:outline-none focus:border-[#FBBF24]">
                </div>

                <div data-closed-state class="flex items-center gap-3 {{ $isOpen ? 'hidden' : '' }}">
                    <span class="text-xs font-bold text-[#ff5520] uppercase tracking-wider px-4">Closed</span>
                    <input data-open-hidden type="hidden" name="horaires[{{ $dayKey }}][open]" value=""
                        {{ $isOpen ? 'disabled' : '' }}>
                    <input data-close-hidden type="hidden" name="horaires[{{ $dayKey }}][close]" value=""
                        {{ $isOpen ? 'disabled' : '' }}>
                </div>

                <button type="button" data-toggle-hours
                    class="text-xs font-medium text-zinc-400 underline">{{ $isOpen ? 'Close Day' : 'Set Hours' }}</button>
            </div>
        @endforeach
    </div>
</div>

<script>
    const dayRows = document.querySelectorAll('[data-day-row]');

    dayRows.forEach((row) => {
        const toggleButton = row.querySelector('[data-toggle-hours]');
        const hoursFields = row.querySelector('[data-hours-fields]');
        const closedState = row.querySelector('[data-closed-state]');
        const openInput = row.querySelector('[data-open-input]');
        const closeInput = row.querySelector('[data-close-input]');
        const openHidden = row.querySelector('[data-open-hidden]');
        const closeHidden = row.querySelector('[data-close-hidden]');
        const dayLabel = row.querySelector('span.w-24');

        if (!toggleButton || !hoursFields || !closedState || !openInput || !closeInput || !openHidden || !closeHidden || !dayLabel) {
            return;
        }

        toggleButton.addEventListener('click', () => {
            const isClosed = !hoursFields.classList.contains('hidden');

            if (isClosed) {
                hoursFields.classList.add('hidden');
                closedState.classList.remove('hidden');

                openInput.disabled = true;
                closeInput.disabled = true;
                openInput.value = '';
                closeInput.value = '';

                openHidden.disabled = false;
                closeHidden.disabled = false;
                openHidden.value = '';
                closeHidden.value = '';

                row.classList.add('opacity-60');
                dayLabel.classList.remove('text-white');
                dayLabel.classList.add('text-zinc-500');
                toggleButton.textContent = 'Set Hours';
            } else {
                hoursFields.classList.remove('hidden');
                closedState.classList.add('hidden');

                openInput.disabled = false;
                closeInput.disabled = false;
                if (!openInput.value) {
                    openInput.value = '06:00';
                }
                if (!closeInput.value) {
                    closeInput.value = '22:00';
                }

                openHidden.disabled = true;
                closeHidden.disabled = true;

                row.classList.remove('opacity-60');
                dayLabel.classList.remove('text-zinc-500');
                dayLabel.classList.add('text-white');
                toggleButton.textContent = 'Close Day';
            }
        });
    });
</script>