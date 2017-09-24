import {PUSH_USER_TEAM, REMOVE_TEAM_MEMBER, SET_ACCEPTED, SET_USER_TEAMS} from "./mutationTypes";

export default {
    [SET_USER_TEAMS](state, payload) {
        state.teams = payload;
    },
    [PUSH_USER_TEAM](state, payload) {
        state.teams.push(payload);
    },
    [REMOVE_TEAM_MEMBER](state, payload) {
        state.teams = state.teams.filter(t => t.team_id !== payload)
    },
    [SET_ACCEPTED](state, payload){
        state.teams.forEach(t => {
            if(t.member_id === payload){
                t.pending_accept = false;
            }
        })
    }
}