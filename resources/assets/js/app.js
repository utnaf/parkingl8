
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

Vue.prototype.$eventHub = new Vue();
const app = new Vue({
    el: '#app',
    store,
    router
});
