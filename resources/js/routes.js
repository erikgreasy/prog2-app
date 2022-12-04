import Home from './views/Home.vue';
import About from './views/About.vue';
import Login from './views/Login.vue';
import Websocket from './views/Websocket.vue';
import AdminAssignmentsIndex from './views/admin/assignments/Index.vue';
import AdminAssignmentsEdit from './views/admin/assignments/Edit.vue';
import AdminAssignmentsCreate from './views/admin/assignments/Create.vue';
import PageNotFound from './views/PageNotFound.vue';
import AssignmentsIndex from './views/public/assignments/Index.vue';
import AssignmentsShow from './views/public/assignments/Show.vue';
import AdminDashboard from './views/admin/Dashboard.vue';
import AdminUsersIndex from './views/admin/users/Index.vue';
import AdminUsersEdit from './views/admin/users/Edit.vue';

import auth from './middleware/auth.js';

import Admin from './views/layouts/Admin.vue'
import guest from './middleware/guest';

export default [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            middleware: [guest]
        }
    },
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: '/zadania',
        name: 'assignments.index',
        component: AssignmentsIndex,
    },
    {
        path: '/zadania/:slug',
        name: 'assignments.show',
        component: AssignmentsShow
    },
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: {
            layout: Admin,
            middleware: [auth]
        }
    },
    {
        path: "/admin/assignments",
        name: 'admin.assignments.index',
        component: AdminAssignmentsIndex,
        meta: {
            layout: Admin
        }
    },
    {
        path: "/admin/assignments/:id/edit",
        name: 'admin.assignments.edit',
        component: AdminAssignmentsEdit,
        meta: {
            layout: Admin
        }
    },
    {
        path: "/admin/assignments/create",
        name: 'admin.assignments.create',
        component: AdminAssignmentsCreate,
        meta: {
            layout: Admin
        }
    },
    {
        path: '/admin/users',
        name: 'admin.users.index',
        component: AdminUsersIndex,
        meta: {
            layout: Admin
        }
    },
    {
        path: '/admin/users/:id/edit',
        name: 'admin.users.edit',
        component: AdminUsersEdit,
        meta: {
            layout: Admin
        }
    },
    {
        path: "/about",
        name: "about",
        component: About,
    },
    {
        path: '/websocket',
        name: 'websocket',
        component: Websocket
    },
    {
        path: '/:catchAll(.*)',
        name: '404',
        component: PageNotFound
    }
];
