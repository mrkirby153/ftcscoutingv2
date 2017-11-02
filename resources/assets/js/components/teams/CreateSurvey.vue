<style>

</style>

<template>
    <div>
        <h1>Create Survey</h1>
        <form-wrapper :form="forms.create" @submit="save">
            <div class="ui form">
                <form-field :form="forms.create" :required="true" name="name">
                    <div class="field">
                        <label>Survey Name</label>
                        <input type="text" name="name" v-model="forms.create.name"/>
                    </div>
                </form-field>
                <form-field :form="forms.create" :required="false" name="cloneFrom">
                    <label>Clone From (Optional)</label>
                    <select class="ui dropdown" v-model="forms.create.cloneFrom">
                        <option :value="-1">None</option>
                        <option v-for="survey in validSurveys" :value="survey.id">{{survey.name}}</option>
                    </select>
                </form-field>
            </div>
            <input type="button" class="ui button" style="margin-top: 10px" value="Create" @click.prevent="save"/>
        </form-wrapper>
    </div>
</template>

<script>
    import router from "../../router/routes";

    export default {
        data() {
            return {
                forms: {
                    create: new Form('PUT', route('survey.create', {team: this.$route.params.id}).constructUrl(), {
                        name: '',
                        cloneFrom: -1
                    })
                },
                surveys: []
            }
        },
        computed: {
            validSurveys() {
                return this.surveys.filter(data => !data.archived);
            }
        },
        mounted() {
            this.onMount();
        },

        methods: {
            onMount() {
                this.getSurveys();
                $('.ui.dropdown').dropdown();
            },
            getSurveys() {
                axios.get(route('survey.list', {team: this.$route.params.id})).then(resp => {
                    this.surveys = resp.data;
                })
            },
            save() {
                this.forms.create.save().then(resp => {
                    console.log(resp.data);
                    router.push('/survey/' + resp.data.id);
                }).catch(e => {
                });
            }
        }
    }
</scrIpt>