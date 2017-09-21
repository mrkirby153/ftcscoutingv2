<style scoped>
    .action-link {
        cursor: pointer;
    }

    .m-b-none {
        margin-bottom: 0;
    }
</style>

<template>
    <div>
        <div class="ui top attached header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span>
                            Personal Access Tokens
                        </span>

                <a class="action-link" @click="showCreateTokenForm">
                    Create New Token
                </a>
            </div>
        </div>
        <div class="ui attached segment">
            <!-- No Tokens Notice -->
            <p class="m-b-none" v-if="tokens.length === 0">
                You have not created any personal access tokens.
            </p>

            <!-- Personal Access Tokens -->
            <table class="ui celled table m-b-none" v-if="tokens.length > 0">
                <thead>
                <tr>
                    <th>Name</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="token in tokens">
                    <!-- Client Name -->
                    <td style="vertical-align: middle;">
                        {{ token.name }}
                    </td>

                    <!-- Delete Button -->
                    <td style="vertical-align: middle;">
                        <a class="action-link text-danger" @click="revoke(token)">
                            Delete
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Create Token Modal -->
        <div class="ui modal" id="modal-create-token" tabindex="-1" role="dialog">
            <div class="header">
                <h4>
                    Create Token
                </h4>
            </div>
            <div class="content">
                <div class="ui error message" v-if="form.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <ul>
                        <li v-for="error in form.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <form class="ui form" role="form" @submit.prevent="store">
                    <div class="field">
                        <label>Name</label>
                        <input class="input" id="create-token-name" type="text" name="name" v-model="form.name"/>
                    </div>
                    <div class="field" v-if="scopes.length > 0">
                        <label>Scopes</label>
                        <div v-for="scope in scopes">
                            <div class="ui checkbox">
                                <label>{{scope.id}}</label>
                                <input type="checkbox" @click="toggleScope(scope.id)"
                                       :checked="scopeIsAssigned(scope.id)"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="actions">
                <div class="ui red deny button">
                    Close
                </div>
                <button class="ui blue button" @click="store">Create</button>
            </div>
        </div>

        <!-- Access Token Modal -->
        <div class="ui modal" id="modal-access-token" tabindex="-1" role="dialog">
            <div class="header">

                <h4>
                    Personal Access Token
                </h4>
            </div>

            <div class="content">
                <p>
                    Here is your new personal access token. This is the only time it will be shown so don't lose it!
                    You may now use this token to make API requests.
                </p>

                <pre><code>{{ accessToken }}</code></pre>
            </div>

            <!-- Modal Actions -->
            <div class="actions">
                <button type="button" class="ui confirm button" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                accessToken: null,

                tokens: [],
                scopes: [],

                form: {
                    name: '',
                    scopes: [],
                    errors: []
                }
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getTokens();
                this.getScopes();

                $('#modal-create-token').on('shown.bs.modal', () => {
                    $('#create-token-name').focus();
                });
            },

            /**
             * Get all of the personal access tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/personal-access-tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Get all of the available scopes.
             */
            getScopes() {
                axios.get('/oauth/scopes')
                    .then(response => {
                        this.scopes = response.data;
                    });
            },

            /**
             * Show the form for creating new tokens.
             */
            showCreateTokenForm() {
                $('#modal-create-token').modal('show');
            },

            /**
             * Create a new personal access token.
             */
            store() {
                this.accessToken = null;

                this.form.errors = [];

                axios.post('/oauth/personal-access-tokens', this.form)
                    .then(response => {
                        this.form.name = '';
                        this.form.scopes = [];
                        this.form.errors = [];

                        this.tokens.push(response.data.token);

                        this.showAccessToken(response.data.accessToken);
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            this.form.errors = _.flatten(_.toArray(error.response.data));
                        } else {
                            this.form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Toggle the given scope in the list of assigned scopes.
             */
            toggleScope(scope) {
                if (this.scopeIsAssigned(scope)) {
                    this.form.scopes = _.reject(this.form.scopes, s => s == scope);
                } else {
                    this.form.scopes.push(scope);
                }
            },

            /**
             * Determine if the given scope has been assigned to the token.
             */
            scopeIsAssigned(scope) {
                return _.indexOf(this.form.scopes, scope) >= 0;
            },

            /**
             * Show the given access token to the user.
             */
            showAccessToken(accessToken) {
                $('#modal-create-token').modal('hide');

                this.accessToken = accessToken;

                $('#modal-access-token').modal('show');
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/personal-access-tokens/' + token.id)
                    .then(response => {
                        this.getTokens();
                    });
            }
        }
    }
</script>
