<template>
    <div>
        <div>
            <h1>Organizations</h1>
        </div>
        <div style="margin-top: 50px; width: 50%">
            Create Organization:<br>
            <el-input placeholder="New Org Name" v-model="newName" style="width:75%;margin-right:15px;"></el-input>
            <el-button @click="createOrganization" icon="el-icon-circle-plus-outline" circle></el-button>
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
            <el-table-column label="Actions" align="center" width="230" class-name="small-padding fixed-width">
                <template slot-scope="{row}">
                <el-button @click="handleModifyStatus(row, 'delete')" size="mini" type="danger" style="float:left;margin-left:15px">
                    Delete
                </el-button>
                <el-button v-if="row.id != defaultId" @click="handleModifyStatus(row, 'default')" size="mini" type="success">
                    Activate
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
    mounted() {
        this.loaded = true
    },
    async created() {
        await this.$store.dispatch('user/loadOrganizations')
    },
    data: () => ({
        loaded: false,
        newName: '',
    }),
    computed: {
        organizations() {
            return this.$store.state.user.organizations
        },
        defaultId() {
            return this.$store.state.user.default_organization
        }
    },
    methods: {
        async createOrganization() {
            try {
                await this.$store.dispatch('user/createOrganization', this.newName)
                this.newName = ''
                this.$notify({
                    title: 'Success',
                    message: this.newName + ' has been created!',
                    type: 'success'
                })
            } catch (e) {
                this.$notify.error({
                    title: 'Error',
                    message: 'User not approved by admin yet'
                });
            }
        },
        async handleModifyStatus(row, status) {
            if (confirm('Are you sure?')) {
                if (status === 'delete') {
                    await this.$store.dispatch('user/removeOrganization', row.id)
                    this.$notify({
                        title: 'Success',
                        message: 'Organization deleted!',
                        type: 'success'
                    })
                } else if (status === 'default') {
                    await this.$store.dispatch('user/setDefaultOrganization', row.id)
                    this.$notify({
                        title: 'Success',
                        message: 'Organization selected!',
                        type: 'success'
                    })
                }

            }
        },
    }
}
</script>

<style scoped>

</style>