<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                FTC Scouting: Create Team
            </div>
            <div class="ui attached segment">
                <form-wrapper :form="forms.createTeam" @submit="save">
                    <p slot="success">Team created!</p>
                    <form-field name="name" required="true" :form="forms.createTeam">
                            <label>Team Name</label>
                            <input type="text" name="name" v-model="forms.createTeam.name"/>
                    </form-field>
                    <form-field name="team_number" required="true" :form="forms.createTeam">
                        <label>Team Number</label>
                        <input type="number" name="team_number" v-model="forms.createTeam.team_number"/>
                    </form-field>
                    <button class="ui green button">Create</button>
                </form-wrapper>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                forms: {
                    createTeam: new Form('put', route('team.create'), {
                        name: "",
                        team_number: 0
                    })
                }
            }
        },
        methods: {
            save(){
                this.forms.createTeam.save().then(resp =>{
                    this.$router.push('/team/'+resp.data.id);
                });
            }
        }
    }
</script>