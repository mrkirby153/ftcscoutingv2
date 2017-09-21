import {PUSH_USER_TEAM, SET_USER_TEAMS} from "./mutationTypes";

export default {
    [SET_USER_TEAMS](state, payload) {
        state.teams = payload;
    },
    [PUSH_USER_TEAM](state, payload) {
        state.teams.push(payload);
    }
}