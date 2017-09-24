import axios from 'axios';
import {ACCEPT_MEMBER_INVITE, GET_USER_TEAMS, SET_ACCEPTED, SET_USER_TEAMS} from "./mutationTypes";

export default {
    [GET_USER_TEAMS]({commit}) {
        axios.get(route('team.list')).then(resp => {
            commit(SET_USER_TEAMS, resp.data)
        })
    },
    [ACCEPT_MEMBER_INVITE](context, data){
        axios.patch(route('team.member.accept', {member: data})).then(resp => {
            context.commit(SET_ACCEPTED, data);
        });
    }
}