export const state = () => ({
  showSettings: false,
  fixedHeader: true,
  sidebarLogo: true
})

export const mutations = {
  CHANGE_SETTING: (state, { key, value }) => {
    if (state.hasOwnProperty(key)) {
      state[key] = value
    }
  }
}

export const actions = {
  changeSetting({ commit }, data) {
    commit('CHANGE_SETTING', data)
  }
}


