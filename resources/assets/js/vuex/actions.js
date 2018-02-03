import axios from 'axios';
import state from './state';
import toastr from 'toastr'
import {
    ACCEPT_MEMBER_INVITE, ADD_NEW_QUESTION, CLEAR_EDIT_DATA, CLEAR_ERRORS, CLEAR_RESPONSE_DATA, COMMIT_SURVEY_DATA,
    DELETE_QUESTION,
    DISPATCH_SURVEY_QUESTION_TYPE,
    GET_SURVEY,
    GET_USER_TEAMS, PUSH_QUESTION,
    REMOVE_QUESTION_FROM_SURVEY, RETRIEVE_QUESTION_DATA, SET_ACCEPTED,
    SET_EDITING_QUESTION, SET_ERRORS, SET_LOADING,
    SET_QUESTION_DATA, SET_QUESTION_OPTIONS, SET_QUESTION_ORDER, SET_QUESTION_TITLE, SET_SURVEY,
    SET_SURVEY_QUESTION_TYPE,
    SET_USER_TEAMS, UPDATE_QUESTION_DATA, UPDATE_QUESTION_ORDER
} from "./mutationTypes";

export default {
    [GET_USER_TEAMS]({commit}) {
        axios.get(route('team.list')).then(resp => {
            commit(SET_USER_TEAMS, resp.data)
        })
    },
    [ACCEPT_MEMBER_INVITE](context, data) {
        context.commit(SET_LOADING, true);
        axios.patch(route('team.member.accept', {member: data})).then(resp => {
            context.commit(SET_ACCEPTED, data);
            context.commit(SET_LOADING, false);
        }).catch(resp => {
            toastr["error"]("An error occurred, please try again", "Error")
            throw resp;
        });
    },
    [GET_SURVEY](context, data) {
        context.commit(SET_LOADING, true);
        axios.get(route('survey.get', {survey: data})).then(resp => {
            context.commit(SET_SURVEY, resp.data);
            context.commit(SET_LOADING, false);
        }).catch(resp => {
            toastr["error"]("An error occurred, please try again", "Error");
            throw resp;
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
            context.commit(CLEAR_EDIT_DATA);
        }).catch(resp => {
            toastr["error"]("An error occurred, please try again", "Error")
            throw resp;
        });
    },
    [DELETE_QUESTION](context, payload) {
        axios.delete(route('survey.question.delete', {
            survey: context.state.survey.id,
            question: payload
        })).then(resp => {
            context.commit(REMOVE_QUESTION_FROM_SURVEY, payload);
            context.commit(SET_EDITING_QUESTION, null);
        }).catch(resp => {
            toastr["error"]("An error occurred, please try again", "Error")
            throw resp;
        });
    },
    [DISPATCH_SURVEY_QUESTION_TYPE](context, payload) {
        let id = payload.id;
        let type = payload.type;
        axios.patch(route('survey.question.type', {survey: state.survey.id, question: id}), {
            type: type
        }).then(resp => {
            context.commit(SET_SURVEY_QUESTION_TYPE, payload);
        });
    },
    [COMMIT_SURVEY_DATA](context) {
        context.commit(CLEAR_ERRORS);
        context.commit(SET_LOADING, true);
        axios.put(route('survey.commit', {survey: state.survey.id}), state.response).then(resp => {
            toastr["success"]("Your response has been recorded!", "Success");
            context.commit(SET_LOADING, false);
            context.commit(CLEAR_RESPONSE_DATA);
            $('html, body').animate({scrollTop: 0}, 800);
        }).catch(resp => {
            context.commit(SET_LOADING, false);
            if(resp.response.status !== 422){
                toastr["error"]("An error occurred, try again.", "Error");
            }
            context.commit(SET_ERRORS, resp.response.data.errors);
            throw resp;
        })
    },
    [ADD_NEW_QUESTION](context) {
        context.commit(SET_LOADING, true);
        axios.put(route('survey.question.create', {survey: context.state.survey.id})).then(resp => {
            context.dispatch(GET_SURVEY, state.survey.id);
            context.commit(SET_LOADING, false);
        }).catch(resp => {
            context.commit(SET_LOADING, false);
            toastr["error"]("An error occurred when adding a question, please try again", "Error");
            throw resp;
        })
    },
    [RETRIEVE_QUESTION_DATA](context, payload){
        axios.get(route('question.get', {id: payload})).then(resp => {
            const question = resp.data;
            console.log("Question: " + question.type);
            console.log(question);
            context.commit(SET_EDITING_QUESTION, payload);
            context.commit(SET_QUESTION_TITLE, question.question_name);
            if(question.type === "RADIO" || question.type === "MULTIPLE_CHOICE") {
                context.commit(SET_QUESTION_OPTIONS, question.extra_data.items);
            }
        });
    },
    [SET_QUESTION_ORDER](context, payload) {
        let questions = [...context.getters.questions];
        let questionId = payload.question;
        let newOrder = payload.order;

        let index = 0;
        for(index = 0; index < questions.length; index++){
            if(questions[index].id == questionId)
                break;
        }

        console.log("Found at index " + index);

        // Remove the element from the array
        let q = questions.splice(index, 1)[0];
        console.log(q);
        q.order = newOrder;
        questions.splice(newOrder - 1, 0, q);

        // Verify that the questions orders are updated
        for(let i = 0; i < questions.length; i++){
            if(questions[i].order != (i + 1)){
                axios.patch(route('survey.question.order', {survey: context.state.survey.id, question: questions[i].id}), {
                    order: i+1
                }).then(resp => {
                    context.commit(UPDATE_QUESTION_ORDER, {
                        id: questions[i].id,
                        order: i+1
                    })
                })
            }
        }
    }
}