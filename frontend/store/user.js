const GQL_ENDPOINT = process.env.gqlEndpoint
const cookie = process.client ? require('js-cookie') : undefined

import { GraphQLClient } from 'graphql-request'

export const state = () => ({
    id: 0,
    token: '',
    default_organization: 0,
    avatar: '/user-default.png',
    name: '',
    email: '',
    routes: {
        user: [],
        admin: []
    },
    organizations: [],
    users: [],
})

export const mutations = {
    setToken (state, token) {
        state.token = token
    },
    setId (state, id) {
        state.id = id
    },
    setName (state, name) {
        state.name = name
    },
    setEmail (state, email) {
        state.email = email
    },
    setUser (state, user) {
        if (user) {
            state.id = user.id
            state.name = user.name
            state.email = user.email
            state.default_organization = user.default_organization
        } else {
            state.id = 0
            state.name = ''
            state.email = ''
            state.default_organization = 0
        }
    },
    setUsers (state, users) {
        state.users = users
    },
    setRoutes (state, routes) {
        state.routes = routes
    },
    setOrganizations (state, organizations) {
        state.organizations = organizations
    }
}

export const actions = {
    /**
     * Instantiates a GraphQL Client
     * Uses Auth Header if token present in state
     */
    async gqlClient ({ commit, state }, useAuth)  {
        return new GraphQLClient(
            GQL_ENDPOINT,
            (useAuth && state.token) ?
                { headers: { authorization: `Bearer ${state.token}` } }
                : {}
        )
    },
    /**
     * Login and load User
     *
     * @param {*} credentials Username and Password object
     */
    async login ({ commit, dispatch }, credentials) {
        const client = await dispatch('gqlClient', false)
        const data = await client.request(`
        query {
            login(
                email:"${credentials.username}",
                password:"${credentials.password}"
            ) {
                access_token
            }
        }`)
        const token = data && data.login ? data.login.access_token : null
        await dispatch('handleToken', token)
    },
    /**
     * Remove client auth from store and cookies
     */
    logout({ commit, dispatch }) {
        commit('setToken', '')
        cookie.remove('token')
    },
    /**
     * Register user in API
     *
     * @param {*} details Name, email, password
     */
    async register({ commit, dispatch }, details) {
        const client = await dispatch('gqlClient', false)
        const data = await client.request(`
        mutation {
            register(
                name: "${details.name}",
                email: "${details.email}",
                password: "${details.password}"
                ${details.no_login ? ',no_login: ' + details.no_login : '' }
            ) {
                access_token
            }
        }`)
        if (details.no_login) {
            return !!data.register
        } else {
            const token = data && data.register ? data.register.access_token : null
            await dispatch('handleToken', token)
        }
    },
    /**
     * Handles token from login and register, loads user updates store
     *
     * @param {*} token
     */
    async handleToken({ commit, dispatch }, token) {
        if (!token) {
            throw new Error('Operation failed')
        } else {
            commit('setToken', token)
            cookie.set('token', token)
            await dispatch('loadUser')
            await dispatch('loadRoutes')
        }
    },
    /**
     * Get user from API, store in state
     */
    async loadUser({ commit, dispatch }) {
        let user = await dispatch('getUser')
        if (user) {
            commit('setUser', user)
        }
    },

    async loadUsers({ commit, dispatch }) {
        let users = await dispatch('getUsers')
        if (users) {
            commit('setUsers', users)
        }
    },
    /**
     * Get user from API
     */
    async getUser({ commit, dispatch }) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        query {
            user {
                id
                name
                email
                default_organization
            }
        }`)
        if (data.user) {
            return data.user
        } else {
            return null
        }
    },
/**
     * Get all users from API
     */
    async getUsers({ commit, dispatch }) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        query {
            users {
                id
                name
                email
                approved
            }
        }`)
        if (data.users) {
            return data.users
        } else {
            return null
        }
    },
    async approveUser({ commit, dispatch }, user_id) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            approveUser(user_id: ${user_id}) {
                id
                approved
            }
        }`)
        return data && data.approveUser ? data.approveUser.approved : null
    },
    async disapproveUser({ commit, dispatch }, user_id) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            disapproveUser(user_id: ${user_id}) {
                id
                approved
            }
        }`)
        return data && data.disapproveUser ? data.disapproveUser.approved : null
    },
    /**
     * Fetch the user's available frontend routes based on role (admin or editor)
     */
    async loadRoutes({ commit, dispatch }) {
        const client = await dispatch('gqlClient', true)
        const userRoutes = await client.request(`
        query {
            routes {
                name
                path
                icon
            }
        }`)
        const adminRoutes = await client.request(`
        query {
            routes(admin: true) {
                name
                path
                icon
            }
        }`)

        commit('setRoutes', {
            user: userRoutes.routes,
            admin: adminRoutes.routes
        })
    },
    /**
     * Get user from API, store in state
     */
    async loadOrganizations({ commit, dispatch }, all) {
        let organizations = await dispatch('getOrganizations', all)
        if (organizations) {
            commit('setOrganizations', organizations)
        }
    },
    /**
     * Get user from API
     */
    async getOrganizations({ commit, dispatch }, all) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        query {
            organizations${ all ? '(all: true)' : '' } {
                id
                name
            }
        }`)
        if (data.organizations) {
            return data.organizations
        } else {
            return null
        }
    },
    async createOrganization({ commit, dispatch }, newName) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            createOrganization(name: "${newName}") {
                id
                name
            }
        }`)
        if (data.createOrganization) {
            await dispatch('loadOrganizations')
            return data.createOrganization
        } else {
            return null
        }
    },
    async removeOrganization({ commit, dispatch }, orgId) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            removeOrganization(organization_id: ${orgId})
        }`)
        if (data.removeOrganization) {
            await dispatch('loadOrganizations')
            return data.removeOrganization
        } else {
            return null
        }
    },
    async setDefaultOrganization({ commit, dispatch }, orgId) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            setDefaultOrganization(
                organization_id: ${orgId},
            )
        }`)
        if (data.setDefaultOrganization) {
            return data.setDefaultOrganization
        } else {
            return null
        }
    },
    async leaveOrganization({ commit, dispatch }, payload) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            leaveOrganization(
                organization_id: ${payload.organization_id},
                user_id: ${payload.user_id}
            )
        }`)
        if (data.leaveOrganization) {
            return data.leaveOrganization
        } else {
            return null
        }
    },
    async joinOrganization({ commit, dispatch }, payload) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        mutation {
            joinOrganization(
                organization_id: ${payload.organization_id},
                user_id: ${payload.user_id}
            ) {
                id
            }
        }`)
        if (data.joinOrganization) {
            return data.joinOrganization
        } else {
            return null
        }
    },
    async members({ commit, dispatch }, orgId) {
        const client = await dispatch('gqlClient', true)
        const data = await client.request(`
        query {
            members(organization_id: ${orgId}) {
                id
                name
            }
        }`)
        if (data.members) {
            return data.members
        } else {
            return null
        }
    }
}