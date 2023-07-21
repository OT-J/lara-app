import { createRouter, createWebHistory } from "vue-router";
import login from '../components/login.vue'
import admin from '../components/admin.vue'
import employee from '../components/employee.vue'
import registerEmployee from '../components/registerEmployee.vue'


const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes:[
        {
            path:'/',
            component: login
        },
        {
            path:'/admin',
            component: admin
        },
        {
            path:'/employee',
            component: employee
        },
        {
            path:'/register-employee',
            component: registerEmployee
        },
        {
            path:'/:pathMatch(.*)*',
            component: login
        },
    ]
});

export default router;
