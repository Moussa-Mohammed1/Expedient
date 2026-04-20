<div id="review-report-modal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-sm px-4 py-6 overflow-y-auto">
	<div class="max-w-lg mx-auto bg-[#1b1c1f] border border-zinc-800 rounded-xl p-5 sm:p-6 mt-10">
		<div class="flex items-center justify-between border-b border-zinc-800 pb-3 mb-5">
			<div>
				<h3 class="text-white text-lg font-bold">Report Review</h3>
				<p class="text-zinc-500 text-xs mt-1">Flag a review for admin moderation.</p>
			</div>
			<button type="button" id="close-review-report-modal"
				class="w-8 h-8 rounded-full border border-zinc-700 text-zinc-400 hover:text-white flex items-center justify-center">
				<i class="fa-solid fa-xmark"></i>
			</button>
		</div>

		<form id="review-report-form" action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
			@csrf
			<input type="hidden" name="opinion_id" id="report-opinion-id" value="">
			<div>
				<label for="report-reason" class="block text-xs font-bold text-zinc-400 uppercase  mb-2">Reason</label>
				<select id="report-reason" name="reason"
					class="w-full bg-[#111214] border border-zinc-700 rounded-md px-3 py-2 text-white text-sm focus:outline-none focus:border-[#FBBF24]">
					<option value="Abusive Language">Abusive Language</option>
					<option value="Spam / Promotional">Spam / Promotional</option>
					<option value="False Information">False Information</option>
					<option value="other">Other</option>
				</select>
			</div>

			<div>
				<label for="report-note" class="block text-xs font-bold text-zinc-400 uppercase  mb-2">Additional Notes</label>
				<textarea id="report-note" name="note" rows="4"
					class="w-full bg-[#111214] border border-zinc-700 rounded-md px-3 py-2 text-white text-sm focus:outline-none focus:border-[#FBBF24] resize-none"
					placeholder="Describe why this review should be reviewed by admins."></textarea>
			</div>

			<div>
				<label for="report-proof" class="block text-xs font-bold text-zinc-400 uppercase  mb-2">Proof Attachment</label>
				<label
					class="flex flex-col items-center justify-center gap-2  border-zinc-700 bg-[#111214] px-4 py-5 text-center cursor-pointer hover:border-[#FBBF24] transition-colors">
					<i class="fa-solid fa-paperclip text-[#FBBF24]"></i>
					<span class="text-sm text-zinc-300 font-medium">Upload proof image or file</span>
					<span class="text-[11px] text-zinc-500">PNG, JPG, PDF up to 5MB</span>
					<input id="report-proof" name="proof" type="file" class="hidden" accept="image/*,.pdf">
				</label>
				<p id="report-proof-name" class="mt-2 text-xs text-zinc-500">No file selected.</p>
			</div>

			<div class="flex items-center justify-end gap-3 pt-2">
				<button type="button" id="cancel-review-report"
					class="border border-zinc-700 text-zinc-300 text-sm font-bold py-2.5 px-4 rounded-md">
					Cancel
				</button>
				<button type="submit"
					class="bg-[#ff5520] hover:bg-[#ff6f42] text-white text-sm font-bold py-2.5 px-5 rounded-md transition-colors">
					Submit Report
				</button>
			</div>
		</form>
	</div>
</div>

<script>
	
		const reportModal = document.getElementById('review-report-modal');
		const closeReportModalButton = document.getElementById('close-review-report-modal');
		const cancelReportButton = document.getElementById('cancel-review-report');
		const reportOpinionId = document.getElementById('report-opinion-id');
		const reportProofInput = document.getElementById('report-proof');
		const reportProofName = document.getElementById('report-proof-name');

		const openReportModal = (button) => {
			if (!reportModal || !button) {
				return;
			}

			if (reportOpinionId) {
				reportOpinionId.value = button.dataset.opinionId || '';
			}

			reportModal.classList.remove('hidden');
		};

		const closeReportModal = () => {
			if (!reportModal) {
				return;
			}

			reportModal.classList.add('hidden');
		};

		document.querySelectorAll('.open-report-modal').forEach((button) => {
			button.addEventListener('click', () => openReportModal(button));
		});

		if (closeReportModalButton) {
			closeReportModalButton.addEventListener('click', closeReportModal);
		}

		if (cancelReportButton) {
			cancelReportButton.addEventListener('click', closeReportModal);
		}

		if (reportModal) {
			reportModal.addEventListener('click', (event) => {
				if (event.target === reportModal) {
					closeReportModal();
				}
			});
		}

		if (reportProofInput && reportProofName) {
			reportProofInput.addEventListener('change', () => {
				const selectedFile = reportProofInput.files && reportProofInput.files[0];
				reportProofName.textContent = selectedFile ? selectedFile.name : 'No file selected.';
			});
		}
	
</script>
