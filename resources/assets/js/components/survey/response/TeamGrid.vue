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
                    <th v-for="q in transformed">{{q.name}}</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="d in questionData">
                    <td v-for="d1 in d">{{decode(d1)}}</td>
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
                responses: undefined
            }
        },

        computed: {
            transformed() {
                try {
                    let data = {};
                    this.responses.forEach(response => {
                        // Sort the data here because we can't easily do it in the backend
                        response.data.sort((o1, o2) => {
                            return o1.question.order - o2.question.order;
                        });
                        response.data.forEach(rData => {
                            if (data[rData.question.order] === undefined) {
                                data[rData.question.order] = {
                                    name: rData.question.question_name,
                                    data: []
                                };
                            }
                            data[rData.question.order].data.push(rData.response_data);
                        })
                    });

                    return data;
                } catch (e) {
                    console.log(e);
                    throw e;
                }
            },
            questionData() {
                let data = [];
                let index = 0;
                this.responses.forEach(response => {
                    let r = [];
                    response.data.forEach(rData => {
                        r.push(rData.response_data);
                    });
                    data[index++] = r;
                });
                return data;
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
                })
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