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
import AdminStudentsIndex from '@/views/admin/students/Index.vue'
import AdminStudentsShow from '@/views/admin/students/Show.vue'
import AdminAssignmentTestsIndex from '@/views/admin/assignment-tests/Index.vue'
import AdminAssignmentTestsCreate from '@/views/admin/assignment-tests/Create.vue'
import AdminAssignmentTestsEdit from '@/views/admin/assignment-tests/Edit.vue'
import AdminAssignmentTestsShow from '@/views/admin/assignment-tests/Show.vue'
import Myprofile from '@/views/Myprofile.vue'
import AssignmentsSubmissionsShow from '@/views/public/assignments-submissions/Show.vue'
import Login from '@/views/Login.vue'

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
        path: '/zadania/:slug/odovzdania/:index',
        name: 'assignments.submissions.show',
        component: AssignmentsSubmissionsShow,
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
        path: "/admin/assignments/:id/tests",
        name: 'admin.assignment-tests.index',
        component: AdminAssignmentTestsIndex,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: "/admin/assignments/:id/tests/create",
        name: 'admin.assignment-tests.create',
        component: AdminAssignmentTestsCreate,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: "/admin/assignments/:assignment_id/tests/:test_id",
        name: 'admin.assignment-tests.show',
        component: AdminAssignmentTestsShow,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: "/admin/assignments/:assignment_id/tests/:test_id/edit",
        name: 'admin.assignment-tests.edit',
        component: AdminAssignmentTestsEdit,
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
            middleware: [auth, admin]
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
        path: '/admin/students',
        name: 'admin.students.index',
        component: AdminStudentsIndex,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: '/admin/students/:id',
        name: 'admin.students.show',
        component: AdminStudentsShow,
        meta: {
            layout: Admin,
            middleware: [auth, teacher]
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
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
