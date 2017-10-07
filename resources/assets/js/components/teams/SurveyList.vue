<style>

</style>

<template>
    <div>
        <h1>Surveys</h1>
        <router-link :to="'/team/'+$route.params.id+'/create'" class="ui button"><i class="plus icon"></i>Create a Survey</router-link>
        <table class="ui celled table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Responses</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="survey in surveys">
                <td>
                    <router-link :to="'/survey/'+survey.id">{{survey.name}}</router-link>
                </td>
                <td>
                    <router-link :to="'/survey/'+survey.id+'/responses'">0</router-link>
                </td>
                <td>
                    <router-link to="/" class="ui button">Edit</router-link>
                    <button class="ui button">Archive</button>
                    <button class="ui button">Delete</button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                surveys: null
            }
        },

        mounted() {
            this.retrieveSurveys();
        },

        watch: {
            '$route'(to, from) {
                if (to.params.id !== from.params.id) {
                    this.retrieveSurveys();
                }
            }
        },

        methods: {
            retrieveSurveys() {
                axios.get(route('survey.list', {team: this.$route.params.id})).then(resp => {
                    this.surveys = resp.data;
                })
            }
        }
    }
</scrIpt>