import './bootstrap';

/**
 * Global Real-time Search Handler
 * Finds all search inputs in forms and applies debounced auto-submit logic.
 */
document.addEventListener('DOMContentLoaded', function() {
    const searchInputs = document.querySelectorAll('input[type="search"][name="q"]');
    
    searchInputs.forEach(input => {
        const form = input.closest('form');
        if (!form) return;

        let debounceTimer;
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                // If input is empty, ensure we reset to full list
                if (this.value === '') {
                    // Logic to ensure status and other filters are preserved
                    form.submit();
                } else if (this.value.length >= 1) {
                    form.submit();
                }
            }, 500); // 500ms debounce
        });

        // Prevent enter key from double submitting if already debouncing
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(debounceTimer);
                form.submit();
            }
        });
    });
});
