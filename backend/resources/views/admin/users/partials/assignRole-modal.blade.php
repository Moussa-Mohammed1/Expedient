<div id="assignRoleModal" class="hidden fixed inset-0 z-50 p-4 sm:p-6" aria-labelledby="modal-title" role="dialog"
    aria-modal="true" style="display: none; align-items: center; justify-content: center;">

    <div class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

    <div
        class="relative w-full max-w-md mx-auto bg-[#111111] border border-zinc-800/80 rounded-sm shadow-2xl overflow-hidden transform transition-all">

        <div class="px-6 py-5 border-b border-zinc-800/80 flex items-center justify-between">
            <h3 class="text-lg font-bold text-white flex items-center gap-2" id="modal-title">
                Assign Admin Role
            </h3>
            <button type="button" id="closeAssignRoleModal" class="text-zinc-500 hover:text-white transition-colors outline-none cursor-pointer">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div class="px-6 py-6">

            <p class="text-sm text-zinc-400 mb-5">You are about to elevate the privileges for the following user. Please
                review their details carefully.</p>

            <div class="bg-[#1c1c1c] border border-zinc-700/80 rounded-xl p-4 flex items-center gap-4 mb-6">
                <img id="modalUserAvatar" src="" alt="User Avatar"
                    class="w-12 h-12 rounded-full border border-zinc-600 shrink-0 object-cover">

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1">
                        <h4 id="modalUserName" class="text-white font-bold text-base truncate pr-2"></h4>
                        <span id="modalUserId" class="text-zinc-500 text-xs font-mono shrink-0"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-zinc-500">Current Role:</span>
                        <span id="modalUserRole"
                            class="bg-zinc-800 text-zinc-300 border border-zinc-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider">
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-[#FBBF24]/10 border border-[#FBBF24]/30 rounded-sm p-4 flex gap-3">
                <i class="fa-solid fa-triangle-exclamation text-[#FBBF24] mt-0.5"></i>
                <p class="text-xs text-[#FBBF24] leading-relaxed">
                    <span class="font-bold">Warning:</span> Granting Admin rights gives this user full access to the
                    Expedient dashboard, including user management, content moderation, and system settings.
                </p>
            </div>

        </div>

        <form id="assignRoleForm" method="POST" action="">
            @csrf
            <div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-end gap-3">
                <button type="button" id="cancelAssignRoleModal"
                    class="bg-transparent hover:bg-zinc-800 text-zinc-300 text-sm font-bold py-2.5 px-5 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" id="confirmAssignRoleBtn"
                    class="bg-yellow-500  text-black text-sm font-bold py-2.5 px-6 rounded-xl transition-colors flex items-center gap-2 shadow-lg shadow-[#ff5520]/20">
                    <i class="fa-solid fa-check"></i> Confirm Assignment
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    (function () {
        const modal = document.getElementById('assignRoleModal');
        const openButtons = document.querySelectorAll('#assign-admin');
        const closeBtn = document.getElementById('closeAssignRoleModal');
        const cancelBtn = document.getElementById('cancelAssignRoleModal');
        const form = document.getElementById('assignRoleForm');

        if (!modal || !form) {
            return;
        }

        let selectedUserId = null;

        const openModal = (userId, userName, userRole, userAvatar) => {
            selectedUserId = userId;
            document.getElementById('modalUserName').textContent = userName;
            document.getElementById('modalUserId').textContent = `ID: ${userId}`;
            document.getElementById('modalUserRole').textContent = userRole || 'User';
            document.getElementById('modalUserAvatar').src = userAvatar;
            modal.style.display = 'flex';
        };

        const closeModal = () => {
            modal.style.display = 'none';
            selectedUserId = null;
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
                const userRole = row.querySelector('span.uppercase')?.textContent ?? 'User';
                const userAvatar = row.querySelector('img')?.src ?? '';

                if (!userId) {
                    return;
                }

                openModal(userId, userName, userRole, userAvatar);
            });
        });

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);

        form.addEventListener('submit', (event) => {
            if (!selectedUserId) {
                event.preventDefault();
                return;
            }

            form.action = `/users/${selectedUserId}/assign-role`;
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                closeModal();
            }
        });
    })();
</script>