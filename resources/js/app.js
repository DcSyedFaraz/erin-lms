import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('theme', () => ({
    theme: localStorage.getItem('color-theme') || 'system',

    init() {
        this.$watch('theme', (newTheme) => {
            if (newTheme === 'system') {
                localStorage.removeItem('color-theme');
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } else {
                localStorage.setItem('color-theme', newTheme);
                if (newTheme === 'dark') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            }
        });
    }
}));

Alpine.start();
