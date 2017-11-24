<template>
    <div class="sixteen wide column">
        <div class="row">
            <div class="column">
                <div class="ui top attached header" v-if="team">
                    Team {{team.team_number}}: {{ team.name }}
                </div>
                <guard-component model="Team" check="view" :value="$route.params.id">
                    <div class="ui attached segment">
                        <div class="ui buttons" v-if="member && member.pending_accept">
                            <button class="ui button" @click="acceptInvite">Join Team</button>
                            <div class="or"></div>
                            <button class="ui button_popup button" @click="declineInvite">
                                Decline Invite
                            </button>
                        </div>
                        <div v-if="member && !member.pending_accept">
                            <div class="ui secondary pointing labeled icon menu">
                                <router-link :to="{name: 'team.overview', id: team.team_id}" activeClass="" class="item"><i
                                        class="browser icon"></i>Team Overview
                                </router-link>
                                <router-link :to="{name: 'team.members', id: team.team_id}" class="item"><i
                                        class="user icon"></i>Team Members
                                </router-link>
                                <router-link :to="{name: 'team.surveys', id: team.team_id}" class="item"><i
                                        class="tasks icon"></i>Surveys
                                </router-link>
                            </div>
                            <router-view></router-view>
                        </div>
                    </div>
                    <div class="ui attached segment" slot="no-access">
                        You are not a member of this team. If you would like to request join, click <a>Here</a>
                    </div>
                </guard-component>
                <div class="ui segment" v-if="loaded && !team">
                    That team does not exist!
                </div>
            </div>
        </div>
        <div class="ui modal" id="modal-decline-invite" tabindex="-1" role="dialog">
            <div class="header">
                <h4>
                    Decline invite?
                </h4>
            </div>

            <div class="content">
                <p>
                    Are you sure you want to decline this invite? You won't be able to respond to surveys unless you are
                    invited again!
                </p>
            </div>

            <!-- Modal Actions -->
            <div class="actions">
                <div class="ui red button" @click="denyInvite">Confirm</div>
                <div class="ui blue deny button">Cancel</div>
            </div>
        </div>
    </div>
</template>

<script>
    import {ACCEPT_MEMBER_INVITE, REMOVE_TEAM_MEMBER} from "../../vuex/mutationTypes";

    export default {
        computed: {
            member() {
                let t = this.$store.state.teams.filter(t => t.team_id === this.$route.params.id)[0];
                if (typeof t === 'undefined')
                    return null;
                else return t;
            }
        },

        data() {
            return {
                team: {
                    name: "Loading...",
                    team_number: "Loading..."
                },
                loaded: false,
            }
        },

        mounted() {
            this.getTeam();
        },

        watch: {
            '$route'(to, from) {
                if (to.params.id !== from.params.id)
                    this.getTeam();
            }
        },

        methods: {
            declineInvite() {
                $('#modal-decline-invite').modal('show');
            },
            denyInvite() {
                $("#modal-decline-invite").modal('hide');
                axios.delete(route('team.member.remove', {member: this.member.member_id})).then(resp => {
                    this.$store.commit(REMOVE_TEAM_MEMBER, this.member.team_id);
                })
            },
            acceptInvite() {
                this.$store.dispatch(ACCEPT_MEMBER_INVITE, this.member.member_id);
            },
            getTeam() {
                this.loaded = false;
                axios.get(route('team.get', {team: this.$route.params.id})).then(resp => {
                    this.team = resp.data;
                    this.loaded = true;
                }).catch(e => {
                    this.loaded = true;
                    this.team = null;
                })
            }
        }
    }
</script>