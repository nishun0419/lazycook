
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';

Vue.use(VueRouter);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
// Vue.component('todo-component', require('./components/TodoComponent.vue'));
// Vue.component('login-form',require('./components/Login_form.vue'));
Vue.component('nav-component',require('./components/NavComponent.vue'));

const router = new VueRouter({
	mode:'history',
	routes: [
		{path: '/', component: require('./components/Login_form.vue')},
		{path: '/todo', component: require('./components/TodoComponent.vue')},
	]
});

const app = new Vue({
	router,
    el: '#app'
});
