<style>

</style>

<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                {{survey.name}}
            </div>
            <div class="ui attached segment">
                <div class="ui form" :class="{'loading': loading}">
                    <div class="field">
                        <label>Team Number</label>
                        <input type="number" @keyup.stop="commit('team_number', $event.target.value)" :value="response.team_number"/>
                    </div>
                    <div class="field">
                        <label>Match Number</label>
                        <input type="number" @keyup.stop="commit('match_number', $event.target.value)" :value="response.match_number"/>
                    </div>
                    <hr/>
                    <survey-questions :editable="false"/>
                    <div style="margin-top: 10px">
                        <button class="ui fluid button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {CLEAR_RESPONSE_DATA, GET_SURVEY, SET_RESPONSE_DATA} from "../../vuex/mutationTypes";

    export default {
        data() {
            return {
            }
        },

        computed: {
            survey() {
                return this.$store.state.survey;
            },
            response() {
                return this.$store.state.response;
            },
            loading(){
                return this.$store.state.loading;
            }
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
            }
        }
    }
</scrIpt>