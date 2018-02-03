<style>

</style>

<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
              <span v-if="editing">Edit </span>  {{survey.name}}
            </div>
            <div class="ui attached segment">
                <div class="ui form" :class="{'loading': loading}">
                    <div v-if="!editing">
                        <div class="field" :class="{'error': hasError('team_number')}">
                            <label>Team Number</label>
                            <input type="number" @keyup.stop="commit('team_number', $event.target.value)"
                                   :value="response.team_number"/>
                            <div class="form-error" v-if="hasError('team_number')">
                                {{ getError('team_number') }}
                            </div>
                        </div>
                        <div class="field" :class="{'error': hasError('match_number')}">
                            <label>Match Number</label>
                            <input type="number" @keyup.stop="commit('match_number', $event.target.value)"
                                   :value="response.match_number"/>
                            <div class="form-error" v-if="hasError('match_number')">
                                {{ getError('match_number') }}
                            </div>
                        </div>
                        <hr/>
                    </div>
                    <survey-questions :editable="editing"/>
                    <div style="margin-top: 10px" v-if="!editing">
                        <button class="ui fluid button" :class="{'loading': loading}" @click="submit()" :disabled="loading">Submit</button>
                    </div>
                    <div style="margin-top: 10px" v-if="editing">
                        <button class="ui button" @click="addNewQuestion" :class="{'loading': loading, 'disabled': editingQuestion != undefined}" :disabled="loading">Add Question</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        ADD_NEW_QUESTION,
        CLEAR_RESPONSE_DATA,
        COMMIT_SURVEY_DATA,
        GET_SURVEY,
        SET_RESPONSE_DATA
    } from "../../vuex/mutationTypes";

    import {mapState} from 'vuex';

    export default {
        data() {
            return {}
        },

        computed: {
            editing() {
                return this.$route.name === 'survey.edit';
            },
            ...mapState(['errors', 'survey', 'response', 'loading', 'editingQuestion'])
        },

        mounted() {
            this.retrieveData();
        },

        methods: {
            retrieveData() {
                this.$store.dispatch(GET_SURVEY, this.$route.params.id);
            },
            commit(type, data) {
                this.$store.commit(SET_RESPONSE_DATA, {question: type, response: data});
            },
            clearResponseData() {
                this.$store.commit(CLEAR_RESPONSE_DATA, {});
            },
            submit() {
                this.$store.dispatch(COMMIT_SURVEY_DATA);
            },
            addNewQuestion(){
                this.$store.dispatch(ADD_NEW_QUESTION);
            },
            hasError(field){
                return _.indexOf(_.keys(this.errors), field) > -1;
            },
            getError(error){
                return this.errors[error][0];
            }
        }
    }
</scrIpt>