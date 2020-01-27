<template>
    <div>
        <h1>Users</h1>
        <client-only placeholder="Loading...">
            <el-table
                :data="users"
                border
                fit
                highlight-current-row
                style="margin-top: 50px; width: 50%;"
                v-loading="!loaded"
            >
                <el-table-column label="ID" prop="id" sortable="custom" align="center" width="80">
                    <template slot-scope="{row}">
                    <span>{{ row.id }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="Name" min-width="150px">
                    <template slot-scope="{row}">
                    <span class="link-type">{{ row.name }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="Actions" align="center" class-name="small-padding fixed-width">
                    <template slot-scope="{row}">
                    <el-button v-if="!row.approved" @click="approveUser(row)" type="primary">
                        Approve
                    </el-button>
                    <el-button v-if="row.approved" @click="disapproveUser(row)"  type="danger">
                        Disapprove
                    </el-button>
                    </template>
                </el-table-column>
            </el-table>
        </client-only>
        <br><br>
        <div>
            <div style="margin-top: 50px; width: 30%">
                <form autocomplete="off">
                <el-input placeholder="Name" v-model="newName" autocomplete="off"></el-input><br><br>
                <el-input placeholder="Email" v-model="newEmail" autocomplete="off"></el-input><br><br>
                <el-input placeholder="Password" v-model="newPassword" type="password" autocomplete="off"></el-input><br><br>
                </form>
            </div>
            <el-button @click="registerUser" type="primary">
                Register New User
            </el-button>
        </div>
    </div>
</template>

<script>
export default {
    middleware: 'authenticated',
    layout: 'app',
    data: () => ({
        loaded: false,
        newName: '',
        newEmail: '',
        newPassword: '',
    }),
    mounted() {
        this.loaded = true
    },
    async created() {
        await this.$store.dispatch('user/loadUsers')
    },
    computed: {
        users() {
            return this.$store.state.user.users
        }
    },
    methods: {
        async registerUser() {
            await this.$store.dispatch('user/register', {
                name: this.newName,
                email: this.newEmail,
                password: this.newPassword,
                no_login: true
            })
            this.$notify({
                title: 'Success',
                message: this.newName + ' has been created!',
                type: 'success'
            })
            await this.$store.dispatch('user/loadUsers')
            this.newName = ''
            this.newEmail = ''
            this.newPassword = ''
        },
        async approveUser(row) {
            if (confirm('Are you sure?')) {
                await this.$store.dispatch('user/approveUser', row.id)
                await this.$store.dispatch('user/loadUsers')
                this.$notify({
                    title: 'Success',
                    message: 'User approved!',
                    type: 'success'
                })
            }
        },
        async disapproveUser(row) {
            if (confirm('Are you sure?')) {
                await this.$store.dispatch('user/disapproveUser', row.id)
                await this.$store.dispatch('user/loadUsers')
                this.$notify({
                    title: 'Success',
                    message: 'User disapproved!',
                    type: 'success'
                })
            }
        },
    }
}
</script>

<style scoped>

</style>