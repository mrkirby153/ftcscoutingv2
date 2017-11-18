<style>

</style>

<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                Responses for Team {{$route.params.team}}
            </div>
            <div class="ui attached segment">
                <table class="ui table">
                    <thead>
                    <tr>
                        <th>Match Number</th>
                        <th>Submitted By</th>
                        <th>Submitted On</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="response in responses">
                        <td>{{response.match_number}}</td>
                        <td>{{response.submitter.name}}</td>
                        <td>{{response.created_at}}</td>
                        <td><router-link :to="{name: 'survey.response', params: {id: response.id, survey: $route.params.id}}" class="ui icon button"><i class="right arrow icon"></i></router-link></td>
                    </tr>
                    </tbody>
                </table>
                <router-link :to="{name: 'survey.responses', params: {id: $route.params.id}}" class="ui icon button"><i class="left arrow icon"></i></router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                responses:[]
            }
        },

        mounted(){
            this.get();
        },

        methods:{
            get(){
                axios.get(route('response.team.overview', {team: this.$route.params.team, survey: this.$route.params.id})).then(resp => {
                    this.responses = resp.data;
                })
            }
        }
    }
</scrIpt>