<style>

</style>

<template>
    <div>
        <div class="ui top attached header">
            Overview For Team {{$route.params.team}}
        </div>
        <div class="ui attached segment scroll-x-overflow">
            <table class="ui table" v-if="responses">
                <thead>
                <tr>
                    <th>Match Number</th>
                    <th v-for="q in questions">{{q.question_name}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="response in responses">
                    <td>{{response.match_number}}</td>
                    <td v-for="question in questions">{{getResponseData(response, question.id)}}</td>
                </tr>
                </tbody>
            </table>
            <router-link :to="{name: 'survey.response.team', params: { id: $route.params.id, team: $route.params.team}}"
                         class="ui icon button">
                <i class="left arrow icon"></i>
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                responses: undefined,
                survey: undefined
            }
        },

        computed: {
            questions(){
                return this.survey.questions;
            }
        },

        mounted() {
            this.onMount();
        },

        methods: {
            onMount() {
                axios.get(route('response.team.summary', {
                    survey: this.$route.params.id,
                    team: this.$route.params.team
                })).then(resp => {
                    this.responses = resp.data;
                });
                axios.get(route('survey.get', {
                    survey: this.$route.params.id,
                })).then(resp => {
                    this.survey = resp.data;
                })
            },
            getResponseData(response, question) {
                for (let i = 0; i < response.data.length; i++) {
                    let data = response.data[i];
                    if (data.question_id == question) {
                        return this.decode(data.response_data);
                    }
                }
                return ""
            },
            decode(question) {
                if (question instanceof Array) {
                    let d = "";
                    question.forEach(d1 => {
                        d += d1 + ", "
                    });
                    return d.substr(0, d.length - 2)
                } else {
                    return question;
                }
            }
        }
    }
</scrIpt>