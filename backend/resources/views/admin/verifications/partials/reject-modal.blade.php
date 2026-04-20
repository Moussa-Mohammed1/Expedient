<div id="rejectVerificationModal" class="hidden fixed inset-0 z-50 p-4 sm:p-6" aria-labelledby="reject-modal-title"
	role="dialog" aria-modal="true" style="display: none; align-items: center; justify-content: center;">

	<div class="fixed inset-0 bg-black/20 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

	<div
		class="relative w-full max-w-md mx-auto bg-[#111111] border border-zinc-800/80 rounded-sm shadow-2xl overflow-hidden transform transition-all">

		<div class="px-6 py-5 border-b border-zinc-800/80 flex items-center justify-between">
			<h3 class="text-lg font-bold text-white flex items-center gap-2" id="reject-modal-title">
				Reject Verification Request
			</h3>
			<button type="button" id="closeRejectVerificationModal"
				class="text-zinc-500 hover:text-white transition-colors outline-none cursor-pointer">
				<i class="fa-solid fa-xmark text-lg"></i>
			</button>
		</div>

		<form id="rejectVerificationForm" method="POST" action="">
			@csrf
			@method('PATCH')
			<input type="hidden" name="status" value="rejected">

			<div class="px-6 py-6 space-y-6">
				<p class="text-sm text-zinc-400">You are about to reject this coach verification request. Please provide
					a clear reason that will help the coach resubmit correctly.</p>

				<div class="bg-[#1c1c1c] border border-zinc-700/80 rounded-xl p-4 flex items-center gap-4">
					<img id="rejectModalUserAvatar" src="" alt="Coach Avatar"
						class="w-12 h-12 rounded-full border border-zinc-600 shrink-0 object-cover">

					<div class="flex-1 min-w-0">
						<div class="flex items-center justify-between mb-1">
							<h4 id="rejectModalCoachName" class="text-white font-bold text-base truncate pr-2"></h4>
							<span id="rejectModalCoachId" class="text-zinc-500 text-xs font-mono shrink-0"></span>
						</div>
						<div class="flex items-center gap-2">
							<span class="text-xs text-zinc-500">Request:</span>
							<span id="rejectModalRequestId"
								class="bg-zinc-800 text-zinc-300 border border-zinc-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wider"></span>
						</div>
					</div>
				</div>

				<div>
					<label for="rejection_cause"
						class="block text-[10px] font-bold text-zinc-400 uppercase tracking-wide mb-2">Rejection Cause
						<span class="text-[#ff5520]">*</span></label>
					<textarea id="rejection_cause" name="rejection_cause" rows="3" required
						placeholder="e.g., Document is unreadable, expired, or missing identity details..."
						class="w-full bg-[#1c1c1c] border border-zinc-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:border-[#ff5520] resize-none"></textarea>
				</div>

				<div class="bg-[#ff5520]/10 border border-[#ff5520]/30 rounded-sm p-4 flex gap-3">
					<i class="fa-solid fa-triangle-exclamation text-[#ff5520] mt-0.5"></i>
					<p class="text-xs text-[#ff5520] leading-relaxed">
						<span class="font-bold">Notice:</span> This reason will be visible to the coach and used as
						guidance for their next submission.
					</p>
				</div>
			</div>

			<div class="px-6 py-5 bg-[#1c1c1c] border-t border-zinc-800 flex items-center justify-end gap-3">
				<button type="button" id="cancelRejectVerificationModal"
					class="bg-transparent hover:bg-zinc-800 text-zinc-300 text-sm font-bold py-2.5 px-5 rounded-xl transition-colors">
					Cancel
				</button>
				<button type="submit"
					class="bg-yellow-500 text-black text-sm font-bold py-2.5 px-6 rounded-xl transition-colors flex items-center gap-2">
					<i class="fa-solid fa-ban"></i> Confirm Rejection
				</button>
			</div>
		</form>

	</div>
</div>

<script>
	(function () {
		const modal = document.getElementById('rejectVerificationModal');
		const openButtons = document.querySelectorAll('.open-reject-verification');
		const closeBtn = document.getElementById('closeRejectVerificationModal');
		const cancelBtn = document.getElementById('cancelRejectVerificationModal');
		const form = document.getElementById('rejectVerificationForm');
		const causeField = document.getElementById('rejection_cause');

		if (!modal || !form || !causeField) {
			return;
		}

		const openModal = (button) => {
			document.getElementById('rejectModalCoachName').textContent = button.dataset.coachName || 'Unknown coach';
			document.getElementById('rejectModalCoachId').textContent = `Coach ID: ${button.dataset.coachId || '--'}`;
			document.getElementById('rejectModalRequestId').textContent = `#VER-${button.dataset.requestId || '--'}`;
			document.getElementById('rejectModalUserAvatar').src = button.dataset.avatar || '';
			form.action = button.dataset.action || '';
			causeField.value = '';
			modal.style.display = 'flex';
			causeField.focus();
		};

		const closeModal = () => {
			modal.style.display = 'none';
			form.action = '';
			causeField.value = '';
		};

		openButtons.forEach((button) => {
			button.addEventListener('click', () => openModal(button));
		});

		closeBtn?.addEventListener('click', closeModal);
		cancelBtn?.addEventListener('click', closeModal);

		modal.addEventListener('click', (event) => {
			if (event.target === modal) {
				closeModal();
			}
		});
	})();
</script>
