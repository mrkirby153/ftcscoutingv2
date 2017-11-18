<style>

</style>

<template>
    <div class="row">
        <div class="column">
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
                            <td><router-link :to="{'name': 'survey.response.team',
                            params: {id: $route.params.id, team: response.team_number}}" class="ui icon button"><i class="right arrow icon"></i></router-link> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {


        data() {
            return {
                responses: []
            }
        },

        mounted(){
            this.getOverview();
        },

        methods: {
            getOverview() {
                axios.get(route('response.overview', {survey: this.$route.params.id})).then(resp => {
                    this.responses = resp.data;
                })
            }
        }
    }
</scrIpt>