<template>
    <div>
        <div class="row">
            <div class="column">
                <div class="ui top attached header">
                    <h2>Overview for {{survey.name}}</h2>
                </div>
                <div class="ui attached segment scroll-x-overflow">
                    <router-link :to="{name: 'survey.responses', params:{id: $route.params.id}}" class="ui button">
                        Back
                    </router-link>
                    <table class="ui table" v-if="responses">
                        <thead>
                        <tr>
                            <th>Team Number</th>
                            <th>Match Number</th>
                            <th v-for="question in questions">{{question.question_name}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="response in responses">
                            <td>{{response.team_number}}</td>
                            <td>{{response.match_number}}</td>
                            <td v-for="question in questions">
                                {{getResponseData(response, question.id)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "survey-overview",
        data() {
            return {
                survey: {}
            }
        },
        computed: {
            questions() {
                return this.survey.questions;
            },
            responses() {
                this.survey.responses.sort((o1, o2) => {
                    if (o1.team_number === o2.team_number) {
                        return o1.match_number - o2.match_number;
                    } else {
                        return o1.team_number - o2.team_number;
                    }
                });
                return this.survey.responses;
            }
        },
        mounted() {
            this.getSurveyData();
        },
        methods: {
            getSurveyData() {
                axios.get(route('survey.get', {
                    survey: this.$route.params.id
                }) + "?load=responses.data").then(resp => {
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
</script>

<style scoped>

</style>