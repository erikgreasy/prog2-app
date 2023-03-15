import Home from '@/views/Home.vue';
import About from '@/views/About.vue';
import Websocket from '@/views/Websocket.vue';
import PageNotFound from '@/views/PageNotFound.vue';
import AssignmentsIndex from '@/views/public/assignments/Index.vue';
import AssignmentsShow from '@/views/public/assignments/Show.vue';
import Myprofile from '@/views/Myprofile.vue'
import AssignmentsSubmissionsShow from '@/views/public/assignments-submissions/Show.vue'
import AssignmentLayout from '@/views/layouts/Assignment.vue'
import AssignmentsShowSubmission from '@/views/public/assignments/Submission.vue'
import AssignmentsShowMaterials from '@/views/public/assignments/Materials.vue'
import AssignmentsShowInstructions from '@/views/public/assignments/Instructions.vue'
import Login from '@/views/Login.vue'

import auth from '@/middleware/auth.js';

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
        component: AssignmentsShow,
        meta: {
            layout: AssignmentLayout,
        }
    },
    {
        path: '/zadania/:slug/odovzdanie',
        name: 'assignments.show.submission',
        component: AssignmentsShowSubmission,
        meta: {
            layout: AssignmentLayout,
        }
    },
    {
        path: '/zadania/:slug/instrukcie',
        name: 'assignments.show.instructions',
        component: AssignmentsShowInstructions,
        meta: {
            layout: AssignmentLayout,
        }
    },
    {
        path: '/zadania/:slug/materialy',
        name: 'assignments.show.materials',
        component: AssignmentsShowMaterials,
        meta: {
            layout: AssignmentLayout,
        }
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
]
