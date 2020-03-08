import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../pages/index'
import Show from '../pages/show'
import War from '../pages/war'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/show/:id',
        name: 'show',
        component: Show
    },
    {
        path: '/war/:id',
        name: 'war',
        component: War
    }
]

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes
})

export default router
