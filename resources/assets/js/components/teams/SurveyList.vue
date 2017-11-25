<style>

</style>

<template>
    <div>
        <h1>Surveys</h1>
        <guard-component model="Surveys\Survey::class" check="create"
                         :additional="'\\App\\Models\\Team,id,'+$route.params.id">
            <router-link :to="'/team/'+$route.params.id+'/create'" class="ui button"><i class="plus icon"></i>Create a Survey
            </router-link>
        </guard-component>
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
                    <router-link :to="'/survey/'+survey.id+'/responses'">{{survey.response_count}}</router-link>
                </td>
                <td>
                    <guard-component model="Surveys\Survey" check="update" :value="survey.id">
                        <router-link :to="'/survey/'+survey.id" class="ui green button">Take Survey</router-link>
                        <router-link :to="'/survey/'+survey.id+'/responses'" class="ui labeled button">
                            <div class="ui purple button">
                                Responses
                            </div>
                            <a class="ui basic purple left pointing label">
                                {{survey.response_count}}
                            </a>
                        </router-link>
                        <router-link :to="{name: 'survey.edit', params: {id: survey.id}}" class="ui button">Edit
                        </router-link>
                        <button class="ui button">Archive</button>
                        <button class="ui button">Delete</button>
                        <div slot="no-access">
                            <router-link :to="'/survey/'+survey.id" class="ui green button">Take Survey</router-link>
                            <router-link :to="'/survey/'+survey.id+'/responses'" class="ui labeled button">
                                <div class="ui purple button">
                                    Responses
                                </div>
                                <a class="ui basic purple left pointing label">
                                    {{survey.response_count}}
                                </a>
                            </router-link>
                        </div>
                    </guard-component>
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
                    this.surveys.forEach(r => {
                        if (r.response_count === null)
                            r.response_count = 0;
                    })
                })
            }
        }
    }
</scrIpt>