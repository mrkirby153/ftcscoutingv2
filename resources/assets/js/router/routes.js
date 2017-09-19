import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('../components/Example.vue')
    }
];


const router = new VueRouter({
    routes,
    linkExactActiveClass: 'active',
    mode: 'history'
});

export default router;