<style>

</style>

<template>
    <div>
        <div class="row">
            <div class="column">
                <div class="ui top attached header">
                    Responses for Team {{$route.params.team}}
                </div>
                <div class="ui attached segment">
                    <table class="ui table">
                        <thead>
                        <tr>
                            <th>Match Number</th>
                            <th>Submitted By</th>
                            <th>Submitted On</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="response in responses">
                            <td>{{response.match_number}}</td>
                            <td>{{response.submitter.name}}</td>
                            <td>{{response.created_at}}</td>
                            <td>
                                <guard-component model="Surveys\Response" check="delete" :value="response.id">
                                    <router-link
                                            :to="{name: 'survey.response', params: {id: response.id, survey: $route.params.id}}"
                                            class="ui icon button"><i class="right arrow icon"></i></router-link>
                                    <button class="ui red icon button" @click="del(response.id)">
                                        <i class="x icon"></i>
                                        <span v-if="response.id === deleting">Confirm?</span>
                                    </button>
                                    <router-link
                                            :to="{name: 'survey.response', params: {id: response.id, survey: $route.params.id}}"
                                            class="ui icon button" slot="no-access"><i class="right arrow icon"></i></router-link>
                                </guard-component>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <router-link :to="{name: 'survey.responses', params: {id: $route.params.id}}"
                                 class="ui icon button"><i class="left arrow icon"></i></router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                responses: [],
                deleting: null
            }
        },

        mounted() {
            this.get();
        },

        methods: {
            get() {
                axios.get(route('response.team.overview', {
                    team: this.$route.params.team,
                    survey: this.$route.params.id
                })).then(resp => {
                    this.responses = resp.data;
                })
            },
            del(id) {
                if (this.deleting === null)
                    this.deleting = id;
                else
                    this.doDelete();
            },
            doDelete() {
                axios.delete(route('response.delete', {response: this.deleting})).then(resp => {
                    this.deleting = null;
                    this.get();
                })
            }
        }
    }
</scrIpt>