<style>

</style>

<template>
    <div class="row">
        <div class="column" v-if="question">
            <div class="ui top attached header">
                PIN
            </div>
            <div class="ui attached segment">
                <div class="ui grid">
                    <div class="row" v-for="o in options" v-if="question.type != 'NUMBER'">
                        <div class="three wide column">
                            {{o.name}}
                        </div>
                        <div class="three wide column">
                            <div class="ui input">
                                <input type="number" v-model="pin[o.name]"/>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="question.type == 'NUMBER'">
                        <div class="seven wide column">
                            <b>{{question.question_name}}</b>
                        </div>
                        <div class="three wide column">
                            <div class="ui input">
                                <input type="number" v-model="pin.number"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="six wide column">
                            <button class="ui button" @click.prevent="save()">Save and return</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {store} from "../../vuex/vuex";
    import {GET_SURVEY, RETRIEVE_QUESTION_DATA, SET_EDITING_QUESTION} from "../../vuex/mutationTypes";
    import router from '../../router/routes';
    import toastr from 'toastr'

    export default {

        data() {
            return {
                pin: {},
                pinId: 0,
            }
        },

        mounted() {
            this.onMount();
        },

        computed: {
            options() {
                return this.$store.state.editingOptions;
            },
            question() {
                if (this.$store.state.survey.questions === undefined)
                    return undefined;
                let q = this.$store.state.survey.questions.filter(it => it.id === this.$route.params.question);
                if (q.length < 1)
                    return undefined;
                return q[0];
            },
        },

        methods: {
            onMount() {
                // Get data only if its null
                if (this.$store.state.editingQuestion === undefined) {
                    this.$store.dispatch(RETRIEVE_QUESTION_DATA, this.$route.params.question);
                }
                if(this.$store.state.survey.questions === undefined){
                    this.$store.dispatch(GET_SURVEY, this.$route.params.id)
                }
                axios.get(route('pin.get.by-question', {question: this.$route.params.question})).then(resp => {
                    this.pin = resp.data.data;
                    this.pinId = resp.data.id;
                });
            },
            save() {
                axios.patch(route('pin.update', {id: this.pinId}), {
                    data: this.pin
                }).then(resp => {
                    router.push({
                        name: 'survey.edit',
                        params: {
                            id: this.$route.params.id
                        }
                    });
                    toastr["success"]("Things have worked", "Success");
                }).catch(e => {
                    toastr["error"]("An unknown error occurred. Please try again", "Error");
                    throw e;
                });
            }
        }
    }
</scrIpt>