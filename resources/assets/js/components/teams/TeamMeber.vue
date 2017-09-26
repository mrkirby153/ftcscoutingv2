<template>
    <div>
        <h2>Team Members</h2>
        <button class="ui orange button" @click="showAddMember"><i class="plus icon"></i>Add a member</button>
        <table class="ui celled table" v-if="team">
            <thead>
            <tr>
                <th>Member Name</th>
                <th>Join Date</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="member in team.members">
                <td><span v-if="member.user">{{member.user.name}}</span><span v-else="">{{member.user_email}}</span>
                    <div class="ui small label" v-if="member.pending">Pending</div>
                    <div class="ui small blue label" v-if="member.user && team.owner_id === member.user.id">Owner</div>
                </td>
                <td>{{member.created_at}}</td>
                <td>
                    <div class="buttons" v-if="!(member.user && team.owner_id === member.user.id)">
                        <button class="ui button">
                            <span v-if="member.pending">Cancel Invite</span><span v-else="">Remove From Team</span>
                        </button>
                        <button class="ui button" v-if="!member.pending">Make Team Manager</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="ui modal" id="add-member-modal">
            <div class="header">
                Invite A Member
            </div>
            <div class="content">
                <p>
                    Enter the email of the user you wish to invite to the team. If the user does not have an account
                    they will be prompted to create one
                </p>
                <form-wrapper :form="forms.inviteUser" @submit="inviteUser">
                    <div slot="success">
                        User Invited!
                    </div>
                    <form-field name="email" required="true" :form="forms.inviteUser">
                        <label>User E-Mail</label>
                        <input type="text" v-model="forms.inviteUser.email" name="email"/>
                    </form-field>
                </form-wrapper>
            </div>
            <div class="actions">
                <div v-if="!forms.inviteUser.successful">
                    <div class="ui red deny button">Cancel</div>
                    <div class="ui blue button" @click="inviteUser()">Invite</div>
                </div>
                <div v-else="">
                    <div class="ui green deny button">Close</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                team: {},
                forms: {
                    inviteUser: new Form('PUT', route('team.member.create', {team: this.$route.params.id}), {
                        email: ''
                    })
                }
            }
        },

        mounted() {
            this.getTeamInfo();
        },

        watch: {
            '$route'(to, from) {
                if (to.params.id !== from.params.id)
                    this.getTeamInfo();
            }
        },

        methods: {
            getTeamInfo() {
                axios.get(route('team.get', {team: this.$route.params.id})).then(resp => {
                    this.team = resp.data;
                })
            },
            showAddMember() {
                this.forms.inviteUser.reset();
                $("#add-member-modal").modal('show');
            },
            inviteUser(){
                this.forms.inviteUser.save().then(resp => {
                    this.getTeamInfo();
                });
            }
        }
    }
</script>