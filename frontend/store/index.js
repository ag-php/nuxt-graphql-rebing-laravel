const cookieparser = process.server ? require('cookieparser') : undefined
const jwt = process.server ? require('jsonwebtoken') : undefined

export const state = () => {
  return {

  }
}

export const mutations = {

}

export const actions = {
  async nuxtServerInit ({ commit, dispatch }, { req }) {
    if (req.headers.cookie) {
      const parsed = cookieparser.parse(req.headers.cookie)
      try {
        const token = parsed.token
        if (token) {
            jwt.verify(token, process.env.JWT_SECRET)
            commit('user/setToken', token)
            await dispatch('user/loadUser')
            await dispatch('user/loadRoutes')
            return
        }
      } catch (err) {
        console.log('Server init failed')
      }
    }
    commit('user/setToken', null)
    commit('user/setUser', null)
  }
}

export const getters = {
    sidebar: state => state.app.sidebar,
    device: state => state.app.device,
    token: state => state.user.token,
    avatar: state => state.user.avatar,
    name: state => state.user.name,
    routes: state => state.user.routes,
    loadingReport: state => state.user.loadingReport
}