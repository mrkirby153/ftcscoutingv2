import {
    CLEAR_RESPONSE_DATA,
    PUSH_USER_TEAM, REMOVE_QUESTION_FROM_SURVEY, REMOVE_TEAM_MEMBER, SET_ACCEPTED, SET_EDITING_QUESTION, SET_LOADING,
    SET_QUESTION_DATA, SET_RESPONSE_DATA, SET_SURVEY, SET_SURVEY_QUESTION_TYPE, SET_QUESTION_TITLE, SET_USER,
    SET_USER_TEAMS, UPDATE_QUESTION_DATA, PUSH_QUESTION, UPDATE_QUESTION_ORDER, SET_QUESTION_OPTIONS,
    REMOVE_QUESTION_OPTION, ADD_QUESTION_OPTION, SET_QUESTION_OPTION, CLEAR_EDIT_DATA, SET_CACHED_GUARD_CHECK,
    CLEAR_ERRORS, SET_ERRORS, CLEAR_ERROR
} from "./mutationTypes";

export default {
    [SET_USER](state, payload) {
        state.user = payload;
    },
    [SET_LOADING](state, payload) {
        state.loading = payload;
    },
    [SET_USER_TEAMS](state, payload) {
        state.teams = payload;
    },
    [PUSH_USER_TEAM](state, payload) {
        state.teams.push(payload);
    },
    [REMOVE_TEAM_MEMBER](state, payload) {
        state.teams = state.teams.filter(t => t.team_id !== payload)
    },
    [SET_ACCEPTED](state, payload) {
        state.teams.forEach(t => {
            if (t.member_id === payload) {
                t.pending_accept = false;
            }
        })
    },
    [SET_SURVEY](state, payload) {
        state.survey = payload;
    },
    [SET_EDITING_QUESTION](state, payload) {
        state.editingQuestion = payload;
    },
    [UPDATE_QUESTION_DATA](state, payload) {
        state.survey.questions.forEach(d => {
            if (d.id === payload.id) {
                d.extra_data = payload.data;
                d.question_name = payload.title;
            }
        });
    },
    [REMOVE_QUESTION_FROM_SURVEY](state, payload) {
        state.survey.questions = _.reject(state.survey.questions, q => q.id === payload);
    },
    [SET_RESPONSE_DATA](state, payload) {
        state.response[payload.question] = payload.response;
    },
    [CLEAR_RESPONSE_DATA](state, payload) {
        state.response = {};
    },
    [SET_SURVEY_QUESTION_TYPE](state, payload) {
        let id = payload.id;
        let type = payload.type;
        state.survey.questions.forEach(d => {
            if (d.id === id) {
                d.type = type;
            }
        })
    },
    [SET_QUESTION_TITLE](state, payload) {
        state.editingTitle = payload;
    },
    [PUSH_QUESTION](state, payload) {
        state.survey.questions.push(payload);
    },
    [UPDATE_QUESTION_ORDER](state, payload) {
        const targetId = payload.id;
        const newOrder = payload.order;

        state.survey.questions.forEach(d => {
            if (d.id === targetId)
                d.order = newOrder;
        })
    },
    [SET_QUESTION_OPTION](state, payload) {
        state.editingOptions[payload.index] = payload.data;
    },
    [SET_QUESTION_OPTIONS](state, payload) {
        state.editingOptions = payload;
    },
    [REMOVE_QUESTION_OPTION](state, payload) {
        state.editingOptions.splice(payload, 1);
    },
    [ADD_QUESTION_OPTION](state, payload) {
        if(state.editingOptions === undefined)
            state.editingOptions = [];
        state.editingOptions.push(payload);
    },
    [CLEAR_EDIT_DATA](state){
        state.editingQuestion = null;
        state.editingOptions = [];
        state.editingTitle = "";
    },
    [SET_CACHED_GUARD_CHECK](state, payload){
        let model = payload.model;
        let check = payload.check;
        let val = payload.value;
        let property = payload.property;
        let extra = payload.extra;
        if(state.guardCache[model] === undefined){
            state.guardCache[model] = {};
        }
        if(state.guardCache[model][check] === undefined){
            state.guardCache[model][check] = {};
        }
        if(state.guardCache[model][check][property] === undefined){
            state.guardCache[model][check][property] = {};
        }
        if(state.guardCache[model][check][property][val] === undefined){
            state.guardCache[model][check][property][val] = {};
        }
        if(state.guardCache[model][check][property][val][extra] === undefined){
            state.guardCache[model][check][property][val][extra] = {};
        }
        state.guardCache[model][check][property][val][extra] = {
            result: payload.result,
            time: new Date().getTime()
        }
    },
    [CLEAR_ERRORS](state, payload){
        state.errors = {}
    },
    [SET_ERRORS](state, payload){
        state.errors = payload
    },
    [CLEAR_ERROR](state, payload){
        delete state.errors[payload];
    }
}