<style scoped>
    .pin-data {
        float: right;
        font-size: 3em;
    }
    .m-10-top{
        margin-top: 10px;
    }
</style>

<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                Response
            </div>

            <div class="ui attached segment">
                <div class="pin-data">
                    PIN: 0
                </div>
                <div v-for="d in data.data">
                    <h2>{{d.question_name}}</h2>
                    {{decodeQuestionResponse(d)}}
                </div>

                <router-link :to="{name: 'survey.response.team', params: {id: $route.params.survey, team: data.team}}" class="ui icon button m-10-top">
                    <i class="left arrow icon"></i>
                </router-link>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                data: []
            }
        },

        mounted() {
            this.get();
        },

        methods: {
            get() {
                axios.get(route('response.get', {response: this.$route.params.id})).then(resp => {
                    this.data = resp.data;
                })
            },

            decodeQuestionResponse(question) {
                let data = JSON.parse(question.response_data);
                if (data instanceof Array) {
                    let d = "";
                    data.forEach(d1 => {
                        d += d1 + ", "
                    });
                    return d.substr(0, d.length - 2)
                } else {
                    return data;
                }
            }
        }
    }
</scrIpt>