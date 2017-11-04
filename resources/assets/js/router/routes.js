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
        component: require('../components/teams/TeamDashboard.vue'),
        children: [
            {
                path: '',
                component: require('../components/teams/TeamOverview.vue'),
                name: 'team.overview'
            },
            {
                path: 'members',
                component: require('../components/teams/TeamMeber.vue'),
                name: 'team.members'
            },
            {
                path: 'surveys',
                component: require('../components/teams/SurveyList.vue'),
                name: 'team.surveys'
            },
            {
                path: 'create',
                component: require('../components/teams/CreateSurvey.vue'),
                name: 'survey.create'
            }
        ]

    },

    {
        path: '/team/create',
        component: require('../components/teams/CreateTeam.vue'),
        meta: {
            auth: true
        }
    },
    {
        path: '/survey/:id',
        component: require('../components/survey/SurveyView.vue'),
        meta: {
            auth: true
        }
    },
    {
        path: '/survey/:id/edit',
        component: require('../components/survey/SurveyView.vue'),
        meta: {
            auth: true
        },
        name: 'survey.edit'
    }
];


const router = new VueRouter({
    routes,
    linkExactActiveClass: 'active',
    mode: 'history'
});

export default router;