<!-- Loading States Component -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading states to all forms
        const forms = document.querySelectorAll('form[method="POST"], form[method="PUT"]');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner"></span> Menyimpan...';
                    submitBtn.disabled = true;

                    // Re-enable after 30 seconds as fallback
                    setTimeout(() => {
                        if (submitBtn.disabled) {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    }, 30000);
                }
            });
        });

        // Add loading states to delete buttons
        const deleteButtons = document.querySelectorAll('button[data-delete-url]');
        deleteButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('data-delete-url');
                const itemName = this.getAttribute('data-item-name') || 'data';
                showDeleteModal(url, itemName);
            });
        });

        // Add loading states to links
        const actionLinks = document.querySelectorAll('a[href*="/edit"], a[href*="/create"]');
        actionLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.classList.contains('loading')) {
                    this.classList.add('loading');
                    this.innerHTML = '<span class="spinner"></span> ' + this.textContent;
                }
            });
        });

        // Add search loading state
        const searchInputs = document.querySelectorAll('input[type="search"], input[name="search"]');
        searchInputs.forEach(input => {
            let searchTimeout;
            input.addEventListener('input', function() {
                clearTimeout(searchTimeout);

                // Show loading indicator
                const searchBtn = this.parentElement.querySelector('button[type="submit"]');
                if (searchBtn) {
                    searchBtn.innerHTML = '<span class="spinner"></span>';
                    searchBtn.disabled = true;
                }

                searchTimeout = setTimeout(() => {
                    // Auto-submit search after 500ms
                    if (this.value.length >= 2 || this.value.length === 0) {
                        this.form.submit();
                    }
                }, 500);
            });
        });

        // Add pagination loading state
        const paginationLinks = document.querySelectorAll('.pagination a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const loadingOverlay = document.createElement('div');
                loadingOverlay.className =
                    'fixed inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50';
                loadingOverlay.innerHTML =
                    '<div class="text-center"><span class="spinner"></span><p class="mt-2">Memuat halaman...</p></div>';
                document.body.appendChild(loadingOverlay);
            });
        });

        // Add filter loading state
        const filterSelects = document.querySelectorAll(
            'select[name="status"], select[name="kondisi"], select[name="kategori_id"], select[name="ruang_id"]'
            );
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                const form = this.closest('form');
                if (form) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner"></span>';
                        submitBtn.disabled = true;
                    }
                    form.submit();
                }
            });
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

    .loading {
        pointer-events: none;
        opacity: 0.7;
    }

    /* Loading overlay styles */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loading-content {
        text-align: center;
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
</style>
