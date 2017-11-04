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
                <div class="field">
                    <div v-for="option in question.extra_data.items">
                        <div v-bind:class="{ 'ui checkbox': checkbox, 'ui radio checkbox': !checkbox }">
                            <input type="checkbox" :value="option.name" :name="id+'-checkbox'"
                                   v-if="checkbox" :disabled="editable" @change="change()"
                                   :checked="shouldCheck(option.name)">
                            <input type="radio" :value="option.name" :name="id+'-checkbox'"
                                   v-if="!checkbox" :disabled="editable" @change="change()"
                                   :checked="shouldCheck(option.name)">
                            <label>{{option.name}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="editing">
                <div class="row">
                    <div class="ui fluid input">
                        <input type="text" v-model="editData.name" placeholder="Question Name"/>
                    </div>
                </div>
                <h5>Options</h5>
                <div class="row" v-for="(option, index) in editData.options">
                    <div class="ui fluid icon input m-10-top">
                        <i class="circular x link icon" @click="removeOption(index)"></i>
                        <input type="text" v-model="editData.options[index].name"/>
                    </div>
                </div>
                <div class="row m-10-top">
                    <type-selector :id="id"/>
                </div>
                <button class="ui icon button m-10-top" @click="addOption()"><i class="plus icon"></i> Add Option
                </button>
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
                data: null,
                editData: {
                    name: "",
                    options: []
                }
            }
        },
        props: {
            checkbox: {
                required: true
            },
            editable: {
                default: false
            },
            id: {
                required: true
            }
        },

        mounted() {
            if (this.checkbox)
                this.data = [];
            else
                this.data = {};
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
                if (!this.editable || this.editing || this.$store.state.editingQuestion !== null)
                    return;
                this.$store.commit(SET_EDITING_QUESTION, this.id);
                this.editData.name = this.question.question_name;
                this.editData.options = this.question.extra_data.items;
            },
            addOption() {
                this.editData.options.push({
                    name: ""
                })
            },

            removeOption(index) {
                this.editData.options.splice(index, 1);
            },

            save() {
                this.$store.dispatch(SET_QUESTION_DATA, {
                    id: this.id,
                    title: this.editData.name,
                    data: {items: this.editData.options}
                });
            },
            deleteQuestion() {
                this.$store.dispatch(DELETE_QUESTION, this.id);
            },
            change(data) {
                let d = [];
                document.getElementsByName(this.id + '-checkbox').forEach(obj => {
                    if (obj.checked) {
                        d.push(obj.value);
                    }
                });
                this.$store.commit(SET_RESPONSE_DATA, {question: this.id, response: d});
            },
            shouldCheck(name) {
                let obj = this.response[this.id];
                if (obj === undefined)
                    return false;
                return obj.includes(name);
            }
        }

    }
</scrIpt>