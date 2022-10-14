import './bootstrap';
import '../css/app.css'
import 'flowbite';

import { createApp } from 'vue';
import { createWebHistory, createRouter } from "vue-router";
import routes from './routes.js';
import App from './App.vue';

const router = createRouter({
    history: createWebHistory(),
    routes, 
})

const app = createApp(App)

app.use(router)

app.mount('#app')
