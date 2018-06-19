
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./filters');
require('./routing/api');
window.Vue = require('vue');

// plugins
import store from './vuex/store';
import router from './routing/router';

// global event handler
Vue.prototype.$eventHub = new Vue();

// axios handler
axios.interceptors.response.use(null, (error) => {
    Vue.prototype.$eventHub.$emit('show-dialog', {
        className: 'alert-danger',
        text: error.response.data.message
    });
    return Promise.reject(error);
});

if(document.getElementById('app')) {
    const app = new Vue({
        el: '#app',
        store,
        router
    });
}
