<style>

</style>

<template>
    <div>
        <div v-if="questions && questions.length == 0 && !editable">
            There are no questions for this survey. Ask the survey owner to add some.
        </div>
        <div v-for="question in questions">
            <question-multiplechoice :checkbox="question.type=='MULTIPLE_CHOICE'" :id="question.id"
                                     :editable="editable"
                                     v-if="question.type=='MULTIPLE_CHOICE' || question.type=='RADIO'"></question-multiplechoice>
            <question-text :id="question.id" :editable="editable"
                           v-if="question.type==='TEXT' || question.type==='LONG_TEXT' || question.type ==='NUMBER'"></question-text>
        </div>
    </div>
</template>

<script>
    import {GET_SURVEY, SET_QUESTION_ORDER} from "../../vuex/mutationTypes";

    export default {

        computed: {
            survey() {
                return this.$store.state.survey;
            },
            questions() {
                return this.$store.getters.questions;
            }
        },

        props: {
            editable: {
                type: Boolean,
                required: true
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