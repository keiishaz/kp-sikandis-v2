/**
 * SIKANDIS Dashboard - Operator JavaScript
 * Dinas Kominfo Kota Bengkulu
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function () {

    // Initialize dashboard
    initDashboard();

    // Initialize mobile menu
    initMobileMenu();

    // ... existing code ...

    // Initialize active menu highlighting
    initActiveMenu();

    initModals();

    // New Initializers
    initTableSearch();
    initColumnResize();
    initAccordionMenu();

});

/**
 * Sidebar Accordion Menu
 */
function initAccordionMenu() {
    const toggles = document.querySelectorAll('.nav-group-toggle');
    toggles.forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            const group = this.closest('.nav-group');
            // Toggle expanded class
            group.classList.toggle('expanded');
        });
    });
}

/**
 * Real-time Table Search & Filtering (Debounced)
 */
function initTableSearch() {
    const searchInputs = document.querySelectorAll('.table-search-input');
    const filterSelects = document.querySelectorAll('.table-filter-select');

    let debounceTimer;

    // Search Input Handler
    // Search Input Handler with Debounce
    searchInputs.forEach(input => {
        input.addEventListener('input', function (e) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                submitTableFilter(input.form);
            }, 600); // 600ms debounce for better UX
        });

        // Clear search behavior (x button in input type="search")
        input.addEventListener('search', function () {
            if (this.value === '') {
                submitTableFilter(input.form);
            }
        });

        // Ensure input type is search for 'x' clear button
        input.setAttribute('type', 'search');
    });

    // Filter Select Handler (Instant)
    filterSelects.forEach(select => {
        select.addEventListener('change', function () {
            submitTableFilter(select.form);
        });
    });
}

function submitTableFilter(form) {
    if (!form) return;

    // Add loading state opacity to table
    const tableContainer = document.querySelector('.table-container');
    if (tableContainer) {
        tableContainer.style.opacity = '0.6';
        tableContainer.style.pointerEvents = 'none';

        // Show loading indicator if desired
        // const loader = document.createElement('div'); ...
    }

    form.submit();
}

/**
 * Table Column Resizing
 */
function initColumnResize() {
    const tables = document.querySelectorAll('.data-table');

    tables.forEach(table => {
        const headers = table.querySelectorAll('th');

        headers.forEach(th => {
            // Skip action column
            if (th.classList.contains('col-actions')) return;

            // Check if handle already exists to avoid duplicates
            if (th.querySelector('.resize-handle')) return;

            // Create handle
            const handle = document.createElement('div');
            handle.classList.add('resize-handle');
            th.appendChild(handle);

            // Resizing logic
            let startX, startWidth;

            handle.addEventListener('mousedown', function (e) {
                e.preventDefault();
                startX = e.pageX;
                startWidth = th.offsetWidth;

                handle.classList.add('active');
                document.body.style.cursor = 'col-resize'; // Global cursor

                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });

            function onMouseMove(e) {
                const diff = e.pageX - startX;
                const newWidth = startWidth + diff;
                if (newWidth > 60) { // Min width 60px
                    th.style.width = newWidth + 'px';
                }
            }

            function onMouseUp() {
                handle.classList.remove('active');
                document.body.style.cursor = ''; // Reset cursor
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
            }
        });
    });
}

/**
 * Initialize Dashboard
 */
function initDashboard() {
    // ... existing code ...
    console.log('SIKANDIS Dashboard initialized');

    // Add smooth scroll behavior
    document.documentElement.style.scrollBehavior = 'smooth';
}

function initModals() {
    const openers = document.querySelectorAll('[data-modal-open]');
    const closers = document.querySelectorAll('[data-modal-close]');

    openers.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-open');
            const overlay = document.getElementById(id);
            if (overlay) overlay.classList.add('active');
        });
    });

    closers.forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.getAttribute('data-modal-close');
            const overlay = document.getElementById(id);
            if (overlay) overlay.classList.remove('active');
        });
    });

    document.addEventListener('click', (e) => {
        const target = e.target;
        if (target && target.classList && target.classList.contains('modal-overlay')) {
            target.classList.remove('active');
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(overlay => {
                overlay.classList.remove('active');
            });
        }
    });
}

/**
 * Initialize Mobile Menu Toggle
 */
function initMobileMenu() {
    const mobileToggle = document.getElementById('mobile-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', function () {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function (event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggle = mobileToggle.contains(event.target);

            if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
    }
}

/**
 * Initialize Active Menu Highlighting
 */
function initActiveMenu() {
    const navLinks = document.querySelectorAll('.nav-link');
    const currentPath = window.location.pathname;

    navLinks.forEach(link => {
        // Remove active class from all links
        link.classList.remove('active');

        // Add active class to current page link
        if (link.getAttribute('href') === currentPath ||
            (currentPath === '/' && link.getAttribute('href') === '#dashboard')) {
            link.classList.add('active');
        }
    });
}

/**
 * Format number with thousand separator
 */
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/**
 * Update summary card value with animation
 */
function updateSummaryCard(cardId, newValue) {
    const card = document.getElementById(cardId);
    if (card) {
        const valueElement = card.querySelector('.summary-value');
        if (valueElement) {
            // Animate number change
            const currentValue = parseInt(valueElement.textContent);
            const increment = newValue > currentValue ? 1 : -1;
            const duration = 500; // ms
            const steps = Math.abs(newValue - currentValue);
            const stepDuration = duration / steps;

            let current = currentValue;
            const timer = setInterval(() => {
                current += increment;
                valueElement.textContent = current;

                if (current === newValue) {
                    clearInterval(timer);
                }
            }, stepDuration);
        }
    }
}

/**
 * Show notification (can be used for future features)
 */
function showNotification(message, type = 'info') {
    // This is a placeholder for future notification system
    console.log(`[${type.toUpperCase()}] ${message}`);
}

/**
 * Handle table row click (for future detail view)
 */
function handleTableRowClick(vehicleId) {
    console.log('Vehicle clicked:', vehicleId);
    // Future: Navigate to detail page or show modal
}

/**
 * Global Confirmation Modal Logic
 */
let confirmCallback = null;

function showConfirm(options = {}) {
    const modal = document.getElementById('sikandis-confirm-modal');
    const titleEl = document.getElementById('confirm-modal-title');
    const messageEl = document.getElementById('confirm-modal-message');
    const btnConfirm = document.getElementById('confirm-btn-confirm');
    const btnCancel = document.getElementById('confirm-btn-cancel');
    const iconContainer = modal.querySelector('.confirm-icon');

    // Reset styles
    iconContainer.className = 'confirm-icon ' + (options.type || 'danger');

    // Set content
    titleEl.textContent = options.title || 'Konfirmasi';
    
    let msg = options.message || 'Apakah Anda yakin ingin melanjutkan?';
    // Handle the \b... \b notation as bold (common in some Indonesian legacy systems/preferences)
    // or simply as a custom bold delimiter. We use <strong> for semantic bolding.
    msg = msg.replace(/\u0008/g, '\b'); // Ensure we catch the literal backspace char if it was interpreted
    msg = msg.replace(/\\b/g, '<strong>').replace(/<strong>(.*?)<strong>/g, '<strong>$1</strong>'); // This handles literal \b
    // Since \b in JS backticks is U+0008, let's catch that too
    msg = msg.replace(/\x08(.*?)\x08/g, '<strong>$1</strong>');
    
    messageEl.innerHTML = msg;
    btnConfirm.textContent = options.confirmText || 'Ya, Lanjutkan';
    btnCancel.textContent = options.cancelText || 'Batal';

    // Show modal
    modal.classList.add('active');

    // Handle actions
    return new Promise((resolve) => {
        const handleConfirm = () => {
            modal.classList.remove('active');
            cleanup();
            resolve(true);
        };

        const handleCancel = () => {
            modal.classList.remove('active');
            cleanup();
            resolve(false);
        };

        const cleanup = () => {
            btnConfirm.removeEventListener('click', handleConfirm);
            btnCancel.removeEventListener('click', handleCancel);
        };

        btnConfirm.addEventListener('click', handleConfirm);
        btnCancel.addEventListener('click', handleCancel);
    });
}

/**
 * Intercept forms/links that use onclick="return confirm(...)"
 */
function initBetterConfirms() {
    // Intercept forms using specific action buttons or data-confirm attribute
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        const submitter = e.submitter;

        // 1. Check for data-confirm attribute on the form itself (Highest priority)
        const formConfirmMsg = form.getAttribute('data-confirm');
        
        // 2. Identify Action Type
        const isLogout = submitter && (
            submitter.classList.contains('logout-btn') || 
            submitter.closest('.logout-btn') || 
            submitter.innerText.toLowerCase().includes('keluar') || 
            submitter.title?.toLowerCase().includes('keluar') ||
            (submitter.closest('form') && submitter.closest('form').action.includes('logout'))
        );
        
        const isDelete = !isLogout && submitter && (
            submitter.classList.contains('btn-delete') || 
            submitter.closest('.btn-delete') || 
            submitter.title?.toLowerCase().includes('hapus') || 
            (submitter.style.color && submitter.style.color.includes('danger'))
        );
        
        const isWarning = submitter && (
            submitter.classList.contains('btn-warning') || 
            submitter.closest('.btn-warning')
        );

        if (formConfirmMsg || isLogout || isDelete || isWarning) {
            if (form.dataset.confirmed) return; // Allow submit if already confirmed

            e.preventDefault();

            let options = {
                type: isLogout ? 'warning' : (isDelete ? 'danger' : 'warning'),
                title: isLogout ? 'Keluar dari Sistem?' : (isDelete ? 'Konfirmasi Tindakan' : 'Konfirmasi'),
                message: formConfirmMsg || 'Apakah Anda yakin ingin melanjutkan?',
                confirmText: isLogout ? 'Ya, Keluar' : (isDelete ? 'Ya, Lanjutkan' : 'Ya, Setuju')
            };

            // Context-specific defaults if no message provided
            if (!formConfirmMsg) {
                if (isLogout) {
                    options.title = 'Keluar dari Sistem?';
                    options.message = 'Apakah Anda yakin ingin mengakhiri sesi ini?';
                    options.confirmText = 'Ya, Keluar';
                    options.type = 'warning';
                } else if (isDelete) {
                    options.title = 'Hapus Data?';
                    options.message = 'Data ini akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.';
                    options.confirmText = 'Ya, Hapus';
                } else if (submitter && submitter.innerText.toLowerCase().includes('regenerate')) {
                    options.title = 'Buat Ulang QR Code?';
                    options.message = 'QR Code lama tidak akan berfungsi lagi setelah Anda membuat yang baru.';
                    options.confirmText = 'Ya, Buat Ulang';
                    options.type = 'warning';
                }
            }

            const confirmed = await showConfirm(options);

            if (confirmed) {
                form.dataset.confirmed = "true";
                form.submit();
            }
        }
    });
}

// Initialize better confirms
initBetterConfirms();

// Export functions for global use if needed
window.SIKANDIS = {
    formatNumber,
    updateSummaryCard,
    showNotification,
    handleTableRowClick,
    confirm: showConfirm
};

