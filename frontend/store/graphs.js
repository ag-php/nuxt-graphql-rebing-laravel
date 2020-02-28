export const state = () => ({})

export const mutations = {}

const months = [
    'Jan',
    'Feb',
    'Mar',
    'Apr',
    'May',
    'Jun',
    'Jul',
    'Aug',
    'Sep',
    'Oct',
    'Nov',
    'Dec',
]

const minOne = x => (x < 1) ? 1 : x

const padZero = a => {
    let l = a.length
    if (l === 13) {
        return a
    } else {
        let diff = 13 - l
        let i
        for (i = 1; i <= diff; i++) {
            a.unshift(0)
        }
        return a
    }
}

export const actions = {
    async period({ dispatch }, periodNum) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            period(period: ${periodNum}) {
                description
                month
                year
            }
        }`)
        return data.period
    },
    async periodReverse({ dispatch }, selectedDateTime) {
        let d = new Date(selectedDateTime)
        let month = months[d.getMonth()]
        let year = d.getFullYear()

        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            periodReverse(month: "${month}", year: "${year}")
        }`)
        return data.periodReverse
    },
    async locations({ dispatch }) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            locations {
                acct_id
                acct
                parent
                acct_type
            }
        }`)
        return data.locations
    },
    async categories({ dispatch }) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            categories {
                id
                name
            }
        }`)
        return data.categories
    },
    async graphOneTotals({ dispatch }, params) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            graphOneTotals(
              location: "${params.location}", 
              locationId: ${params.locationId}, 
              isParent: ${params.isParent},
              period: ${params.period}, 
              selector: "${params.selector}")
        }`)
        return data.graphOneTotals
    },
    async graphOne({ dispatch }, params) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            graphOne(
              location: "${params.location}",
              period: ${params.period},
              locationId: ${params.locationId}, 
              isParent: ${params.isParent},
              ) {
                location
                p
                m
                y
                amount
            }
        }`)
        return data.graphOne
    },
    async graphTwo({ dispatch }, params) {
        const client = await dispatch('user/gqlClient', true, { root: true })
        const data = await client.request(`
        query {
            graphTwo(
              location: "${params.location}", 
              period: ${params.period},
              locationId: ${params.locationId}, 
              isParent: ${params.isParent},
              ) {
                location
                account
                amount
            }
        }`)
        return data.graphTwo
    },
    async graphFiveCOGS({ dispatch }, params) {
        const client = await dispatch('user/gqlClient', true, { root: true })

        let p = parseInt(params.p)

        let endP = minOne(p)
        let startP = minOne(p - 13)

        let endLYP = minOne(endP - 13)
        let startLYP = minOne(startP - 13)

        let [actualsDataCY, actualsDataLY, selectorData] = await Promise.all([
            client.request(`query {
                graphFiveCOGS(
                    location: "${params.location}",
                    locationId: ${params.locationId}, 
                    isParent: ${params.isParent},
                    selector: "actuals",
                    startP: ${startP},
                    endP: ${endP}
                )
            }`),
            client.request(`query {
                graphFiveCOGS(
                    location: "${params.location}",
                    locationId: ${params.locationId}, 
                    isParent: ${params.isParent},
                    selector: "actuals",
                    startP: ${startLYP},
                    endP: ${endLYP}
                )
            }`),
            client.request(`query {
                graphFiveCOGS(
                    location: "${params.location}",
                    locationId: ${params.locationId}, 
                    isParent: ${params.isParent},
                    selector: "${params.selector}",
                    startP: ${startP},
                    endP: ${endP}
                )
            }`)
        ])

        return {
            actualsCYCOGS: padZero(actualsDataCY.graphFiveCOGS),
            actualsLYCOGS: padZero(actualsDataLY.graphFiveCOGS),
            targetCOGS: padZero(selectorData.graphFiveCOGS)
        }
    },
    async graphSixIndividual({ dispatch }, params) {
        const client = await dispatch('user/gqlClient', true, { root: true })

        let p = parseInt(params.p)

        let endP = minOne(p)
        let startP = minOne(p - 13)


        let [actualsData, selectorData] = await Promise.all([
            client.request(`query {
                graphSixIndividual(
                    category: ${params.category},
                    location: "${params.location}",
                    locationId: ${params.locationId}, 
                    isParent: ${params.isParent},
                    selector: "actuals",
                    startP: ${startP},
                    endP: ${endP}
                )
            }`),
            client.request(`query {
                graphSixIndividual(
                    category: ${params.category},
                    location: "${params.location}",
                    locationId: ${params.locationId}, 
                    isParent: ${params.isParent},
                    selector: "${params.selector}",
                    startP: ${startP},
                    endP: ${endP}
                )
            }`)
        ])

        return {
            actualsData: padZero(actualsData.graphSixIndividual),
            selectorData: padZero(selectorData.graphSixIndividual)
        }
    }
}