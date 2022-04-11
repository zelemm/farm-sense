import axios from 'axios'

export default  {
  namespaced: true,

  state: {
    user: null,
  },

  mutations: {
    getProfileSuccess(state, user) {
      state.user = user
    },

    updateProfileSuccess(state, user) {
      this.state.auth.user = user
      if (user ) {
        state.user = user
      }
    },
  },

  actions: {
    find({commit}, id) {
      return new Promise((resolve, reject) => {
        axios.get(`/super_admin/farms/${id}`)
          .then((res) => {
            resolve(res.data.data.farms)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    create({commit}, farmForm) {
      return new Promise((resolve, reject) => {
        axios
          .post('/super_admin/farms', farmForm, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    update({commit}, farmForm) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/super_admin/farms/${farmForm.get('id')}`, farmForm, {
            headers: { 'Content-Type': 'multipart/form-data' },
          })
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    delete({commit}, farmId) {
      return new Promise((resolve, reject) => {
        axios
          .delete(`/super_admin/farms/${farmId}`)
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    restore({commit}, farmId) {
      return new Promise((resolve, reject) => {
        axios
          .put(`/super_admin/farms/${farmId}/restore`)
          .then((res) => {
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    getProfile({commit}) {
      return new Promise((resolve, reject) => {
        axios
          .get('/super_admin/get-profile')
          .then((res) => {
            commit('getProfileSuccess', res.data.data.user)
            resolve(res.data.data.user)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    updateProfile({commit}, profileForm) {
      return new Promise((resolve, reject) => {
        axios
          .post(`/super_admin/${profileForm.get('id')}/update-profile`, profileForm)
          .then((res) => {
            commit('updateProfileSuccess', res.data.data.user)
            resolve(res)
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
  },

  getters: {
    user: (state) => state.user,
  },
}
