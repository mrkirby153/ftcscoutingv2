<style>

</style>

<template>
    <div>
        <div v-for="question in questions" :key="question.id">
            <question-multiplechoice :checkbox="question.type=='MULTIPLE_CHOICE'" :id="question.id"
                                     :editable="editable"
                                     v-if="question.type=='MULTIPLE_CHOICE' || question.type=='RADIO'"></question-multiplechoice>
            <question-text :id="question.id" :editable="editable"
                           v-if="question.type==='TEXT' || question.type==='LONG_TEXT'"></question-text>
        </div>
    </div>
</template>

<script>
    import {GET_SURVEY} from "../../vuex/mutationTypes";

    export default {

        computed: {
            survey() {
                return this.$store.state.survey;
            },
            questions() {
                return this.survey.questions;
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