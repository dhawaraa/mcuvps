(function() {
    // VPSMCU Accessibility: Font Size Controller with LocalStorage Persistence
    const STORAGE_KEY = 'vpsmcu_font_size';
    const VALID_SIZES = ['small', 'normal', 'large'];

    function applyFontSize(size, save = true) {
        if (!VALID_SIZES.includes(size)) {
            size = 'normal';
        }

        const root = document.documentElement;
        root.classList.remove('font-size-small', 'font-size-normal', 'font-size-large');
        root.classList.add(`font-size-${size}`);

        // Base font scale: 14.5px (small), 16px (normal - default), 18px (large)
        if (size === 'small') {
            root.style.fontSize = '14.5px';
        } else if (size === 'large') {
            root.style.fontSize = '18px';
        } else {
            root.style.fontSize = '16px';
        }

        // Update active states on buttons
        document.querySelectorAll('[data-font-size-btn]').forEach(btn => {
            const btnSize = btn.getAttribute('data-font-size-btn');
            if (btnSize === size) {
                btn.classList.add('bg-[#5A6B47]', 'text-white', 'shadow-xs');
                btn.classList.remove('text-[#5A544A]', 'hover:text-[#2C3E2D]', 'text-[#6B6357]');
                btn.setAttribute('aria-pressed', 'true');
            } else {
                btn.classList.remove('bg-[#5A6B47]', 'text-white', 'shadow-xs');
                btn.classList.add('text-[#5A544A]', 'hover:text-[#2C3E2D]');
                btn.setAttribute('aria-pressed', 'false');
            }
        });

        if (save) {
            try {
                localStorage.setItem(STORAGE_KEY, size);
            } catch (e) {
                // Ignore storage errors in private mode
            }
        }
    }

    // Expose globally
    window.setFontSize = function(size) {
        applyFontSize(size, true);
    };

    // Auto-initialize immediately
    let savedSize = 'normal';
    try {
        savedSize = localStorage.getItem(STORAGE_KEY) || 'normal';
    } catch (e) {
        savedSize = 'normal';
    }

    applyFontSize(savedSize, false);

    document.addEventListener('DOMContentLoaded', function() {
        applyFontSize(savedSize, false);
    });
})();
