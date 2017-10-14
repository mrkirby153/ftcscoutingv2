import axios from 'axios';
import state from './state';
import {
    ACCEPT_MEMBER_INVITE, DELETE_QUESTION, DISPATCH_SURVEY_QUESTION_TYPE, GET_SURVEY, GET_USER_TEAMS,
    REMOVE_QUESTION_FROM_SURVEY, SET_ACCEPTED,
    SET_EDITING_QUESTION,
    SET_QUESTION_DATA, SET_SURVEY, SET_SURVEY_QUESTION_TYPE,
    SET_USER_TEAMS, UPDATE_QUESTION_DATA
} from "./mutationTypes";

export default {
    [GET_USER_TEAMS]({commit}) {
        axios.get(route('team.list')).then(resp => {
            commit(SET_USER_TEAMS, resp.data)
        })
    },
    [ACCEPT_MEMBER_INVITE](context, data) {
        axios.patch(route('team.member.accept', {member: data})).then(resp => {
            context.commit(SET_ACCEPTED, data);
        });
    },
    [GET_SURVEY](context, data) {
        axios.get(route('survey.get', {survey: data})).then(resp => {
            context.commit(SET_SURVEY, resp.data)
        })
    },
    [SET_QUESTION_DATA](context, payload) {
        let questionId = payload.id;
        let data = payload.data;
        let title = payload.title;
        context.commit(UPDATE_QUESTION_DATA, {
            id: questionId,
            title: title,
            data: data
        });
        axios.patch(route('survey.question.update', {survey: context.state.survey.id, question: questionId}), {
            question_name: title,
            extra_data: data
        }).then(resp => {
            context.commit(SET_EDITING_QUESTION, null)
        });
    },
    [DELETE_QUESTION](context, payload){
        axios.delete(route('survey.question.delete', {survey: context.state.survey.id, question: payload})).then(resp => {
            context.commit(REMOVE_QUESTION_FROM_SURVEY, payload)
        });
    },
    [DISPATCH_SURVEY_QUESTION_TYPE](context, payload){
        let id = payload.id;
        let type = payload.type;
        axios.patch(route('survey.question.type', {survey: state.survey.id, question: id}), {
            type: type
        }).then(resp => {
            context.commit(SET_SURVEY_QUESTION_TYPE, payload);
        });
    }
}