<div id="unassignRoleModal" class="hidden fixed inset-0 z-50 p-4 sm:p-6" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none; align-items: center; justify-content: center;">
        
        <div class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

        <div class="relative w-full max-w-md mx-auto bg-[#111111] border border-zinc-800/80 shadow-2xl overflow-hidden transform transition-all">
            
            <div class="px-6 py-5 border-b border-zinc-800/80 flex items-center justify-between">
                <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                    Revoke Admin Privileges
                </h3>
                <button type="button" id="closeUnassignRoleModal" class="text-zinc-500 hover:text-white transition-colors outline-none cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <form id="unassignRoleForm" method="POST" action="">
                @csrf
                <div class="px-6 py-6 space-y-6">
                    
                    <p class="text-sm text-zinc-400">You are about to remove administrative access for this user. They will immediately lose access to the system dashboard.</p>

                    <div class="bg-[#1c1c1c] border border-zinc-700/80 p-4 flex items-center gap-4">
                        <img id="unassignModalUserAvatar" src="" alt="User Avatar" class="w-12 h-12 rounded-full border border-zinc-600 shrink-0 object-cover">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <h4 id="unassignModalUserName" class="text-white font-bold text-base truncate pr-2"></h4>
                                <span id="unassignModalUserId" class="text-zinc-500 text-xs font-mono shrink-0"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-zinc-500">Current Role:</span>
                                <span class="bg-[#d1fa48]/10 text-[#d1fa48] border border-[#d1fa48]/30 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                                    Admin
                                </span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="new_role" class="block  text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Revert Account To <span class="text-[#ff5520]">*</span></label>
                        <select id="new_role" name="new_role" required class="w-full bg-[#1c1c1c] border border-zinc-700 text-white text-sm rounded-xl px-4 py-3 focus:outline-none focus:border-[#FBBF24] appearance-none cursor-pointer">
                            <option value="" disabled selected>Select new role...</option>
                            <option value="trainee">Trainee (standard user)</option>
                            <option value="coach">Coach</option>
                        </select>
                    </div>

                    <div class="bg-[#ff5520]/10 border border-[#ff5520]/30 p-4 flex gap-3">
                        <i class="fa-solid fa-triangle-exclamation text-[#ff5520] mt-0.5"></i>
                        <p class="text-xs text-[#ff5520]">
                            <span class="font-bold">Caution:</span> This action is immediate. Active sessions for this user will be terminated, and they will be redirected to the standard application upon their next request.
                        </p>
                    </div>

                </div>

                <div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-end gap-3">
                    <button type="button" id="cancelUnassignRoleModal" class="bg-transparent hover:bg-zinc-800 text-zinc-300 text-sm font-bold py-2.5 px-5 rounded-full">
                        Cancel
                    </button>
                    <button type="submit" class="bg-yellow-500 text-black text-sm font-bold py-2.5 px-6 rounded-full  flex items-center gap-2">
                        <i class="fa-solid fa-user-minus"></i> Revoke Access
                    </button>
                </div>
            </form>

        </div>
    </div>

<script>
    (function () {
        const modal = document.getElementById('unassignRoleModal');
        const openButtons = document.querySelectorAll('#unassign-admin');
        const closeBtn = document.getElementById('closeUnassignRoleModal');
        const cancelBtn = document.getElementById('cancelUnassignRoleModal');
        const form = document.getElementById('unassignRoleForm');
        const roleField = document.getElementById('new_role');

        if (!modal || !form || !roleField) {
            return;
        }

        let selectedUserId = null;

        const openModal = (userId, userName, userAvatar) => {
            selectedUserId = userId;
            document.getElementById('unassignModalUserName').textContent = userName;
            document.getElementById('unassignModalUserId').textContent = `ID: ${userId}`;
            document.getElementById('unassignModalUserAvatar').src = userAvatar;
            modal.style.display = 'flex';
        };

        const closeModal = () => {
            modal.style.display = 'none';
            selectedUserId = null;
            roleField.value = '';
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
                const userAvatar = row.querySelector('img')?.src ?? '';

                if (!userId) {
                    return;
                }

                openModal(userId, userName, userAvatar);
            });
        });

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);

        form.addEventListener('submit', (event) => {
            if (!selectedUserId) {
                event.preventDefault();
                return;
            }

            form.action = `/users/${selectedUserId}/unassign-role`;
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    })();
</script>