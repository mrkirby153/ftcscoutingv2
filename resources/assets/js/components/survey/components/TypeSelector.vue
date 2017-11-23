<style>

</style>

<template>
    <div class="ui fluid field">
        <label>Type</label>
        <select class="ui fluid dropdown" @change="setType($event.target.value)" :value="question.type">
            <option value="TEXT">Text</option>
            <option value="LONG_TEXT">Long Text</option>
            <option value="NUMBER">Numeric</option>
            <option value="MULTIPLE_CHOICE">Checkbox</option>
            <option value="RADIO">Radio</option>
        </select>
    </div>
</template>

<script>
    import {DISPATCH_SURVEY_QUESTION_TYPE} from "../../../vuex/mutationTypes";

    export default {
        props: {
            id: {
                type: String,
                required: true
            }
        },

        computed: {
            question() {
                if (this.$store.state.survey.questions === undefined)
                    return undefined;
                let q = this.$store.state.survey.questions.filter(it => it.id === this.id);
                if (q.length < 1)
                    return undefined;
                return q[0];
            }
        },

        mounted(){
            $('.ui.dropdown').dropdown();
        },

        methods: {
            setType(type) {
                this.$store.dispatch(DISPATCH_SURVEY_QUESTION_TYPE, {id: this.id, type: type})
            }
        }
    }
</scrIpt>