import VueRouter from 'vue-router';
import router from './router/routes';
import {store} from "./vuex/vuex";
import {GET_USER_TEAMS, SET_USER} from "./vuex/mutationTypes";
import Raven from "raven-js";
import RavenVue from 'raven-js/plugins/vue';

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./semantic');
require('./form/form');

require('./router/guards');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);


Vue.component('example', require('./components/Example.vue'));

Vue.component('passport-clients', require('./components/passport/Clients.vue'));
Vue.component('passport-tokens', require('./components/passport/PersonalAccessTokens.vue'));
Vue.component('passport-authorized', require('./components/passport/AuthorizedClients.vue'));

Vue.component('question-multiplechoice', require('./components/survey/components/MultipleChoice.vue'));
Vue.component('question-text', require('./components/survey/components/Text.vue'));
Vue.component('survey-questions', require('./components/survey/SurveyQuestions.vue'));
Vue.component('type-selector', require('./components/survey/components/TypeSelector.vue'));

Vue.component('guard-component', require('./components/GuardComponent.vue'));


const app = new Vue({
    router,
    store,
    el: '#app',
    data() {
        return {
            user: null
        }
    },

    mounted() {
        this.$store.commit(SET_USER, window.User);
        if (window.User !== null)
            this.$store.dispatch(GET_USER_TEAMS);
    },

    computed: {
        teams() {
            return this.$store.getters.teams;
        },
        pendingTeams() {
            return this.$store.getters.pendingTeams;
        }
    }

});

if (window.env !== "local" && window.sentryJs !== undefined) { // Run sentry if not local environment
    Raven
        .config(window.sentryJs) // TODO: Don't hardcode this.
        .addPlugin(RavenVue, Vue)
        .install();
}