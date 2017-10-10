<style>

</style>

<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                {{survey.name}}
            </div>
            <div class="ui attached segment">
                <div v-for="question in questions" :key="question.id">
                    <question-multiplechoice :checkbox="question.type=='MULTIPLE_CHOICE'" :id="question.id" :data="surveyData"
                                             :editable="true" v-if="question.type=='MULTIPLE_CHOICE' || question.type=='RADIO'"></question-multiplechoice>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {GET_SURVEY} from "../../vuex/mutationTypes";

    export default {
        data() {
            return {
                surveyData: {},
            }
        },

        computed: {
            survey() {
                return this.$store.state.survey;
            },
            questions() {
                return this.survey.questions;
            }
        },

        mounted() {
            this.retrieveData();
        },

        methods: {
            retrieveData() {
                this.$store.dispatch(GET_SURVEY, this.$route.params.id);
            }
        }
    }
</scrIpt>