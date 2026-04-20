<div id="suspendUserModal" class="hidden fixed inset-0 z-50 p-4 sm:p-6" aria-labelledby="modal-title" role="dialog"
    aria-modal="true" style="display: none; align-items: center; justify-content: center;">

    <div class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

    <div class="relative w-full max-w-md bg-[#111111] border border-zinc-800/80 rounded-lg shadow-2xl overflow-hidden transform transition-all">

        <div class="px-6 py-5 border-b border-zinc-800/80 flex items-center justify-between bg-[#111111]">
            <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                <div class="w-8 h-8 rounded-full bg-[#FBBF24]/10 text-[#FBBF24] flex items-center justify-center">
                    <i class="fa-solid fa-user-clock text-sm"></i>
                </div>
                Suspend Account
            </h3>
            <button type="button" id="closeSuspendUserModal" class="text-zinc-500 hover:text-white transition-colors outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form id="suspendUserForm" action="" method="POST">
            @csrf

            <div class="px-6 py-6 space-y-6">

                <div class="bg-[#1c1c1c] border border-zinc-700/80 rounded-lg p-4 flex items-center gap-4">
                    <img id="suspendModalUserAvatar" src="" alt="User Avatar"
                        class="w-10 h-10 rounded-full border border-zinc-600 shrink-0 object-cover">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <h4 id="suspendModalUserName" class="text-white font-bold text-sm truncate pr-2"></h4>
                            <span id="suspendModalUserId" class="text-zinc-500 text-xs font-mono shrink-0"></span>
                        </div>
                        <p id="suspendModalUserEmail" class="text-xs text-zinc-500"></p>
                    </div>
                </div>

                <div>
                    <label for="preset_reason" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Reason for Suspension <span class="text-[#ff5520]">*</span></label>
                    <select id="preset_reason" class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-lg px-4 py-2.5 focus:outline-none focus:border-[#FBBF24] appearance-none mb-3">
                        <option value="" selected>Select a primary reason...</option>
                        <option value="Community Guidelines Violation">Community Guidelines Violation</option>
                        <option value="Spam / Scam Activity">Spam / Scam Activity</option>
                        <option value="Harassment / Abusive Behavior">Harassment / Abusive Behavior</option>
                        <option value="Fake Facility / Profile">Fake Facility / Profile</option>
                        <option value="Other">Other</option>
                    </select>
                    <textarea id="reason" name="reason" rows="2" placeholder="Provide specific details for the record..." required
                        class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-[#FBBF24] resize-none"></textarea>
                </div>

                <div>
                    <label for="expires_at" class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Suspension Lift Date <span class="text-[#ff5520]">*</span></label>
                    <input type="datetime-local" id="expires_at" name="expires_at" required
                        class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-2.5 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
                    <p class="text-xs text-zinc-500 mt-2"><i class="fa-solid fa-circle-info mr-1"></i> The account will automatically revert after this date.</p>
                </div>

            </div>

            <div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-end gap-3">
                <button type="button" id="cancelSuspendUserModal" class="bg-transparent hover:bg-zinc-800 text-zinc-300 text-sm font-bold py-2.5 px-5 rounded-lg transition-colors">
                    Cancel
                </button>
                <button type="submit" class="bg-[#FBBF24] hover:bg-[#d4a017] text-black text-sm font-bold py-2.5 px-6 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-pause"></i> Suspend User
                </button>
            </div>

        </form>

    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('suspendUserModal');
        const openButtons = document.querySelectorAll('#suspend-user');
        const closeBtn = document.getElementById('closeSuspendUserModal');
        const cancelBtn = document.getElementById('cancelSuspendUserModal');
        const form = document.getElementById('suspendUserForm');
        const presetReason = document.getElementById('preset_reason');
        const reasonField = document.getElementById('reason');
        const expiresAtField = document.getElementById('expires_at');

        if (!modal || !form || !reasonField || !expiresAtField) {
            return;
        }

        let selectedUserId = null;

        const toLocalDateTimeValue = (date) => {
            const offsetMs = date.getTimezoneOffset() * 60000;
            return new Date(date.getTime() - offsetMs).toISOString().slice(0, 16);
        };

        const openModal = (userId, userName, userEmail, userAvatar) => {
            selectedUserId = userId;
            document.getElementById('suspendModalUserName').textContent = userName;
            document.getElementById('suspendModalUserId').textContent = `ID: ${userId}`;
            document.getElementById('suspendModalUserEmail').textContent = userEmail;
            document.getElementById('suspendModalUserAvatar').src = userAvatar;

            const minDate = new Date();
            expiresAtField.min = toLocalDateTimeValue(minDate);
            modal.style.display = 'flex';
        };

        const closeModal = () => {
            modal.style.display = 'none';
            selectedUserId = null;
            form.reset();
        };

        openButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const row = button.closest('tr');
                if (!row) {
                    return;
                }

                const userId = row.querySelector('td:nth-child(3)')?.textContent.replace('#', '').trim();
                const userName = row.querySelector('p.font-bold')?.textContent ?? 'Unknown';
                const userEmail = row.querySelector('p.text-zinc-500')?.textContent ?? '';
                const userAvatar = row.querySelector('img')?.src ?? '';

                if (!userId) {
                    return;
                }

                openModal(userId, userName, userEmail, userAvatar);
            });
        });

        presetReason?.addEventListener('change', () => {
            if (!reasonField.value.trim() || reasonField.value === 'Other') {
                reasonField.value = presetReason.value;
            }
        });

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);

        form.addEventListener('submit', (event) => {
            if (!selectedUserId) {
                event.preventDefault();
                return;
            }

            form.action = `/admin/users/${selectedUserId}/suspend`;
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    })();
</script>