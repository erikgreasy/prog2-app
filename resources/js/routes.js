import Home from './views/Home.vue';
import About from './views/About.vue';
import Login from './views/Login.vue';

export default [
    {
        path: '/login',
        name: 'login',
        component: Login
    },
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/about",
        name: "about",
        component: About,
    },
];
