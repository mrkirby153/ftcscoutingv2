<style scoped>
    .bold {
        font-size: 1.75em;
    }
</style>

<template>
    <div>
        <div class="ui top attached header">
            Survey Responses
        </div>
        <div class="ui attached segment">
            <table class="ui table">
                <thead>
                <tr>
                    <th>Team Number</th>
                    <th>Responses</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="response in responses">
                    <td>{{response.team_number}}</td>
                    <td>{{response.responses}}</td>
                    <td>
                        <router-link :to="{'name': 'survey.response.team',
                            params: {id: $route.params.id, team: response.team_number}}" class="ui icon button"><i
                                class="right arrow icon"></i></router-link>
                    </td>
                </tr>
                </tbody>
            </table>
            <button class="ui fluid button" @click.prevent="getRanked">Rank Teams by PIN</button>
        </div>
        <div class="ui modal" id="ranked">
            <div class="header">Teams Ranked by PIN</div>
            <div class="content">
                <ol>
                    <li v-for="(data, index) in pinRanked" :class="{'bold': index + 1 <= 3}">Team {{data.team}} - {{data.pin}}</li>
                </ol>
            </div>
        </div>
    </div>
</template>

<script>
    export default {


        data() {
            return {
                responses: [],
                pinRanked: []
            }
        },

        mounted() {
            this.getOverview();
        },

        methods: {
            getOverview() {
                axios.get(route('response.overview', {survey: this.$route.params.id})).then(resp => {
                    this.responses = resp.data;
                })
            },
            getRanked() {
                axios.get(route('survey.rank', {survey: this.$route.params.id})).then(resp => {
                    this.pinRanked = resp.data;
                    $("#ranked").modal('show');
                });
            }
        }
    }
</scrIpt>