<style scoped>
    .m-10-top {
        margin-top: 10px;
    }
</style>

<template>
    <div>
        <div :class="{'ui segment' : editing}" v-if="question" @click="openEdit()">
            <div v-if="!editing">
                <h3>{{question.question_name}}</h3>
                <div class="ui fluid input" v-if="question.type == 'TEXT'">
                    <input type="text" @keyup.stop="change($event.target.value)" :value="response[id]"
                           :disabled="editable"/>
                </div>
                <div class="ui form field" v-if="question.type=='LONG_TEXT'">
                    <textarea rows="3" @keyup.stop="change($event.target.value)" :value="response[id]"
                              :disabled="editable"></textarea>
                </div>
            </div>
            <div v-if="editing">
                <div class="row">
                    <div class="ui fluid input">
                        <input type="text" v-model="editData.name" placeholder="Question Name"/>
                    </div>
                </div>
                <div class="row">
                    <div class="ui fluid input" v-if="question.type == 'TEXT'">
                        <input type="text" disabled/>
                    </div>
                    <div class="ui form field" v-if="question.type=='LONG_TEXT'">
                        <textarea rows="3" disabled></textarea>
                    </div>
                </div>
                <button class="ui icon button m-10-top" @click="save()"><i class="save icon"></i> Save</button>
                <button class="ui icon button m-10-top" @click="deleteQuestion()"><i class="x icon"></i> Delete</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {
        DELETE_QUESTION,
        SET_EDITING_QUESTION,
        SET_QUESTION_DATA,
        SET_RESPONSE_DATA
    } from "../../../vuex/mutationTypes";

    export default {
        data() {
            return {
                data: "",
                editData: {
                    name: ""
                }
            }
        },
        props: {
            editable: {
                default: false
            },
            id: {
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
            },
            options() {
                return this.question.extra_data.items;
            },
            editing() {
                return this.$store.state.editingQuestion === this.id;
            },
            response() {
                return this.$store.state.response;
            }
        },

        methods: {
            slugify(data) {
                return data.toLowerCase().replace(' ', '-');
            },
            openEdit() {
                if (!this.editable || this.editing)
                    return;
                this.$store.commit(SET_EDITING_QUESTION, this.id);
                this.editData.name = this.question.question_name;
            },

            save() {
                this.$store.dispatch(SET_QUESTION_DATA, {
                    id: this.id,
                    title: this.editData.name,
                    data: {}
                });
            },
            deleteQuestion() {
                this.$store.dispatch(DELETE_QUESTION, this.id);
            },
            change(data) {
                this.$store.commit(SET_RESPONSE_DATA, {question: this.id, response: data})
            }
        }

    }
</scrIpt>