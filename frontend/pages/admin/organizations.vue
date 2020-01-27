<template>
    <div>
    <div>
        <h1>Organizations</h1>
    </div>
    <client-only placeholder="Loading...">
        <el-table
            :data="organizations"
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
                <span class="link-type" @click="handleUpdate(row)">{{ row.name }}</span>
                </template>
            </el-table-column>
            <el-table-column label="Actions" align="center" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                <el-button v-if="row.status!='deleted'" @click="loadMembers(row)" type="primary">
                    Load Membership
                </el-button>
                </template>
            </el-table-column>
        </el-table>

        <br><br>
        <h3 v-if="showMembers">
            Members
        </h3>
        <div style="margin-top: 50px; width: 50%" v-if="showMembers">
            Add User:<br>
            <el-select v-model="newUserId" placeholder="Select" style="width:75%;margin-right:15px;">
                <el-option
                v-for="user in users"
                :key="user.id"
                :label="user.name"
                :value="user.id">
                </el-option>
            </el-select>
            <el-button @click="addUser" icon="el-icon-circle-plus-outline" circle></el-button>
        </div>
        <el-table
            title="Members"
            :data="members"
            v-if="showMembers"
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
                <el-button @click="removeUser(row)"  type="danger">
                    Remove
                </el-button>
                </template>
            </el-table-column>
        </el-table>

    </client-only>
    </div>
</template>

<script>
export default {
    middleware: 'authenticated',
    layout: 'app',
    data: () => ({
        loaded: false,
        members: [],
        newUserId: null,
        users: [],
        currentOrganization: {},
    }),
    mounted() {
        this.loaded = true
    },
    async created() {
        await this.$store.dispatch('user/loadOrganizations', true)
        this.users = await this.$store.dispatch('user/getUsers')
    },
    computed: {
        organizations() {
            return this.$store.state.user.organizations
        },
        showMembers() {
            return !!this.members.length
        }
    },
    methods: {
        async addUser() {
            await this.$store.dispatch('user/joinOrganization', {
                organization_id: this.currentOrganization.id,
                user_id: this.newUserId
            })
            this.$notify({
                title: 'Success',
                message: 'User added!',
                type: 'success'
            })
            this.members = await this.$store.dispatch('user/members', this.currentOrganization.id)
        },
        handleUpdate() {

        },
        async loadMembers(organization) {
            this.currentOrganization = organization
            this.members = await this.$store.dispatch('user/members', organization.id)
        },
        async removeUser(user) {
            await this.$store.dispatch('user/leaveOrganization', {
                organization_id: this.currentOrganization.id,
                user_id: user.id
            })
            this.$notify({
                title: 'Success',
                message: 'User removed!',
                type: 'success'
            })
            this.members = await this.$store.dispatch('user/members', this.currentOrganization.id)
        }
    }
}
</script>

<style scoped>

</style>