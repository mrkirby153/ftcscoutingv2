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
                OAuth Clients
            </span>
                <a class="action-link" @click="showCreateClientForm">Create New Client</a>
            </div>
        </div>
        <div class="ui attached segment">
            <!-- Current Clients -->
            <p class="m-b-none" v-if="clients.length === 0">
                You have not created any OAuth clients.
            </p>

            <table class="ui celled table m-b-none" v-if="clients.length > 0">
                <thead>
                <tr>
                    <th>Client ID</th>
                    <th>Name</th>
                    <th>Secret</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="client in clients">
                    <!-- ID -->
                    <td style="vertical-align: middle;">
                        {{ client.id }}
                    </td>

                    <!-- Name -->
                    <td style="vertical-align: middle;">
                        {{ client.name }}
                    </td>

                    <!-- Secret -->
                    <td style="vertical-align: middle;">
                        <code>{{ client.secret }}</code>
                    </td>

                    <!-- Edit Button -->
                    <td style="vertical-align: middle;">
                        <a class="action-link ui button" @click="edit(client)">
                            Edit
                        </a>
                    </td>

                    <!-- Delete Button -->
                    <td style="vertical-align: middle;">
                        <a class="action-link ui button red" @click="destroy(client)">
                            Delete
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="ui modal" id="modal-create-client" tabindex="-1" role="dialog">
            <div class="header">
                <h4>Create Client</h4>
            </div>
            <div class="content">
                <!-- Form Errors -->
                <div class="ui error message" v-if="createForm.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <ul>
                        <li v-for="error in createForm.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <!-- Create Client Form -->
                <form class="ui form">
                    <div class="field">
                        <label>Name</label>
                        <input id="create-client-name" type="text" @keyup.enter="store" v-model="createForm.name"/>
                        <p>Something your users will recognize and trust</p>
                    </div>
                    <!-- Redirect URL -->
                    <div class="field">
                        <label>Redirect URL</label>
                        <input type="text" class="form-control" name="redirect" @keyup.enter="store"
                               v-model="createForm.redirect"/>
                        <p>Your application's callback URL</p>
                    </div>
                </form>
            </div>
            <div class="actions">
                <div class="ui red deny button">Close</div>
                <div class="ui blue button" @click="store">Create</div>
            </div>
        </div>

        <div class="ui modal" id="modal-edit-client" tabindex="-1" role="dialog">
            <div class="header">
                Edit Client
            </div>
            <div class="content">
                <div class="ui error message" v-if="editForm.errors.length > 0">
                    <p><strong>Whoops!</strong> Something went wrong!</p>
                    <ul>
                        <li v-for="error in editForm.errors">
                            {{ error }}
                        </li>
                    </ul>
                </div>

                <form class="ui form" role="form">
                    <!-- Name -->
                    <div class="field">
                        <label>Name</label>
                        <input type="text" id="edit-client-name" v-model="editForm.name" @keyup.enter="update"/>
                        <p>Something your users will recognize and trust</p>
                    </div>
                    <!-- Redirect URL -->
                    <div class="field">
                        <label>Redirect URL</label>
                        <input type="text" v-model="editForm.redirect" @keyup.enter="update"/>
                        <p>Your application's authorization callback URL</p>
                    </div>
                </form>
            </div>
            <div class="actions">
                <div class="ui red deny button">Close</div>
                <div class="ui blue button" @click="update">Update</div>
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
                clients: [],

                createForm: {
                    errors: [],
                    name: '',
                    redirect: ''
                },

                editForm: {
                    errors: [],
                    name: '',
                    redirect: ''
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
                this.getClients();

                $('#modal-create-client').on('shown.bs.modal', () => {
                    $('#create-client-name').focus();
                });

                $('#modal-edit-client').on('shown.bs.modal', () => {
                    $('#edit-client-name').focus();
                });
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                    .then(response => {
                        this.clients = response.data;
                    });
            },

            /**
             * Show the form for creating new clients.
             */
            showCreateClientForm() {
                $('#modal-create-client').modal('show');
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/clients',
                    this.createForm, '#modal-create-client'
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editForm.id = client.id;
                this.editForm.name = client.name;
                this.editForm.redirect = client.redirect;

                $('#modal-edit-client').modal('show');
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/oauth/clients/' + this.editForm.id,
                    this.editForm, '#modal-edit-client'
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];

                        $(modal).modal('hide');
                    })
                    .catch(error => {
                        if (typeof error.response.data === 'object') {
                            form.errors = _.flatten(_.toArray(error.response.data.errors));
                        } else {
                            form.errors = ['Something went wrong. Please try again.'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client.id)
                    .then(response => {
                        this.getClients();
                    });
            }
        }
    }
</script>
