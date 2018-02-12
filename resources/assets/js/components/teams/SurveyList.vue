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
                    <router-link :to="'/survey/'+survey.id" v-if="!survey.archived">{{survey.name}}</router-link>
                    <a v-else>{{survey.name}}</a>
                </td>
                <td>
                    <router-link :to="'/survey/'+survey.id+'/responses'">{{survey.response_count}}</router-link>
                </td>
                <td>
                    <guard-component model="Surveys\Survey" check="update" :value="survey.id">
                        <router-link :to="'/survey/'+survey.id" class="ui green button" v-if="!survey.archived">Take Survey</router-link>
                        <button class="ui green button" v-else disabled>Take Survey</button>
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
                        <button class="ui button with tip" @click="toggleArchive(survey.id, survey.archived)"
                            :data-tooltip="survey.archived? 'Unarchiving a survey will make it available to all agian' : 'Archiving a survey will remove it from the list of surveys available to' +
                             ' other members. Only team managers can see and view responses.'">
                            <span v-if="!survey.archived">Archive</span>
                            <span v-if="survey.archived">Unarchive</span>
                        </button>
                        <button class="ui red button" @click="confirmDelete(survey.id)">Delete</button>
                        <a :href="'/survey/'+survey.id+'/excel'" class="ui button" data-tooltip="Export the survey to an Excel sheet">Export</a>
                        <div slot="no-access">
                            <router-link :to="'/survey/'+survey.id" class="ui green button" :disabled="survey.archived">Take Survey</router-link>
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
        <div class="ui basic modal" id="confirm-delete">
            <div class="ui icon header">
                <i class="warning circle icon"></i>
                Confirm delete
            </div>
            <div class="centered content">
                <p>Are you sure you want to delete this survey? One it is deleted, it cannot be recovered!</p>
            </div>
            <div class="actions">
                <div class="ui red basic cancel inverted button">
                    <i class="remove icon"></i>
                    No
                </div>
                <div class="ui green ok inverted button">
                    <i class="checkmark icon"></i>
                    Yes
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import toastr from 'toastr';

    export default {
        data() {
            return {
                surveys: null,
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
            },
            confirmDelete(id) {
                $("#confirm-delete").modal({
                    onApprove: () => {
                        axios.delete(route('survey.delete', {survey: id})).then(resp => {
                            this.surveys = _.filter(this.surveys, s => {
                                return s.id !== id
                            });
                            toastr["success"]("Survey deleted!", "Success!");
                        });
                    }
                }).modal('show');
            },
            toggleArchive(id, archived){
                axios.patch(route('survey.setArchived', {survey: id}), {
                    archived: !archived
                }).then(resp => {
                    this.surveys.forEach(survey => {
                        if(survey.id === id){
                            survey.archived = !archived;
                        }
                    })
                })
            }
        }
    }
</scrIpt>