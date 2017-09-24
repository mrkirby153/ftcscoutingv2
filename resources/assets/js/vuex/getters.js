export default {
    pendingTeams: state => {
        return state.teams.filter(team => team.pending_accept)
    },
    teams: state => {
        return state.teams.filter(team => !team.pending_accept)
    }
}