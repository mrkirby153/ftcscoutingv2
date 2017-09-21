<template>
    <div class="row">
        <div class="column">
            <div class="ui top attached header">
                FTC Scouting: OAuth
            </div>
            <div class="ui attached segment">
                <div class="ui secondary menu">
                    <router-link :to="{'name':'oauth.authorized'}" class="item">Authorized Clients</router-link>
                    <router-link :to="{'name':'oauth.tokens'}" class="item">Tokens</router-link>
                    <router-link :to="{'name':'oauth.clients'}" class="item">Clients</router-link>
                </div>
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    export default {

    }
</script>