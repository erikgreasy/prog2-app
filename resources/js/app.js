import './bootstrap';
import '../css/app.css'
import 'flowbite';

import { createApp } from 'vue';
import { createWebHistory, createRouter } from "vue-router";
import { createPinia } from "pinia";

import routes from './routes.js';
import App from './App.vue';
import AdminCard from './components/AdminCard.vue'

const router = createRouter({
    history: createWebHistory(),
    routes, 
})

const app = createApp(App)

app.use(router)

app.use(createPinia());

app.component('AdminCard', AdminCard)

app.mount('#app')
