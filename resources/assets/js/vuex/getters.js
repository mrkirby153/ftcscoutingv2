export default {
    pendingTeams: state => {
        return state.teams.filter(team => team.pending_accept)
    },
    teams: state => {
        return state.teams.filter(team => !team.pending_accept)
    },
    questions: state => {
        if(state.survey.questions === undefined)
            return undefined;
        return state.survey.questions.sort((a, b)=>{
            if(a.order < b.order) return -1;
            if(a.order > b.order) return 1;
            return 0;
        })
    }
}