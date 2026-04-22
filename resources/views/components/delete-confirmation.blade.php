<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4" id="deleteModalTitle">Konfirmasi Hapus</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500" id="deleteModalMessage">
                    Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="flex justify-center space-x-4 mt-4">
                <button type="button" id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');
        const cancelBtn = document.getElementById('cancelDelete');
        const confirmBtn = document.getElementById('confirmDelete');
        const title = document.getElementById('deleteModalTitle');
        const message = document.getElementById('deleteModalMessage');

        // Show modal function
        window.showDeleteModal = function(url, itemName = 'data') {
            deleteForm.action = url;
            title.textContent = 'Konfirmasi Hapus';
            message.textContent =
                `Apakah Anda yakin ingin menghapus ${itemName}? Tindakan ini tidak dapat dibatalkan.`;
            deleteModal.classList.remove('hidden');

            // Add loading state to confirm button
            confirmBtn.innerHTML = '<span class="spinner">Loading...</span>';
            confirmBtn.disabled = true;
        };

        // Hide modal function
        function hideModal() {
            deleteModal.classList.add('hidden');
            confirmBtn.innerHTML = 'Hapus';
            confirmBtn.disabled = false;
        }

        // Event listeners
        cancelBtn.addEventListener('click', hideModal);

        deleteModal.addEventListener('click', function(e) {
            if (e.target === deleteModal) {
                hideModal();
            }
        });

        // ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !deleteModal.classList.contains('hidden')) {
                hideModal();
            }
        });

        // Form submission with loading state
        deleteForm.addEventListener('submit', function(e) {
            confirmBtn.innerHTML = '<span class="spinner">Menghapus...</span>';
            confirmBtn.disabled = true;
        });
    });
</script>

<style>
    .spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
