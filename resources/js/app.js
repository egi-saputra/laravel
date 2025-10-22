import './bootstrap';
import './sidebar';
import '../css/app.css';
import Alpine from 'alpinejs';

// import { createApp, h } from 'vue';
// import { createInertiaApp } from '@inertiajs/vue3';
// import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import { InertiaProgress } from '@inertiajs/progress';
// import { ZiggyVue } from 'ziggy-js';
// import { Ziggy } from './ziggy';

// const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Cek dulu apakah ada elemen root Inertia
// const el = document.getElementById('app'); // misal <div id="app"></div> untuk Inertia
// if (el) {
//     createInertiaApp({
//         title: (title) => `${title} - ${appName}`,
//         resolve: (name) =>
//             resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
//         setup({ App, props, plugin }) {
//             createApp({ render: () => h(App, props) })
//                 .use(plugin)
//                 .use(ZiggyVue, Ziggy)
//                 .mount(el);
//         },
//     });

//     InertiaProgress.init({
//         color: '#4B5563',
//         showSpinner: false,
//     });
// }

// Alpine.js tetap jalan
window.Alpine = Alpine;
Alpine.start();
