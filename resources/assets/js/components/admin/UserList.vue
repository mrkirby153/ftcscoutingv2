<style>

</style>

<template>
    <div>
        <div class="ui top attached header">
            User List
        </div>
        <div class="ui attached segment">
            <table class="ui table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Created At</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="user in users">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.admin}}</td>
                    <td>{{user.created_at}}</td>
                    <td><a :href="'/impersonate/take/'+user.id">Impersonate</a></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                users: []
            }
        },

        mounted(){
           this.onMount();
        },

        methods:{
            onMount(){
                axios.get(route('admin.userList')).then(resp=>{
                    this.users = resp.data;
                })
            }
        }
    }
</scrIpt>