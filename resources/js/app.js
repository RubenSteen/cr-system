require('./bootstrap');

// Importing InertiaJS
import { InertiaApp } from '@inertiajs/inertia-vue';

// Importing Vue JS
import Vue from 'vue';

// Use laravel Routes in your application (https://github.com/tightenco/ziggy)
Vue.prototype.$route = (...args) => route(...args).url()

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
