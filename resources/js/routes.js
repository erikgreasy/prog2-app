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
        path: '/zadania',
        name: 'assignments.index',
        component: AssignmentsIndex
    },
    {
        path: '/zadania/:id',
        name: 'assignments.show',
        component: AssignmentsShow
    },
    {
        path: '/admin',
        name: 'admin.dashboard',
        component: AdminDashboard
    },
    {
        path: "/admin/assignments",
        name: 'admin.assignments.index',
        component: AdminAssignmentsIndex
    },
    {
        path: "/admin/assignments/:id/edit",
        name: 'admin.assignments.edit',
        component: AdminAssignmentsEdit
    },
    {
        path: "/admin/assignments/create",
        name: 'admin.assignments.create',
        component: AdminAssignmentsCreate
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
