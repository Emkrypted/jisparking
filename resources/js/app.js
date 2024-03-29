/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import router from './assets/router.js';
import Vuelidate from 'vuelidate';
import VueAWN from 'vue-awesome-notifications';
import Multiselect from 'vue-multiselect';
import VueMask from 'v-mask';
import vueRut from 'vue-rut';

Vue.component('branchoffice-select', require('./components/BranchOfficeListComponent.vue').default);

Vue.component('commune-select', require('./components/CommuneListComponent.vue').default);

Vue.component('create-dte-modal', require('./components/CreateModalDteComponent.vue').default);

Vue.component('listado', require('./components/ListadoComponent.vue').default);

Vue.component('menu-component', require('./components/MenuComponent.vue').default);

Vue.component('multiselect', Multiselect);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Vuelidate);
Vue.use(VueAWN);
Vue.use(require('vue-moment'));
Vue.use(VueMask);
Vue.component('rol_permissions', require('./components/RolPermissionsComponent.vue'));
Vue.use(vueRut);

const app = new Vue({
    el: '#app',
    data: {
        rut: '',
    },
    router,
});
