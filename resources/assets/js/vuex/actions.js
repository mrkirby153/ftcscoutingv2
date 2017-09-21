import axios from 'axios';
import {GET_USER_TEAMS, SET_USER_TEAMS} from "./mutationTypes";

export default {
    [GET_USER_TEAMS]({commit}) {
        axios.get(route('team.list')).then(resp => {
            commit(SET_USER_TEAMS, resp.data)
        })
    }
}