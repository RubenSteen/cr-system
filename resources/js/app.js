require('./bootstrap');

// Importing InertiaJS
import { InertiaApp } from '@inertiajs/inertia-vue';

// Importing Vue JS
import Vue from 'vue';

Vue.use(InertiaApp);

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
