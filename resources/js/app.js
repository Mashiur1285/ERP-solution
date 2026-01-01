import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {
    faHouse,
    faUsers,
    faCreditCard,
    faShoppingBag,
    faMoneyBillWave,
    faBox,
    faPlus,
    faList,
    faMoneyCheck,
    faFolder,
    faTag,
    faShoppingCart,
    faFileAlt,
    faPlusCircle,
    faClipboardList,
    faChevronDown,
    faLayerGroup
} from '@fortawesome/free-solid-svg-icons';

// Add icons to library
library.add(
    faHouse,
    faUsers,
    faCreditCard,
    faShoppingBag,
    faMoneyBillWave,
    faBox,
    faPlus,
    faList,
    faMoneyCheck,
    faFolder,
    faTag,
    faShoppingCart,
    faFileAlt,
    faPlusCircle,
    faClipboardList,
    faChevronDown,
    faLayerGroup
);

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .component('font-awesome-icon', FontAwesomeIcon)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});