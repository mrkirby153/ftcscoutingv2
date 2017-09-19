import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('../components/Home.vue')
    },
    {
        path: '/dashboard',
        component: require('../components/Dashboard.vue'),
        meta: {
            auth: true
        }
    }
];


const router = new VueRouter({
    routes,
    linkExactActiveClass: 'active',
    mode: 'history'
});

export default router;