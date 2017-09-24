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
    },

    {
        path: '/oauth/',
        component: require('../components/OAuth.vue'),
        children: [
            {
                path: '',
                component: require('../components/passport/AuthorizedClients.vue'),
                name: 'oauth.authorized'
            },
            {
                path: 'clients',
                component: require('../components/passport/Clients.vue'),
                name: 'oauth.clients'
            },
            {
                path: 'tokens',
                component: require('../components/passport/PersonalAccessTokens.vue'),
                name: 'oauth.tokens'
            }
        ],
        meta: {
            auth: true
        }
    },
    {
        path: '/team/:id',
        component: require('../components/teams/TeamDashboard.vue')
    },

    {
        path: '/team/create',
        component: require('../components/teams/CreateTeam.vue'),
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