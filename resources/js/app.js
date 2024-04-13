require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import router from './router';
import store from './store/index';
import customAxios from './customAxios';

window.Vue = Vue;
Vue.use(VueRouter);

Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('footer-component', require('./components/FooterComponent.vue').default);
Vue.component('go-top-component', require('./components/GoTop.vue').default);

const app = new Vue({
    el: '#app',
    router,
    store,
    customAxios,
});
