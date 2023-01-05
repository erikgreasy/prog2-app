import Home from './views/Home.vue';
import About from './views/About.vue';
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
import AdminUsersSubmissionsIndex from '@/views/admin/submissions/Index.vue';
import AdminUsersSubmissionsShow from '@/views/admin/submissions/Show.vue';
import Myprofile from '@/views/Myprofile.vue'

import auth from './middleware/auth.js';
import admin from './middleware/admin';
import teacher from './middleware/teacher';

import Admin from './views/layouts/Admin.vue'
import guest from './middleware/guest';

export default [
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
        path: '/my-profile',
        name: 'myprofile',
        component: Myprofile,
        meta: {
            middleware: [auth]
        }
    },
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: AdminDashboard,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: "/admin/assignments",
        name: 'admin.assignments.index',
        component: AdminAssignmentsIndex,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: "/admin/assignments/:id/edit",
        name: 'admin.assignments.edit',
        component: AdminAssignmentsEdit,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: "/admin/assignments/create",
        name: 'admin.assignments.create',
        component: AdminAssignmentsCreate,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: '/admin/users',
        name: 'admin.users.index',
        component: AdminUsersIndex,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: '/admin/users/:id/edit',
        name: 'admin.users.edit',
        component: AdminUsersEdit,
        meta: {
            layout: Admin,
            middleware: [auth, admin]
        }
    },
    {
        path: '/admin/users/:userId/submissions',
        name: 'admin.users.submissions',
        component: AdminUsersSubmissionsIndex,
        meta: {
            layout: Admin,
            middleware: [auth, admin]
        }
    },
    {
        path: '/admin/users/:userId/submissions/:id',
        name: 'admin.users.submissions.show',
        component: AdminUsersSubmissionsShow,
        meta: {
            layout: Admin,
            middleware: [auth, admin]
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
