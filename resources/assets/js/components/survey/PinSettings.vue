<style>

</style>

<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                PIN
            </div>
            <div class="ui attached segment">
                <!--<form class="ui form" @submit.prevent="save()">
                    <div v-for="o in options">
                        <div class="ui inline field" style="margin-top: 10px">
                            <label>{{o.name}}</label>
                            <input type="number" v-model="pin[o.name]"/>
                        </div>
                    </div>
                    <input type="submit" class="ui button" value="Save and Return" style="margin-top: 10px"/>
                </form>-->
                <div class="ui grid">
                    <div class="row" v-for="o in options">
                        <div class="three wide column">
                            {{o.name}}
                        </div>
                        <div class="three wide column">
                            <div class="ui input">
                                <input type="number" v-model="pin[o.name]"/>
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
    import {RETRIEVE_QUESTION_DATA, SET_EDITING_QUESTION} from "../../vuex/mutationTypes";
    import router from '../../router/routes';

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
            }
        },

        methods: {
            onMount() {
                // Get data only if its null
                if (this.$store.state.editingQuestion === undefined) {
                    this.$store.dispatch(RETRIEVE_QUESTION_DATA, this.$route.params.question);
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
                });
            }
        }
    }
</scrIpt>