export const state = () => ({})

export const mutations = {}

export const actions = {
    
    async getAllSales({ dispatch }, params) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query{
            dashboardSalesForOps(
                start: "${params.start}",
                end: "${params.end}"
            ) {
                item
                type
                amount
            }
        }`);
        return data.dashboardSalesForOps
    },
}