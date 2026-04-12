@php
    // Fetch the latest verification request.
    $verification = $coach->latestVerification;

    $instanceId = (string) \Illuminate\Support\Str::ulid();
    $dropdownId = 'badge-details-' . $instanceId;
    $modalId = 'badge-modal-' . $instanceId;
@endphp

<div class="relative z-50 text-left font-sans coach-badge-container">
    <button title="Badge" type="button" onclick="document.getElementById('{{ $dropdownId }}').classList.toggle('hidden')"
        class=" text-white text-sm font-medium flex justify-center items-center rounded-sm p-2 mr-2 bg-[#2f2e2e] cursor-pointer transition focus:outline-none">
        <i class="fa-solid fa-id-card text-yellow-500 text-xl"></i>
    </button>

    <div id="{{ $dropdownId }}"
        class="hidden absolute -left-50 z-50 w-64 mt-2 p-4 bg-[#322e2e] border border-black rounded-lg shadow-lg">
        @if (!$verification)
            <div class="text-sm">
                <p class="mb-3 text-white">You are not verified yet.</p>
                <button onclick="openRequestModal('{{ $modalId }}')"
                    class=" cursor-pointer font-semibold px-4 py-2 text-black bg-yellow-500 rounded-md hover:bg-yellow-700 transition-colors">
                    Request Verification Badge
                </button>
            </div>

        @elseif ($verification->status === 'pending')
            <div class="text-sm">
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-700 text-white mb-2">
                    Pending
                </span>
                <p class="text-white">Your verification request is currently under review by <span class="text-yellow-500">Expedient</span> admins.</p>
                <p class="mt-2 text-xs text-gray-400">Requested at:
                    {{ \Carbon\Carbon::parse($verification->requested_at)->format('M d, Y') }}
                </p>
            </div>

        @elseif ($verification->status === 'rejected')
            <div class="text-sm">
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mb-2">
                    Rejected
                </span>
                <p class="text-gray-700 mt-1">
                    <strong>Cause:</strong> {{ $verification->rejection_cause ?? 'No reason provided.' }}
                </p>

                <div class="mt-4">
                    <button 
                        onclick="openRequestModal('{{ $modalId }}')"
                        class=" px-4 py-2 text-white bg-green-600 rounded-md hover:bg-green-700 transition-colors">
                        Request Verification Again
</button>
                </div>
            </div>

        @elseif ($verification->status === 'approved')
            <div class="text-sm text-center">
                <div class="flex justify-center mb-2">
                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mb-2">
                    Approved
                </span>
                <p class="text-gray-800 font-semibold text-base mt-1">You have a verification badge!</p>
                <p class="mt-2 text-xs text-gray-400">Verified on:
                    {{ \Carbon\Carbon::parse($verification->reviewed_at)->format('M d, Y') }}
                </p>
            </div>
        @endif
    </div>
</div>
<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="w-full max-w-md p-6 mx-4  bg-[#1c1c1c] border border-gray-800 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Request Verification</h3>
            <button onclick="closeRequestModal('{{ $modalId }}')" class="text-gray-400 hover:text-white cursor-pointer text-lg transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p class="text-sm text-gray-400 mb-6">
            Upload your professional certifications or identity documents to earn your coach verification badge.
        </p>
        <form action="{{ route('coach-verifications.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Proof Document (PDF, PNG, JPG)</label>
                <div class="relative group">
                    <input type="file" name="proof_document" id="proof_document" required
                        class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#111111] file:text-yellow-500 hover:file:bg-gray-800 cursor-pointer border border-gray-700 rounded-md bg-[#111111] focus:outline-none focus:ring-1 focus:ring-yellow-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Brief Description</label>
                <textarea name="description" rows="3"
                    placeholder="e.g., Certification of a Master of Taekwondo"
                    class="w-full px-4 py-2 text-white bg-[#111111] border border-gray-700 rounded-md focus:ring-1 focus:ring-yellow-500 focus:outline-none placeholder:text-gray-600"></textarea>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeRequestModal('{{ $modalId }}')"
                    class="flex-1 px-4 py-2 cursor-pointer font-semibold text-gray-300 bg-gray-800 rounded-md hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-2 cursor-pointer font-semibold text-black bg-yellow-500 rounded-md hover:bg-yellow-600 transition-colors">
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('click', function (event) {

        const containers = document.querySelectorAll('.coach-badge-container');

        containers.forEach(container => {
            const dropdown = container.querySelector('div[id^="badge-details-"]');


            if (!container.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    function openRequestModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeRequestModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    window.onclick = function (event) {
        const modal = document.getElementById('{{ $modalId }}');
        if (event.target == modal) {
            closeRequestModal('{{ $modalId }}');
        }
    }
</script>