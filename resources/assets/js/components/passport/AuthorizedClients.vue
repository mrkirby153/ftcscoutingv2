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
            Authorized Applications
        </div>
        <div class="ui attached segment">
            <div v-if="tokens.length > 0">
                <!-- Authorized Tokens -->
                <table class="ui celled table">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Scopes</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                    <tr v-for="token in tokens">
                        <!-- Client Name -->
                        <td style="vertical-align: middle;">
                            {{ token.client.name }}
                        </td>
                        <!-- Scopes -->
                        <td style="vertical-align: middle">
                            {{ token.scopes.join(', ')}}
                        </td>

                        <!-- Revoke Button -->
                        <td style="vertical-align: middle;">
                            <button class="ui button red" @click="revoke(token)">Revoke</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div v-else>
                <p>You have not authorized any applications</p>
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
                tokens: []
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
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getTokens();
            },

            /**
             * Get all of the authorized tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/tokens')
                    .then(response => {
                        this.tokens = response.data;
                    });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/tokens/' + token.id)
                    .then(response => {
                        this.getTokens();
                    });
            }
        }
    }
</script>
