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
        path: '/team/create',
        component: require('../components/teams/CreateTeam.vue'),
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
    },
    {
        path: '/survey/:id/question/:question/pin',
        component: require('../components/survey/PinSettings.vue'),
        meta: {
            auth: true
        },
        name: 'pin.edit'
    },
    {
        path: '/survey/:id/responses',
        component: require('../components/survey/response/SurveyResponses.vue'),
        meta: {
            auth: true
        },
        name: 'survey.responses'
    },
    {
        path: '/survey/:id/overview',
        component: require('../components/survey/response/SurveyOverview.vue'),
        meta: {
            auth: true
        },
        name: 'survey.overview'
    },
    {
        path: '/survey/:id/responses/team/:team',
        component: require('../components/survey/response/TeamOverview.vue'),
        meta: {
            auth: true
        },
        name: 'survey.response.team'
    },
    {
        path: '/survey/:id/responses/team/:team/summary',
        component: require('../components/survey/response/TeamGrid.vue'),
        meta: {
            auth: true
        },
        name: 'survey.response.team.overview'
    },
    {
        path: '/survey/:survey/response/:id',
        component: require('../components/survey/response/SurveyResponse.vue'),
        meta: {
            auth: true
        },
        name: 'survey.response'
    },
    {
        path: '/admin/users',
        component: require('../components/admin/UserList.vue'),
        meta: {
            auth: true,
            admin: true
        },
        name: 'admin.users'
    }
];


const router = new VueRouter({
    routes,
    linkExactActiveClass: 'active',
    mode: 'history',
    scrollBehavior(to, from, saved) {
        if (saved)
            return saved;
        else
            return {x: 0, y: 0}
    }
});

export default router;