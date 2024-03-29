import './bootstrap';
import '../css/app.css'
import 'flowbite';

import { createApp } from 'vue';
import { createWebHistory, createRouter } from "vue-router";
import { createPinia } from "pinia";

import routes from '@/routes/index';
import App from '@/App.vue';
import AdminCard from '@/components/AdminCard.vue'
import middlewarePipeline from '@/middlewarePipeline';
import { useAuthStore } from '@/stores/auth';
import log from '@/middleware/log';
import { useUserNotificationsStore } from '@/stores/userNotifications';
import { useProcessingAssignmentsStore } from '@/stores/processingAssignments'

const app = createApp(App)


app.use(createPinia());

app.component('AdminCard', AdminCard)

const store = useAuthStore()

const userNotificationsStore = useUserNotificationsStore()

const globalMiddlewares = [log]

import useEventsBus from '@/eventBus'

const processingAssignmentsStore = useProcessingAssignmentsStore()
const { emit } = useEventsBus()

axios.get('/api/user')
.then(res => {
    store.user = res.data
    store.loggedIn = true
    console.log('success login')

    window.Echo.private('App.Models.User.' + store.user?.id)
        .notification((notification) => {
            emit('assignmentProcessed')
            userNotificationsStore.getNotifications()
            processingAssignmentsStore.removeAssignment(notification.assignment.id)
        });
})
.catch(err => {
    if(err.response.status == 401) {
        console.log('not logged in')
    }
})
.finally(() => {

    const router = createRouter({
        history: createWebHistory(),
        routes,
    })

    router.beforeEach((to, from, next) => {
        const routeMiddlewares = to.meta.middleware;
        const context = { to, from, next, router };

        var middlewares = globalMiddlewares

        if(routeMiddlewares) {
            middlewares = [...middlewares, ...routeMiddlewares]
        }

        // Check if no middlware on route
        if (!middlewares) {
            return next();
        }

        middlewares[0]({
            ...context,
            next: middlewarePipeline(context, middlewares, 1)
        });
    })


    app.use(router)

    app.mount('#app')
})

