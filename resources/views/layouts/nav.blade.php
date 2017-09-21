<router-link to="/dashboard" class="item" v-if="user">Dashboard</router-link>

<div class="ui dropdown item" v-show="teams.length > 0" tabindex="0">
    Teams
    <i class="dropdown icon"></i>
    <div class="menu">
        <div v-for="team in teams">
            <router-link :to="'/team/'+team.id" class="item">Team @{{ team.team_number }}: @{{ team.name }}</router-link>
        </div>
    </div>
</div>