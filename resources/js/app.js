import { createInertiaApp } from '@inertiajs/svelte';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { mount } from 'svelte';
import './echo';

const el = document.getElementById('app');
const inertiaScript = document.querySelector('script[data-page]');
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

if (el && (el.dataset.page || inertiaScript)) {
    createInertiaApp({
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) => resolvePageComponent(`./Pages/${name}.svelte`, import.meta.glob('./Pages/**/*.svelte')),
        setup({ el, App, props }) {
            mount(App, { target: el, props });
        },
        progress: {
            color: '#4B5563',
        },
    });
}
