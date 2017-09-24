<router-link to="/dashboard" class="item" v-if="user">Dashboard</router-link>

<div class="ui dropdown item" v-show="teams.length > 0 || pendingTeams.length > 0" tabindex="0">
    Teams <div class="ui small circular label" v-if="pendingTeams.length > 0">@{{ pendingTeams.length }}</div>
    <i class="dropdown icon"></i>
    <div class="menu">
        <router-link v-for="team in teams" :key="team.team_id" :to="'/team/'+team.team_id" class="item">Team
            @{{ team.team_number }}: @{{ team.name }}
        </router-link>
        <div class="divider" v-if="pendingTeams.length > 0"></div>
        <div class="header" v-if="pendingTeams.length > 0">
            Pending Invites
        </div>
        <router-link v-for="team in pendingTeams" :key="team.team_id" :to="'/team/'+team.team_id" class="item">Team
            @{{ team.team_number }}: @{{ team.name }}
        </router-link>
    </div>
</div>